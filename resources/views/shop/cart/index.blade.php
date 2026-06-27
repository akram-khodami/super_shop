<x-app-layout>

    <x-slot:title>
        {{ __('messages.cart') }}
    </x-slot:title>

    <div class="mx-auto max-w-7xl px-4 py-10">

        <div class="mb-8">

            <h1 class="text-3xl font-bold">
                {{ __('messages.shopping_cart') }}
            </h1>

            <p class="mt-2 text-gray-500">
                {{ __('messages.your_selected_products') }}
            </p>

        </div>

        @if (empty($cart['items']))

            <x-shop.cart.empty />
        @else
            <div class="grid gap-8 lg:grid-cols-3">

                <div class="space-y-4 lg:col-span-2">

                    @foreach ($cart['items'] as $item)
                        <x-shop.cart.item :item="$item" />
                    @endforeach

                </div>

                <div>

                    <x-shop.cart.summary :subtotal="$subtotal" />

                </div>

            </div>

        @endif

    </div>

    @push('scripts')
        <script>
            // ========== Increase and decrease quantity ==========
            document.querySelectorAll('.cart-qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.dataset.action;
                    const variantId = this.dataset.variantId;
                    const quantitySpan = document.querySelector(
                        `.cart-qty-text[data-variant-id="${variantId}"]`);
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
                            body: JSON.stringify({
                                quantity: 1,
                                action: action
                            })
                        })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) throw new Error(data.message || 'Server error');
                            return data;
                        })
                        .then(data => {
                            showToast(data.message || '✅ Updated successfully', 'success');

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
                            showToast(error.message || '❌ Error updating', 'error');
                        })
                        .finally(() => {
                            siblingBtns.forEach(b => {
                                b.disabled = false;
                                b.classList.remove('opacity-50', 'pointer-events-none');
                            });
                        });
                });
            });

            // ========== Remove item ==========
            document.querySelectorAll('.cart-remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const variantId = this.dataset.variantId;
                    const row = this.closest('.cart-item-row');

                    if (!confirm('{{ __('messages.confirm_remove_item') }}')) return;

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
                            if (!response.ok) throw new Error(data.message || 'Server error');
                            return data;
                        })
                        .then(data => {
                            showToast(data.message || '🗑️ Item removed', 'success');
                            row.remove();
                            checkEmptyCart();
                        })
                        .catch(error => {
                            showToast(error.message || '❌ Error removing item', 'error');
                            this.textContent = '{{ __('messages.remove') }}';
                        })
                        .finally(() => {
                            siblingBtns.forEach(b => {
                                b.disabled = false;
                                b.classList.remove('opacity-50', 'pointer-events-none');
                            });
                        });
                });
            });

            // Check if cart is empty
            function checkEmptyCart() {
                const remainingItems = document.querySelectorAll('.cart-item-row');
                if (remainingItems.length === 0) {
                    location.reload(); // Reload to show empty state
                }
            }
        </script>
    @endpush
</x-app-layout>
