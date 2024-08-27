<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderSendTelegramListener implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderEvent $event): void
    {

        $message = sprintf(
            "*New Order:* %s\n*User Name:* %s\n*User Email:* %s\n*Price:* %s",
            $event->order->id,
            $event->order->user->name,
            $event->order->user->email,
            $event->order->offerPrice
        );

        $api = new TelegramService();
        $api->sendMessage($message);

    }
}
