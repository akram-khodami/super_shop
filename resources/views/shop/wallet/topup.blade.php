<x-app-layout>

    <div class="max-w-xl mx-auto py-10">

        <div class="bg-white rounded-xl shadow p-6">

            <h1 class="text-xl font-bold mb-6">
                {{ __('messages.top_up_wallet') }}
            </h1>

            <form
                method="POST"
                action="{{ route('wallet.topup.store') }}"
            >

                @csrf

                <label>
                    {{ __('messages.amount_currency') }}
                </label>

                <input
                    type="number"
                    name="amount"
                    min="10000"
                    step="1000"
                    class="w-full rounded-lg border-gray-300 mt-2"
                >

                <div class="mt-6">

                    <label class="block mb-3 font-medium">

                        {{ __('messages.payment_gateway') }}

                    </label>

                    @foreach($gateways as $gateway)

                        <label
                            class="flex items-center gap-3 border rounded-xl p-4 cursor-pointer"
                        >

                            <input
                                type="radio"
                                name="gateway"
                                value="{{ $gateway->value }}"
                                checked
                            >

                            <span>
                                {{ $gateway->label() }}
                            </span>

                        </label>

                    @endforeach

                </div>

                <x-primary-button class="mt-6">
                    {{ __('messages.proceed_to_payment') }}
                </x-primary-button>

            </form>

        </div>

    </div>

</x-app-layout>
