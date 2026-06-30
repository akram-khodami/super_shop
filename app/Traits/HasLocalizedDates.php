<?php

namespace App\Traits;

use Morilog\Jalali\Jalalian;

trait HasLocalizedDates
{
    protected function localizeDate($date): ?string
    {
        if (!$date) {
            return null;
        }

        if (app()->getLocale() === 'fa') {
            return Jalalian::fromCarbon($date)
                ->format('Y/m/d H:i');
        }

        return $date->format('Y-m-d H:i');
    }
}
