<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use App\Enums\PaymentStatus;

class ZarinpalGateway implements PaymentGatewayInterface
{
    protected string $merchantId;

    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantId =
            config('services.zarinpal.merchant_id');

        $sandbox =
            config('services.zarinpal.sandbox');

        $this->baseUrl = $sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment'
            : 'https://payment.zarinpal.com/pg/v4/payment';
    }

    public function purchase(Payment $payment): string
    {
        $response = Http::timeout(30)->post(
            $this->baseUrl . '/request.json',
            [
                'merchant_id' => $this->merchantId,

                'amount' => (int)$payment->amount,

                'description' =>
                    "Payment #{$payment->id}",

                'callback_url' => route(
                    'payment.callback',
                    $payment
                ),
            ]
        );

        $result = $response->json();

        if (
        !isset($result['data']['authority'])
        ) {
            throw new \RuntimeException(
                'Zarinpal request failed.'
            );
        }

        $authority =
            $result['data']['authority'];

        $payment->update([
            'authority' => $authority,
            'transaction_id' => $authority,
            'gateway_response' => $result,
        ]);

        return $this->baseUrl ===
        'https://sandbox.zarinpal.com/pg/v4/payment'
            ? "https://sandbox.zarinpal.com/pg/StartPay/{$authority}"
            : "https://payment.zarinpal.com/pg/StartPay/{$authority}";
    }

    public function verify(Payment $payment, array $callbackData = []): bool
    {
        if (
            ($callbackData['Status'] ?? null)
            !== 'OK'
        ) {
            return false;
        }

        $response = Http::post(
            $this->baseUrl . '/verify.json',
            [
                'merchant_id' => $this->merchantId,

                'amount' => (int)$payment->amount,

                'authority' =>
                    $callbackData['Authority'],
            ]
        );

        $result = $response->json();

        $payment->update([
            'paid_at' => now(),
            'reference_id' => $result['data']['ref_id'],
            'gateway_response' => $result,
            'status' => PaymentStatus::SUCCESS->value,
        ]);

        return ($result['data']['code'] ?? null) === 100;
    }
}
