// resources/js/i18n.js

const translations = {
    en: {
        discount: 'Discount',
        tomans: 'Tomans',
        add_to_cart: 'Add to Cart',
        unavailable: 'Unavailable',
        please_select_variant: 'Please select a variant',
        adding: 'Adding...',
        add_to_cart_success: 'Product added to cart successfully',
        add_to_cart_error: 'Error adding to cart',
        server_error: 'Server error',
        in_stock: 'In Stock',
        out_of_stock: 'Out of Stock',
    },
    fa: {
        discount: 'تخفیف',
        tomans: 'تومان',
        add_to_cart: 'افزودن به سبد خرید',
        unavailable: 'ناموجود',
        please_select_variant: 'لطفاً یک تنوع را انتخاب کنید',
        adding: 'در حال افزودن...',
        add_to_cart_success: 'محصول به سبد خرید اضافه شد',
        add_to_cart_error: 'خطا در افزودن به سبد خرید',
        server_error: 'خطای سرور',
        in_stock: 'موجود در انبار',
        out_of_stock: 'ناموجود'
    }
};

// Get current locale from HTML or default to 'en'
const currentLocale = document.documentElement.lang || 'en';

export function t(key) {
    return translations[currentLocale]?.[key] || translations['en'][key] || key;
}

export function getCurrentLocale() {
    return currentLocale;
}

export function setLocale(locale) {
    document.documentElement.lang = locale;
    // You can reload translations or re-render components here
}
