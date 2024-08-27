<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Mail\OrderCreatedAdminMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class OrderSendAdminListener implements ShouldQueue
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
        $adminEmail = config('mail.admin');

        foreach ($adminEmail as $email)
        {
            Mail::to($email)->send(new OrderCreatedAdminMail($event->order, $email));
        }

    }
}
