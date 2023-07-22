<?php

namespace App\Listeners;

use App\Events\SendEmailEvent;
use App\Mail\UserSendMail;
use Illuminate\Support\Facades\Mail;

class SendMailListener
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
    public function handle(SendEmailEvent $event): void
    {
        $user = $event->user;
        Mail::to($user->email)->later(now()->addSeconds(5), new UserSendMail($user));
    }
}
