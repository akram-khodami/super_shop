<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function index(WalletService $walletService)
    {

        $wallet = $walletService->getWallet(auth()->user());

        $wallet->load([
            'transactions.payment'
        ]);

        return view('shop.wallet.index', compact('wallet')
        );
    }
}
