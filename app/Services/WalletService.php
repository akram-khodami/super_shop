<?php

namespace App\Services;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Increase wallet balance.
     */
    public function deposit(
        User $user,
        float $amount,
        ?Payment $payment = null,
        ?string $description = null
    ): WalletTransaction
    {

        return DB::transaction(function () use (
            $user,
            $amount,
            $payment,
            $description
        ) {

            $wallet = $this->getWallet($user);

            $balanceBefore = $wallet->balance;

            $balanceAfter = $balanceBefore + $amount;

            $wallet->update([
                'balance' => $balanceAfter,
            ]);

            return WalletTransaction::create([

                'wallet_id' => $wallet->id,

                'payment_id' => $payment ?->id,

                'type' => 'deposit',

                'amount' => $amount,

                'balance_before' => $balanceBefore,

                'balance_after' => $balanceAfter,

                'description' => $description,
            ]);
        });
    }

    /**
     * Decrease wallet balance.
     */
    public function withdraw(
        User $user,
        float $amount,
        ?Payment $payment = null,
        ?string $description = null
    ): WalletTransaction
    {

        return DB::transaction(function () use (
            $user,
            $amount,
            $payment,
            $description
        ) {


            $wallet = Wallet::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($wallet->balance < $amount) {

                throw new InsufficientWalletBalanceException();
            }

            $balanceBefore = $wallet->balance;

            $balanceAfter = $balanceBefore - $amount;

            $wallet->update([
                'balance' => $balanceAfter,
            ]);

            return WalletTransaction::create([

                'wallet_id' => $wallet->id,

                'payment_id' => $payment ?->id,

                'type' => 'withdraw',

                'amount' => $amount,

                'balance_before' => $balanceBefore,

                'balance_after' => $balanceAfter,

                'description' => $description,
            ]);
        });
    }

    /**
     * Get user wallet or create it.
     */
    public function getWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'balance' => 0,
            ]
        );
    }
}
