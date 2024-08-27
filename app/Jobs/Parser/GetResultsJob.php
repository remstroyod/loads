<?php

namespace App\Jobs\Parser;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetResultsJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $tries = 5;
    public $timeout = 3600;
    public $backoff = 30;
    private $days = 30;

    public function __construct()
    {

    }

    /**
     * @return void
     */
    public function handle(): void
    {

        try {

            $carbon = new \Carbon\Carbon(now());
            $carbon->setTimezone('Europe/Moscow');

            $dateStart = $carbon->toIso8601String();

            $carbon->addMonth();
            $carbon->endOfDay();

            $dateEnd = $carbon->toIso8601String();

            GetResultsByDateJob::dispatch($dateStart, $dateEnd);

        }catch (Exception $e) {

            Log::debug(sprintf('File - %s Line - %s | Error (GetResultsJob) Get Results: %s', __FILE__, __LINE__, $e->getMessage()));

        }

    }

    public function failed(Throwable $exception)
    {
        Log::debug(sprintf('File - %s Line - %s | Error (GetResultsJob) Get Results: %s', __FILE__, __LINE__, $exception->getMessage()));
    }

}
