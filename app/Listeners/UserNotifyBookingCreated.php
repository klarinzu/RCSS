<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\UserNotificationBookingCreated;
use Illuminate\Support\Facades\Notification;

class UserNotifyBookingCreated
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
    public function handle(BookingCreated $event): void
    {
        $email = $event->appointment->email;
        if ($email) {
            Notification::route('mail',$email)->notify(new UserNotificationBookingCreated($event->appointment));
        }
    }
}
