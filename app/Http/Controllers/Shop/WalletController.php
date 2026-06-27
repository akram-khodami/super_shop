<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\WalletService;

class WalletController extends Controller
{

    public function index(WalletService $walletService)
    {

        $wallet = $walletService->getWallet(auth()->user());

        $wallet->load([
            'transactions.payment'
        ]);

        $transactionsCount = $wallet->transactions->count();
        $transactionsSumAmount = $wallet->transactions->where('type', 'deposit')->sum('amount');
        $lastTransactionTime = $wallet->transactions->first() ?->created_at ?->diffForHumans();

        return view('shop.wallet.index', compact('wallet', 'transactionsCount', 'transactionsSumAmount', 'lastTransactionTime'));
    }
}
