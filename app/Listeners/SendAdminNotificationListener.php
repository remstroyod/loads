<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\UserRegisteredAdminMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAdminNotificationListener implements ShouldQueue
{
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
    public function handle(object $event): void
    {
        $adminEmail = config('mail.admin');

        foreach ($adminEmail as $email)
        {
            Mail::to($email)->send(new UserRegisteredAdminMail($event->user, $email));
        }

    }
}
