@props([
'subtotal'
])

<div
    class="sticky top-4 rounded-xl border bg-white p-6 shadow-sm"
>

    <h2 class="mb-6 text-lg font-bold">
        خلاصه سفارش
    </h2>

    <div class="space-y-4">

        <div class="flex justify-between">
            <span>جمع کالاها</span>

            <span>
                {{ number_format($subtotal) }}
                تومان
            </span>
        </div>

        <div class="flex justify-between">
            <span>هزینه ارسال</span>

            <span>
                رایگان
            </span>
        </div>

        <hr>

        <div
            class="flex justify-between text-lg font-bold"
        >
            <span>مبلغ قابل پرداخت</span>

            <span>
                {{ number_format($subtotal) }}
                تومان
            </span>
        </div>

    </div>

    <a
        href="#"
        class="mt-6 block rounded-xl bg-indigo-600 py-3 text-center font-medium text-white hover:bg-indigo-700"
    >
        ادامه فرایند خرید
    </a>

</div>
