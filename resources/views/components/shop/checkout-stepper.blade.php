<div class="bg-white border rounded-2xl p-5 mb-6">

    <div class="flex items-center justify-center">

        {{-- Cart --}}
        <div class="flex items-center">

            <div @class([
            'w-10 h-10 rounded-full flex items-center justify-center',
            'bg-green-500 text-white' => $step > 1,
            'bg-indigo-600 text-white' => $step === 1,
            'bg-gray-200' => $step < 1,
            ])>
            1
        </div>

        <span class="mr-3">
                {{ __('messages.cart') }}
            </span>
    </div>

    <div class="w-20 h-1 bg-gray-200 mx-3"></div>

    {{-- Address --}}
    <div class="flex items-center">

        <div @class([
        'w-10 h-10 rounded-full flex items-center justify-center',
        'bg-green-500 text-white' => $step > 2,
        'bg-indigo-600 text-white' => $step === 2,
        'bg-gray-200' => $step < 2,
        ])>
        2
    </div>

    <span class="mr-3">
                {{ __('messages.address') }}
            </span>
</div>

<div class="w-20 h-1 bg-gray-200 mx-3"></div>

{{-- Payment --}}
<div class="flex items-center">

    <div @class([
    'w-10 h-10 rounded-full flex items-center justify-center',
    'bg-green-500 text-white' => $step > 3,
    'bg-indigo-600 text-white' => $step === 3,
    'bg-gray-200' => $step < 3,
    ])>
    3
</div>

<span class="mr-3">
    {{ __('messages.payment') }}
</span>
</div>

</div>

</div>
