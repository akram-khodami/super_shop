<?php

namespace App\Services;

use App\Models\UserAddress;

class AddressService
{
    public function setDefault(UserAddress $address): void
    {
        UserAddress::query()
            ->where(
                'user_id',
                $address->user_id
            )
            ->update([
                'is_default' => false
            ]);

        $address->update([
            'is_default' => true
        ]);
    }
}
