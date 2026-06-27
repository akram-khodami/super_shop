<?php

namespace App\Http\Controllers\Shop;

use App\Services\AddressService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreUserAddressRequest;
use App\Http\Requests\Shop\UpdateUserAddressRequest;
use App\Models\UserAddress;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = auth()
            ->user()
            ->addresses()
            ->latest()
            ->get();

        return view(
            'shop.profile.addresses.index',
            compact('addresses')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreUserAddressRequest $request
    )
    {
        auth()
            ->user()
            ->addresses()
            ->create(
                $request->validated()
            );

        return redirect()
            ->route('profile.addresses.index')
            ->with(
                'success',
                'آدرس ثبت شد.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUserAddressRequest $request,
        UserAddress $address
    )
    {
        abort_if(
            $address->user_id !== auth()->id(),
            403
        );

        $address->update(
            $request->validated()
        );

        return back()
            ->with(
                'success',
                'آدرس ویرایش شد.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddress $address)
    {
        abort_if(
            $address->user_id !== auth()->id(),
            403
        );

        $address->delete();

        return back()
            ->with(
                'success',
                'آدرس حذف شد.'
            );
    }

    public function setDefault(
        UserAddress $address,
        AddressService $service
    )
    {
        abort_if(
            $address->user_id !== auth()->id(),
            403
        );

        $service->setDefault(
            $address
        );

        return back();
    }
}
