<?php

namespace App\Traits;

trait HasLabelTranslation
{
    public function label(): string
    {
        $translationKey = strtolower(class_basename($this));
        return __("messages.{$translationKey}.{$this->value}");
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
