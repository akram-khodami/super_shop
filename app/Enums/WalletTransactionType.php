<?php

namespace App\Enums;

enum WalletTransactionType: string
{
case DEPOSIT = 'deposit';

case WITHDRAW = 'withdraw';
}
