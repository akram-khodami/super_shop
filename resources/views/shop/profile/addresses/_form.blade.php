@php
    $address = $address ?? null;
@endphp

<div class="space-y-4">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ __('messages.address_title') }}
        </label>

        <input type="text" name="title" value="{{ old('title', $address?->title) }}"
            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="{{ __('messages.address_title_placeholder') }}">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('messages.recipient_name') }}
            </label>

            <input type="text" name="recipient_name" value="{{ old('recipient_name', $address?->recipient_name) }}"
                class="w-full rounded-lg border-gray-300"
                placeholder="{{ __('messages.recipient_name_placeholder') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('messages.mobile') }}
            </label>

            <input type="text" name="mobile" value="{{ old('mobile', $address?->mobile) }}"
                class="w-full rounded-lg border-gray-300"
                placeholder="{{ __('messages.mobile_placeholder') }}">
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('messages.province') }}
            </label>

            <input type="text" name="province" value="{{ old('province', $address?->province) }}"
                class="w-full rounded-lg border-gray-300"
                placeholder="{{ __('messages.province_placeholder') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('messages.city') }}
            </label>

            <input type="text" name="city" value="{{ old('city', $address?->city) }}"
                class="w-full rounded-lg border-gray-300"
                placeholder="{{ __('messages.city_placeholder') }}">
        </div>

    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ __('messages.full_address') }}
        </label>

        <textarea name="address" rows="4" class="w-full rounded-lg border-gray-300"
            placeholder="{{ __('messages.address_placeholder') }}">{{ old('address', $address?->address) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ __('messages.postal_code') }}
        </label>

        <input type="text" name="postal_code" value="{{ old('postal_code', $address?->postal_code) }}"
            class="w-full rounded-lg border-gray-300"
            placeholder="{{ __('messages.postal_code_placeholder') }}">
    </div>

</div>
