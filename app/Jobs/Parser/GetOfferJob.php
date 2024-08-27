<?php

namespace App\Jobs\Parser;

use App\Enums\OfferMilestonesTypeEnum;
use App\Models\Country;
use App\Models\Result;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetOfferJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $tries = 5;
    public $timeout = 3600;
    public $backoff = 30;

    private $url;

    private Result $result;

    public function __construct(Result $result)
    {

        $this->result = $result;
        $this->url = sprintf('%s/%s', env('PARSER_GET_RESULTS_OFFER_URL'), $result->offerId);
    }

    /**
     * @return void
     */
    public function handle(): void
    {


        try {

            Log::debug('Get Order: ' . $this->url);

            $response = Http::timeout(100)->get($this->url);

            Log::debug('Order Detail: ' . $response->collect()->toJson());

            if($response->ok() && $response->collect()->count())
            {

                $collect = $response->collect();

                if($collect->has('vehicleProperties'))
                {

                    $offer = $this->result->offer()->updateOrCreate([
                        'result_id' => $this->result->id
                    ],
                        [
                            'offerPrice' => $collect->get('offerPrice')
                        ]
                    );

                    $offer->properties()->updateOrCreate([
                        'offer_id' => $offer->id
                    ],
                        [
                            'height' => $collect->get('vehicleProperties')['height'],
                            'classification' => $collect->get('vehicleProperties')['classification']
                        ]
                    );

                    $offer->weights()->updateOrCreate([
                        'offer_id' => $offer->id
                    ],
                        [
                            'value' => $collect->get('totalWeight')['value'],
                            'unit' => $collect->get('totalWeight')['unit']
                        ]
                    );

                    $goods = collect($collect->get('goods'));
                    $goods->each(function ($item, $key) use ($offer, $collect) {
                        $offer->goods()->updateOrCreate([
                            'offer_id' => $offer->id,
                            'description' => $item['description']
                        ],
                            [
                                'description' => $item['description'],
                                'weight_value' => $item['weight']['value'],
                                'weight_unit' => $item['weight']['unit'],
                                'quantity_value' => $item['quantity']['value'],
                                'quantity_unit' => $item['quantity']['unit'],
                                'goodsTypeCode' => $item['goodsTypeCode'],
                            ]
                        );
                    });

                    $milestones = collect($collect->get('milestones'));
                    $milestones->each(function ($item, $key) use ($offer, $collect) {

                        $country = Country::where('iso', $item['address']['countryCode'])->first();
                        $offer->milestones()->updateOrCreate([
                            'offer_id' => $offer->id,
                            'milestoneId' => $item['milestoneId']
                        ],
                            [
                                'milestoneId' => $item['milestoneId'],
                                'type' => !empty($item['type']) ? OfferMilestonesTypeEnum::getValueFromName($item['type']) : 0,
                                'rta_start' => array_key_exists('start', $item['rta']) ? $item['rta']['start'] : null,
                                'rta_end' => array_key_exists('end', $item['rta']) ? $item['rta']['end'] : null,
                                'address_countryCode' => $country ? $country->id : null,
                                'address_zipCode' => $item['address']['zipCode'],
                                'address_city' => $item['address']['city'],
                                'address_loadingTimes_onloading' => $item['address']['loadingTimes']['onloading'],
                                'address_loadingTimes_offloading' => $item['address']['loadingTimes']['offloading'],
                            ]
                        );
                    });

                }else {
                    Log::debug(sprintf('File - %s Line - %s | Error (GetOfferJob) Get Offer: %s', __FILE__, __LINE__, $response->collect()->toJson()));
                }

            }else{
                Log::debug(sprintf('GetOfferJob Empty Results: %s',  $response->json()));
            }

        }catch (Exception $e) {

            Log::debug(sprintf('File - %s Line - %s | Error (GetOfferJob) Get Offer: %s', __FILE__, __LINE__, $e->getMessage()));
            Log::debug(sprintf('File - %s Line - %s | Error (GetOfferJob) Get Offer: %s', __FILE__, __LINE__, $response->collect()->toJson()));

        }

    }

    public function failed(Throwable $exception)
    {
        Log::debug(sprintf('File - %s Line - %s | Error (GetOfferJob) Get Offer: %s', __FILE__, __LINE__, $exception->getMessage()));
    }

}
