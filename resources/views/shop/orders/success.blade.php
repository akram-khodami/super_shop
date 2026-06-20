<x-app-layout>

    <div class="max-w-3xl mx-auto px-4 py-16">

        <div class="bg-white border rounded-3xl p-10 text-center">

            <div
                class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto"
            >
                ✓
            </div>

            <h1
                class="mt-6 text-3xl font-bold text-green-600"
            >
                سفارش با موفقیت ثبت شد
            </h1>

            <p
                class="mt-3 text-gray-500"
            >
                پرداخت شما با موفقیت انجام شد.
            </p>

            <div
                class="mt-8 border rounded-2xl p-6 text-right space-y-4"
            >

                <div class="flex justify-between">
                    <span>شماره سفارش</span>

                    <strong>
                        {{ $order->order_number }}
                    </strong>
                </div>

                <div class="flex justify-between">
                    <span>مبلغ پرداختی</span>

                    <strong>
                        {{ number_format($order->total_amount) }}
                        تومان
                    </strong>
                </div>

                <div class="flex justify-between">
                    <span>وضعیت</span>

                    <strong class="text-green-600">
                        پرداخت شده
                    </strong>
                </div>

            </div>

            <div
                class="mt-8 flex justify-center gap-4"
            >

                <a
                    href="{{ route('home') }}"
                    class="px-5 py-3 rounded-xl bg-gray-100"
                >
                    بازگشت به فروشگاه
                </a>

                <a
                    href="#"
                    class="px-5 py-3 rounded-xl bg-indigo-600 text-white"
                >
                    مشاهده سفارش
                </a>

            </div>

        </div>

    </div>

</x-app-layout>
