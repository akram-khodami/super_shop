<x-app-layout>

    <x-slot:title>
        {{ __('messages.addresses') }}
    </x-slot:title>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-address')">
                {{ __('messages.add_new_address') }}
            </x-primary-button>

            <x-modal name="create-address" focusable>

                <form method="POST" action="{{ route('profile.addresses.store') }}" class="p-6">
                    @csrf

                    @include('shop.profile.addresses._form')

                    <div class="mt-6 flex justify-end">
                        <x-primary-button>
                            {{ __('messages.save_address') }}
                        </x-primary-button>
                    </div>

                </form>

            </x-modal>

            @forelse ($addresses as $address)
                <div class="bg-white border rounded-xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <h3 class="font-semibold text-lg">
                            {{ $address->title }}
                        </h3>

                        @if ($address->is_default)
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                {{ __('messages.default') }}
                            </span>
                        @endif

                    </div>

                    <div class="mt-4 space-y-2 text-sm text-gray-600">

                        <p>
                            <strong>{{ __('messages.recipient') }}:</strong>
                            {{ $address->recipient_name }}
                        </p>

                        <p>
                            <strong>{{ __('messages.mobile') }}:</strong>
                            {{ $address->mobile }}
                        </p>

                        <p>
                            <strong>{{ __('messages.location') }}:</strong>
                            {{ $address->province }}
                            -
                            {{ $address->city }}
                        </p>

                        <p>
                            {{ $address->address }}
                        </p>

                        @if ($address->postal_code)
                            <p>
                                <strong>{{ __('messages.postal_code') }}:</strong>
                                {{ $address->postal_code }}
                            </p>
                        @endif

                    </div>

                    <div class="mt-5 flex gap-2">

                        <button x-data=""
                            x-on:click.prevent="$dispatch('open-modal','edit-address-{{ $address->id }}')"
                            class="text-sm text-indigo-600 hover:text-indigo-800">
                            {{ __('messages.edit') }}
                        </button>

                        <form method="POST" action="{{ route('profile.addresses.default', $address) }}">
                            @csrf
                            @method('PATCH')

                            <button class="text-sm text-green-600 hover:text-green-800">
                                {{ __('messages.set_as_default') }}
                            </button>
                        </form>

                    </div>

                </div>

                <x-modal name="edit-address-{{ $address->id }}" focusable>

                    <form method="POST" action="{{ route('profile.addresses.update', $address) }}" class="p-6">
                        @csrf
                        @method('PUT')

                        @include('shop.profile.addresses._form', ['address' => $address])

                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                {{ __('messages.update_address') }}
                            </x-primary-button>
                        </div>

                    </form>

                </x-modal>

            @empty
                <div class="bg-white border rounded-xl p-10 text-center text-gray-500">
                    {{ __('messages.no_addresses') }}
                </div>
            @endforelse

        </div>
    </div>

</x-app-layout>
