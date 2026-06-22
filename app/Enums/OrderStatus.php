<?php

namespace App\Enums;

enum OrderStatus:string
{
    case PENDING='pending';
    case PROCESSING='processing';
    case SHIPPED='shipped';
    case DELIVERED='delivered';
    case COMPLETED='completed';
    case CANCELED='canceled';

    public function label(): string
    {
        return __("messages.order_statuses.".$this->value);
    }

    public static function options(): array
{
    return collect(self::cases())
        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
        ->toArray();
}

    public function badgeClass(): string
{
    return match ($this) {

    self::PENDING =>
            'bg-yellow-100 text-yellow-700',

        self::PROCESSING =>
            'bg-blue-100 text-blue-700',

        self::SHIPPED =>
            'bg-purple-100 text-purple-700',

        self::DELIVERED,
        self::COMPLETED =>
            'bg-green-100 text-green-700',

        self::CANCELED =>
            'bg-red-100 text-red-700',
    };
}

    public function canTransitionTo(
    self $newStatus
): bool {

    return in_array(
        $newStatus,
        match($this) {

        self::PENDING => [
        self::PROCESSING,
        self::CANCELED,
    ],

                self::PROCESSING => [
        self::SHIPPED,
        self::CANCELED,
    ],

                self::SHIPPED => [
        self::DELIVERED,
    ],

                self::DELIVERED => [
        self::COMPLETED,
    ],

                self::COMPLETED => [],

                self::CANCELED => [],
            },
            true
        );
    }
}
