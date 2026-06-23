<x-app-layout>

    <div class="max-w-6xl mx-auto py-10">

        <div
            class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-xl p-8 mb-8"
        >

            <h1 class="text-xl font-bold">
                {{ __('messages.wallet') }}
            </h1>

            <div class="text-4xl font-bold mt-4">

                {{ number_format($wallet->balance) }}
                {{ __('messages.currency') }}

            </div>

            <a href="{{ route('wallet.topup.create') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-violet-700 transition-all duration-300 transform hover:scale-[1.02]"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.increase_balance') }}
            </a>

        </div>

        <div class="bg-white rounded-xl shadow">

            <div class="p-6 border-b">

                <h2 class="font-bold">
                    {{ __('messages.transactions') }}
                </h2>

            </div>

            <table class="w-full">

                <thead>

                <tr>

                    <th class="p-4 text-right">
                        {{ __('messages.type') }}
                    </th>

                    <th class="p-4 text-right">
                        {{ __('messages.amount') }}
                    </th>

                    <th class="p-4 text-right">
                        {{ __('messages.date') }}
                    </th>

                </tr>

                </thead>

                <tbody>

                @foreach($wallet->transactions as $transaction)

                    <tr class="border-t">

                        <td class="p-4">
                            {{ $transaction->type_label }}
                        </td>

                        <td class="p-4">
                            {{ number_format($transaction->amount) }}
                            {{ __('messages.currency') }}
                        </td>

                        <td class="p-4">
                            {{ $transaction->created_at }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>
