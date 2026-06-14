import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/**
 * نمایش پیغام Toast در گوشه صفحه
 * @param {string} message - متن پیغام
 * @param {string} type - نوع: success | error | warning
 */
window.showToast = function (message, type = 'success') {
    // پاک کردن Toast قبلی
    const existingToast = document.getElementById('toast-container');
    if (existingToast) {
        existingToast.remove();
    }

    // آیکون‌ها
    const icons = {
        success: `<svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>`,
        error: `<svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>`,
        warning: `<svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>`
    };

    // رنگ‌های پس‌زمینه
    const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-amber-50 border-amber-200 text-amber-800'
    };

    const toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.className = 'fixed bottom-6 left-6 z-50';
    toastContainer.innerHTML = `
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border animate-slide-up ${colors[type] || colors.success}">
            ${icons[type] || icons.success}
            <span class="text-sm font-medium">${message}</span>
            <button onclick="this.closest('#toast-container').remove()" 
                    class="ml-2 text-current opacity-50 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(toastContainer);

    // حذف خودکار بعد از ۴ ثانیه
    setTimeout(() => {
        const toast = document.getElementById('toast-container');
        if (toast) {
            toast.remove();
        }
    }, 4000);
};
