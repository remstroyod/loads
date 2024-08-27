<?php

namespace App\Console\Commands;

use App\Jobs\Parser\GetResultsJob;
use Illuminate\Console\Command;

class GetResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Results Parser';

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

        GetResultsJob::dispatch();

        $this->call('queue:work', [
            '--tries' => 3
        ]);

        return Command::SUCCESS;

    }
}
