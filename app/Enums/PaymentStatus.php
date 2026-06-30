<?php

namespace App\Enums;

use App\Traits\HasLabelTranslation;

enum PaymentStatus: string
{
    use HasLabelTranslation;

    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case CANCELED = 'canceled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return __("messages.payment_statuses." . $this->value);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
