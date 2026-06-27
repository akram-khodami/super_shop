<x-app-layout>

    <x-slot:title>
        {{ __('messages.wallet') }}
    </x-slot:title>

    <div class="max-w-6xl mx-auto py-10 px-4">

        {{-- Header with Wallet Balance --}}
        <div
            class="bg-gradient-to-br from-emerald-500 via-green-600 to-teal-700 text-white rounded-2xl p-8 mb-8 shadow-xl relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/3">
            </div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4">
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <p class="text-emerald-100 text-sm font-medium tracking-wide">
                            {{ __('messages.wallet_balance') }}
                        </p>
                        <div class="text-5xl font-bold mt-2 tracking-tight">
                            {{ number_format($wallet->balance) }}
                            <span class="text-2xl font-light text-emerald-200">
                                {{ __('messages.currency') }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('wallet.topup.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-semibold rounded-xl border border-white/30 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('messages.increase_balance') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div
                class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <p class="text-sm text-gray-500">{{ __('messages.total_transactions') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $transactionsCount }}</p>
            </div>
            <div
                class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <p class="text-sm text-gray-500">{{ __('messages.last_transaction') }}</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $lastTransactionTime ?? '—' }}
                </p>
            </div>
            <div
                class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <p class="text-sm text-gray-500">{{ __('messages.total_deposits') }}</p>
                <p class="text-2xl font-bold text-emerald-600">
                    {{ number_format($transactionsSumAmount) }}
                    <span class="text-sm font-normal text-gray-400">{{ __('messages.currency') }}</span>
                </p>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ __('messages.transactions') }}
                    </h2>
                    <span class="text-sm text-gray-400">
                        {{ $transactionsCount }} {{ __('messages.record') }}
                    </span>
                </div>
            </div>

            @if ($transactionsCount > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="bg-gray-50/80 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4">{{ __('messages.type') }}</th>
                                <th class="px-6 py-4">{{ __('messages.amount') }}</th>
                                <th class="px-6 py-4">{{ __('messages.description') }}</th>
                                <th class="px-6 py-4">{{ __('messages.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($wallet->transactions as $transaction)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        @if ($transaction->type === 'deposit')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                {{ $transaction->type_label }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4" />
                                                </svg>
                                                {{ $transaction->type_label }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium">
                                        <span
                                            class="{{ $transaction->type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $transaction->type === 'deposit' ? '+' : '-' }}
                                            {{ number_format($transaction->amount) }}
                                        </span>
                                        <span class="text-xs text-gray-400">{{ __('messages.currency') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">
                                        {{ $transaction->description ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span>{{ $transaction->created_at->format('Y/m/d') }}</span>
                                            <span
                                                class="text-xs text-gray-400">{{ $transaction->created_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-400 text-sm">{{ __('messages.no_transactions') }}</p>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
