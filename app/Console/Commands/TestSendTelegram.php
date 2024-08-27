<?php

namespace App\Console\Commands;

use App\Jobs\Parser\GetResultsJob;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class TestSendTelegram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:telegram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Message to Telegram';

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


        $orderId = 101;
        $userName = 'Alex Cherniy';
        $email = 'remstroyod@gmail.com';

        $message = sprintf(
            "*New Order:* %s\n*User Name:* %s\n*User Email:* %s\n*Price:* %s",
            $orderId,
            $userName,
            $email,
            2700
        );

        $api = new TelegramService();
        $api->sendMessage($message);


        return Command::SUCCESS;

    }
}
