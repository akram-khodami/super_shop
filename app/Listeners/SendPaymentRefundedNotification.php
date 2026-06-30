<?php

namespace App\Listeners;

use App\Events\PaymentRefunded;
use App\Notifications\Payment\PaymentRefundedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentRefundedNotification
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
    public function handle(PaymentRefunded $event): void
    {
        $event->payment->user->notify(

            new PaymentRefundedNotification(
                $event->payment
            )

        );
    }
}
