<?php

namespace App\Services\Payment;

use App\Enums\PaymentStatus;
use App\Events\PaymentCompleted;
use App\Models\Payment;
use App\Services\Payment\PaymentTypeHandlerManager;
use Illuminate\Support\Facades\DB;

class PaymentCompletionService
{
    public function __construct(
        protected PaymentTypeHandlerManager $paymentTypeHandlerManager,
    ) {}

    public function handle(Payment $payment): Payment
    {
        $payment = DB::transaction(function () use ($payment) {

            // Lock payment row to prevent concurrent completion
            $payment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($payment->id);


            if ($payment->status === PaymentStatus::SUCCESS) {
                return $payment;
            }

            $payment->update([
                'status' => PaymentStatus::SUCCESS,
                'paid_at' => now(),
            ]);

            $this->paymentTypeHandlerManager
                ->driver($payment->type)
                ->handle($payment);

            return $payment->fresh();
        });

        event(new PaymentCompleted($payment));

        return $payment;
    }
}
