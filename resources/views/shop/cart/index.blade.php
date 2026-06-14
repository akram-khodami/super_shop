<x-app-layout>

    <div class="mx-auto max-w-7xl px-4 py-10">

        <div class="mb-8">

            <h1 class="text-3xl font-bold">
                سبد خرید
            </h1>

            <p class="mt-2 text-gray-500">
                محصولات انتخاب شده شما
            </p>

        </div>

        @if(empty($cart['items']))

            <x-shop.cart.empty/>

        @else

            <div
                class="grid gap-8 lg:grid-cols-3"
            >

                <div
                    class="space-y-4 lg:col-span-2"
                >

                    @foreach($cart['items'] as $item)

                        <x-shop.cart.item
                            :item="$item"
                        />

                    @endforeach

                </div>

                <div>

                    <x-shop.cart.summary
                        :subtotal="$cart['subtotal']"
                    />

                </div>

            </div>

        @endif

    </div>

    @push('scripts')
        <script>
            // ========== افزایش و کاهش تعداد ==========
            document.querySelectorAll('.cart-qty-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const action = this.dataset.action;
                    const variantId = this.dataset.variantId;
                    const quantitySpan = document.querySelector(`.cart-qty-text[data-variant-id="${variantId}"]`);
                    const row = this.closest('.cart-item-row');

                    if (!variantId) return;

                    const siblingBtns = row.querySelectorAll('.cart-qty-btn, .cart-remove-btn');
                    siblingBtns.forEach(b => {
                        b.disabled = true;
                        b.classList.add('opacity-50', 'pointer-events-none');
                    });

                    fetch(`/cart/items/${variantId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ quantity: 1, action: action })
                    })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) throw new Error(data.message || 'خطای سرور');
                            return data;
                        })
                        .then(data => {
                            showToast(data.message || '✅ بروزرسانی شد', 'success');

                            let currentQty = parseInt(quantitySpan.textContent.trim());
                            if (action === 'increase') {
                                quantitySpan.textContent = currentQty + 1;
                            } else if (action === 'decrease') {
                                const newQty = currentQty - 1;
                                if (newQty <= 0) {
                                    row.remove();
                                    checkEmptyCart();
                                } else {
                                    quantitySpan.textContent = newQty;
                                }
                            }
                        })
                        .catch(error => {
                            showToast(error.message || '❌ خطا در بروزرسانی', 'error');
                        })
                        .finally(() => {
                            siblingBtns.forEach(b => {
                                b.disabled = false;
                                b.classList.remove('opacity-50', 'pointer-events-none');
                            });
                        });
                });
            });

            // ========== حذف آیتم ==========
            document.querySelectorAll('.cart-remove-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const variantId = this.dataset.variantId;
                    const row = this.closest('.cart-item-row');

                    if (!confirm('آیا از حذف این محصول اطمینان دارید؟')) return;

                    const siblingBtns = row.querySelectorAll('.cart-qty-btn, .cart-remove-btn');
                    siblingBtns.forEach(b => {
                        b.disabled = true;
                        b.classList.add('opacity-50', 'pointer-events-none');
                    });

                    this.textContent = '⏳';

                    fetch(`/cart/items/${variantId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) throw new Error(data.message || 'خطای سرور');
                            return data;
                        })
                        .then(data => {
                            showToast(data.message || '🗑️ محصول حذف شد', 'success');
                            row.remove();
                            checkEmptyCart();
                        })
                        .catch(error => {
                            showToast(error.message || '❌ خطا در حذف محصول', 'error');
                            this.textContent = 'حذف';
                        })
                        .finally(() => {
                            siblingBtns.forEach(b => {
                                b.disabled = false;
                                b.classList.remove('opacity-50', 'pointer-events-none');
                            });
                        });
                });
            });

            // بررسی خالی شدن سبد
            function checkEmptyCart() {
                const remainingItems = document.querySelectorAll('.cart-item-row');
                if (remainingItems.length === 0) {
                    location.reload(); // رفرش کن تا empty state نشون داده بشه
                }
            }
        </script>
    @endpush
</x-app-layout>

