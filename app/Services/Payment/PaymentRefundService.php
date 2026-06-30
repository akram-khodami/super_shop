<?php

namespace App\Services\Payment;

use App\Enums\PaymentStatus;
use App\Events\PaymentRefunded;
use App\Exceptions\PaymentNotRefundableException;
use App\Models\Payment;
use App\Services\Payment\Refund\RefundTypeHandlerManager;
use Illuminate\Support\Facades\DB;

class PaymentRefundService
{
    public function __construct(
        protected RefundTypeHandlerManager $refundTypeHandlerManager
    ) {}

    public function refund(Payment $payment): Payment
    {
        $payment = DB::transaction(function () use ($payment) {

            // Lock payment row to prevent concurrent refunds
            $payment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($payment->id);


            if ($payment->status !== PaymentStatus::SUCCESS) {
                throw new PaymentNotRefundableException($payment->status);
            }

            $payment->update([
                'status' => PaymentStatus::REFUNDED, //todo:refunded_at migration
            ]);

            $this->refundTypeHandlerManager
                ->driver($payment->type)
                ->handle($payment);

            return $payment->fresh();
        });

        event(new PaymentRefunded($payment));

        return $payment;
    }
}
