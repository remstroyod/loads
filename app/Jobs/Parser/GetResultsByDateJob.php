<?php

namespace App\Jobs\Parser;

use App\Models\Country;
use App\Models\Result;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetResultsByDateJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $tries = 5;
    public $timeout = 3600;
    public $backoff = 30;

    private $url;

    private $dateStart;
    private $dateEnd;

    public function __construct($dateStart, $dateEnd)
    {
        $this->url = env('PARSER_GET_RESULTS_URL');
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return void
     */
    public function handle(): void
    {

        try {

            Log::debug('DATE START: ' . $this->dateStart);
            Log::debug('DATE END: ' . $this->dateEnd);

            $response = Http::timeout(100)->post($this->url, [
                'startDate' => $this->dateStart,
                'endDate'   => $this->dateEnd,
            ]);

            //Log::debug($response->collect()->toJson());

            if($response->ok() && $response->collect()->has('result') && collect($response->collect()->get('result'))->count())
            {

                $collection = collect($response->collect()->get('result'));

                $collection->each(function ($item) {

                    $result = Result::query()->updateOrCreate(
                        [
                            'offerId' => $item['offerId']
                        ],
                        [
                            'offerId' => $item['offerId'],
                            'offerPrice' => $item['offerPrice'],
                            'roadDistanceKm' => array_key_exists('roadDistanceKm', $item) ? $item['roadDistanceKm'] : 0,
                            'date_parse' => Carbon::make($this->dateStart),
                        ]
                    );

                    $resultOnloading = $result->onloading()->updateOrCreate(
                        [
                            'result_id'  => $result->id
                        ],
                        [
                            'rtaStart' => $item['onloading']['rtaStart'] ?? null,
                            'rtaEnd' => $item['onloading']['rtaEnd'] ?? null
                        ]
                    );

                    $countryCode = !empty($item['onloading']['firstOnloadingPoint']['countryCode']) ? Country::where('iso', $item['onloading']['firstOnloadingPoint']['countryCode'])->first() : null;
                    $resultOnloadingPoint = $resultOnloading->point()->updateOrCreate(
                        [
                            'result_onloading_id'  => $resultOnloading->id
                        ],
                        [
                            'countryCode' => !is_null($countryCode) ? $countryCode->id : null,
                            'city' => $item['onloading']['firstOnloadingPoint']['city'] ?? null,
                            'zipCode' => $item['onloading']['firstOnloadingPoint']['zipCode'] ?? null,
                            'rtaStart' => $item['onloading']['firstOnloadingPoint']['rtaStart'] ?? null,
                            'rtaEnd' => $item['onloading']['firstOnloadingPoint']['rtaEnd'] ?? null,
                        ]
                    );

                    $resultOffloading = $result->offloading()->updateOrCreate(
                        [
                            'result_id'  => $result->id
                        ],
                        [
                            'rtaStart' => $item['offloading']['rtaStart'] ?? null,
                            'rtaEnd' => $item['offloading']['rtaEnd'] ?? null
                        ]
                    );

                    $countryCode = !empty($item['offloading']['lastOffloadingPoint']['countryCode']) ? Country::where('iso', $item['offloading']['lastOffloadingPoint']['countryCode'])->first() : null;
                    $resultOffloadingPoint = $resultOffloading->point()->updateOrCreate(
                        [
                            'result_offloading_id'  => $resultOffloading->id
                        ],
                        [
                            'countryCode' => !is_null($countryCode) ? $countryCode->id : null,
                            'city' => $item['offloading']['lastOffloadingPoint']['city'] ?? null,
                            'zipCode' => $item['offloading']['lastOffloadingPoint']['zipCode'] ?? null,
                            'rtaStart' => $item['offloading']['lastOffloadingPoint']['rtaStart'] ?? null,
                            'rtaEnd' => $item['offloading']['lastOffloadingPoint']['rtaEnd'] ?? null,
                        ]
                    );

                    $result->property()->updateOrCreate(
                        [
                            'result_id' => $result->id
                        ],
                        [
                            'height' => $item['requiredVehicleProperties']['height'] ?? null
                        ]
                    );

                    //GetOfferJob::dispatchSync($result->fresh());

                });
            }

        }catch (Exception $e) {

            Log::debug(sprintf('File - %s Line - %s | Error (GetResultsJob) Get Results By Date: %s', __FILE__, __LINE__, $e->getMessage()));

        }

    }

    public function failed(Throwable $exception)
    {
        Log::debug(sprintf('File - %s Line - %s | Error (GetResultsJob) Get Results By Date: %s', __FILE__, __LINE__, $exception->getMessage()));
    }

}
