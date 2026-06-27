<x-app-layout>

    <x-slot:title>
        {{ __('messages.profile') }}
    </x-slot:title>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.profile_information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Quick Actions Card --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('profile.addresses.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('messages.addresses') }}
                    </a>

                    <a href="{{ route('orders.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __('messages.my_orders') }}
                    </a>

                    <a href="{{ route('wallet.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __('messages.wallet') }}
                    </a>
                </div>
            </div>

            {{-- Update Profile Information --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="max-w-xl">
                    @include('shop.profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="max-w-xl">
                    @include('shop.profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="max-w-xl">
                    @include('shop.profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
