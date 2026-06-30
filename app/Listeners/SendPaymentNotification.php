<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Notifications\PaymentSuccessfulNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentNotification
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
    public function handle(PaymentCompleted $event): void
    {
        $event->payment
            ->user
            ->notify(
                new PaymentSuccessfulNotification(
                    $event->payment
                )
            );
    }
}
