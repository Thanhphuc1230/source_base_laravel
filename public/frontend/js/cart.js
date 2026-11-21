// Shows beautiful Tailwind notifications dynamically
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `transform translate-x-10 opacity-0 transition-all duration-300 ease-out pointer-events-auto p-4 rounded-xl shadow-lg border flex items-start space-x-3 bg-white`;
    
    let iconHtml = '';
    let borderClass = '';
    
    if (type === 'success') {
        iconHtml = `<div class="bg-emerald-100 text-emerald-800 p-1.5 rounded-full"><i class="fa-solid fa-circle-check"></i></div>`;
        borderClass = 'border-emerald-100';
    } else if (type === 'error') {
        iconHtml = `<div class="bg-red-100 text-red-800 p-1.5 rounded-full"><i class="fa-solid fa-circle-exclamation"></i></div>`;
        borderClass = 'border-red-100';
    } else {
        iconHtml = `<div class="bg-blue-100 text-blue-800 p-1.5 rounded-full"><i class="fa-solid fa-circle-info"></i></div>`;
        borderClass = 'border-blue-100';
    }
    
    toast.classList.add(borderClass);
    toast.innerHTML = `
        ${iconHtml}
        <div class="flex-grow">
            <p class="text-xs font-bold text-emerald-950">${type === 'success' ? 'Thành công!' : 'Thông báo!'}</p>
            <p class="text-3xs text-gray-500 mt-0.5 leading-snug">${message}</p>
        </div>
        <button class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.remove()">
            <i class="fa-solid fa-xmark text-2xs"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Slide in animation
    setTimeout(() => {
        toast.classList.remove('translate-x-10', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-10');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 4000);
}

// Helper to update the cart badge UI
function updateCartBadgeUI(count, animate = false) {
    const badge = document.getElementById('cart-badge-count');
    if (!badge) return;

    const num = parseInt(count, 10) || 0;
    badge.innerText = num;

    if (num > 0) {
        badge.classList.remove('scale-0');
        badge.classList.add('scale-100');
    } else {
        badge.classList.remove('scale-100');
        badge.classList.add('scale-0');
    }

    if (animate) {
        badge.classList.add('animate-bounce');
        setTimeout(() => {
            badge.classList.remove('animate-bounce');
        }, 1000);
    }
}

// Client-side synchronization of cart count to bypass static Edge Cache
function syncCartBadge() {
    // 1. Instant sync from local session cache
    const cachedCount = sessionStorage.getItem('cart_total_items');
    if (cachedCount !== null) {
        updateCartBadgeUI(cachedCount);
    }

    // 2. Refresh from server (hits /cart/count which bypasses Cloudflare cache)
    if (window.cartCountUrl) {
        fetch(window.cartCountUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) return null;
            return response.json();
        })
        .then(data => {
            if (data && typeof data.totalItems !== 'undefined') {
                sessionStorage.setItem('cart_total_items', data.totalItems);
                updateCartBadgeUI(data.totalItems);
            }
        })
        .catch(() => {
            // Silently ignore network failures on background badge sync
        });
    }
}

// Initialize on DOM ready and BFCache pageshow
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', syncCartBadge);
} else {
    syncCartBadge();
}
window.addEventListener('pageshow', syncCartBadge);
window.addEventListener('cart-updated', (event) => {
    if (event.detail && typeof event.detail.totalItems !== 'undefined') {
        sessionStorage.setItem('cart_total_items', event.detail.totalItems);
        updateCartBadgeUI(event.detail.totalItems, true);
    }
});
function addToCartAjax(uuid, buttonElement = null, quantity = 1, variantId = null) {
    let originalHtml = '';
    if (buttonElement) {
        // Keep backup and show spinner
        originalHtml = buttonElement.innerHTML;
        buttonElement.innerHTML = `<i class="fa-solid fa-circle-notch animate-spin text-sm"></i>`;
        buttonElement.disabled = true;
    }

    const vId = variantId || window.selectedVariantId || null;
    const qty = quantity || 1;

    // Pull add to cart base URL dynamically
    const baseUrl = window.addToCartUrl || '/add-to-cart';
    let url = `${baseUrl}/${uuid}/${qty}`;
    if (vId) {
        url += `?variant_id=${vId}`;
    }

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Mạng gặp sự cố. Vui lòng thử lại.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update cart badge count and sync session
            sessionStorage.setItem('cart_total_items', data.totalItems);
            updateCartBadgeUI(data.totalItems, true);
            
            showToast(data.message || 'Đã thêm sản phẩm vào giỏ hàng thành công!', 'success');
            
            // Dispatch custom event to let other views know cart was updated
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
        } else {
            showToast(data.message || 'Không thể thêm sản phẩm vào giỏ hàng.', 'error');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showToast(error.message || 'Có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
    })
    .finally(() => {
        if (buttonElement) {
            buttonElement.innerHTML = originalHtml;
            buttonElement.disabled = false;
        }
    });
}
