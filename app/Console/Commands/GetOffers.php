<?php

namespace App\Console\Commands;

use App\Jobs\Parser\GetOfferJob;
use App\Models\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Offers';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $result = Result::latest()->take(20)->get();
        $result->each(function ($item) {
            Log::debug('Create Job ID: ' . $item->offerId);
            GetOfferJob::dispatch($item)->delay(now()->addSeconds(30));
        });

        $this->call('queue:work', [
            '--tries' => 3
        ]);

        return Command::SUCCESS;

    }
}
