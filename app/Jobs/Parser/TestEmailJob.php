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
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestEmailJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $tries = 5;
    public $timeout = 3600;
    public $backoff = 30;

    public function __construct()
    {


    }

    /**
     * @return void
     */
    public function handle(): void
    {

        Mail::raw('Это тестовое письмо', function ($message) {
            $message->to('remstroyod@gmail.com')->subject('Тестовое письмо');
        });

    }

    public function failed(Throwable $exception)
    {
        Log::debug(sprintf('File - %s Line - %s | Error (GetOfferJob) Get Offer: %s', __FILE__, __LINE__, $exception->getMessage()));
    }

}
