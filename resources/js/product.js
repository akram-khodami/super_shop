// resources/js/product.js

import {t, getCurrentLocale} from './i18n';

// Format price based on locale
function formatPrice(price) {
    const locale = getCurrentLocale();
    const formatter = new Intl.NumberFormat(locale === 'fa' ? 'fa-IR' : 'en-US', {
        style: 'currency',
        currency: 'IRR',
        maximumFractionDigits: 0,
        // For Iranian Toman, we need to divide by 10
        // If your prices are in Tomans, use this:
        // currency: 'IRR',
        // minimumFractionDigits: 0
    });

    // If prices are stored in Rials, divide by 10
    // return formatter.format(price / 10);

    // If prices are already in Tomans:
    return formatter.format(price);
}

// Change main image
window.changeMainImage = function (url) {
    document.getElementById('main-image').src = url;
};

// Variant selection
window.selectVariant = function (valueId) {
    const variants = window.variantsData;
    const variant = variants.find(v => v.value_id == valueId);

    if (!variant) return;

    // Update selected variant button
    document.querySelectorAll('.variant-btn').forEach(btn => {
        const isActive = btn.dataset.valueId == valueId;
        btn.classList.toggle('border-indigo-500', isActive);
        btn.classList.toggle('bg-indigo-50', isActive);
        btn.classList.toggle('text-indigo-700', isActive);
        btn.classList.toggle('ring-2', isActive);
        btn.classList.toggle('ring-indigo-200', isActive);
        btn.classList.toggle('border-gray-200', !isActive);
    });

    // Update price box
    const priceBox = document.getElementById('price-box');
    if (variant.sale_price && variant.sale_price < variant.price) {
        const discount = Math.round(((variant.price - variant.sale_price) / variant.price) * 100);
        priceBox.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="text-3xl font-bold text-red-600">${variant.formatted_sale_price}</span>
                <span class="text-lg text-gray-400 line-through">${variant.formatted_price}</span>
                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-lg text-sm">
                    ${discount}% ${t('discount')}
                </span>
            </div>
            <span class="text-gray-500 mr-1">${t('tomans')}</span>
        `;
    } else if (variant.price) {
        priceBox.innerHTML = `
            <span class="text-3xl font-bold">${variant.formatted_price}</span>
            <span class="text-gray-500 mr-1">${t('tomans')}</span>
        `;
    }

    // Update main image
    if (variant.image) {
        document.getElementById('main-image').src = variant.image;
    }

    // Update stock status
    const stockStatus = document.getElementById('stock-status');
    if (variant.in_stock) {
        stockStatus.innerHTML = variant.status;
        stockStatus.className = 'text-sm font-medium text-green-600';
    } else {
        stockStatus.innerHTML = variant.status;
        stockStatus.className = 'text-sm font-medium text-red-500';
    }

    // Update add to cart button
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    addToCartBtn.dataset.currentVariantId = variant.id;

    if (variant.in_stock) {
        addToCartBtn.disabled = false;
        addToCartBtn.textContent = t('add_to_cart');
        addToCartBtn.className = 'flex-1 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium';
    } else {
        addToCartBtn.disabled = true;
        addToCartBtn.textContent = t('unavailable');
        addToCartBtn.className = 'flex-1 py-3 bg-gray-200 text-gray-400 rounded-xl cursor-not-allowed font-medium';
    }

    // Update selected label
    const selectedLabel = document.getElementById('selected-variant-label');
    const selectedBtn = document.querySelector(`[data-value-id="${valueId}"]`);
    if (selectedLabel && selectedBtn) {
        selectedLabel.textContent = selectedBtn.textContent.trim();
    }
};

// Add to cart functionality
const addToCartBtn = document.getElementById('add-to-cart-btn');
if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function () {
        const variantId = this.dataset.currentVariantId;

        if (!variantId) {
            showToast(t('please_select_variant'), 'error');
            return;
        }

        // Disable button
        this.disabled = true;
        this.classList.add('opacity-50', 'cursor-not-allowed');
        const originalText = this.textContent;
        this.textContent = `⏳ ${t('adding')}`;

        fetch(`/cart/items/${variantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({quantity: 1})
        })
            .then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || t('server_error'));
                }

                return data;
            })
            .then(data => {
                showToast(data.message || `✅ ${t('add_to_cart_success')}`, 'success');

                // Update cart count if element exists
                const cartCount = document.getElementById('cart-count');
                if (cartCount && data.count !== undefined) {
                    cartCount.textContent = data.count;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || `❌ ${t('add_to_cart_error')}`, 'error');
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                this.classList.remove('opacity-50', 'cursor-not-allowed');
                this.textContent = originalText;
            });
    });
}

// Optional: Load variants from data attribute
document.addEventListener('DOMContentLoaded', function () {
    // Any initialization code here
    console.log('Product page loaded with locale:', getCurrentLocale());
});
