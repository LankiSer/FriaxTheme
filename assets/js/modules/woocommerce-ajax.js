/**
 * WooCommerce AJAX Module
 * AJAX функционал для WooCommerce (корзина, добавление товаров)
 * 
 * @package Theme
 */

class WooCommerceAjax {
    constructor() {
        this.init();
    }
    
    init() {
        this.bindAddToCart();
        this.bindCartUpdate();
        this.bindQuantityControls();
        this.setupCartCount();
    }
    
    // Add to Cart AJAX
    bindAddToCart() {
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.ajax_add_to_cart');
            if (button) {
                e.preventDefault();
                this.addToCart(button);
            }
        });
    }
    
    addToCart(button) {
        const productId = button.dataset.product_id || button.getAttribute('data-product_id');
        const quantity = button.dataset.quantity || 1;
        
        if (!productId) {
            this.showError('ID товара не найден');
            return;
        }
        
        // Показываем загрузку
        this.setButtonLoading(button, true);
        
        // AJAX запрос
        fetch(themeWoo.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'theme_add_to_cart',
                product_id: productId,
                quantity: quantity,
                nonce: themeWoo.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showSuccess(data.data.message);
                this.updateCartCount(data.data.cart_count);
                this.updateCartHash(data.data.cart_hash);
            } else {
                this.showError(data.data?.message || 'Ошибка добавления товара');
            }
        })
        .catch(error => {
            console.error('AJAX Error:', error);
            this.showError('Произошла ошибка при добавлении товара');
        })
        .finally(() => {
            this.setButtonLoading(button, false);
        });
    }
    
    // Cart Update
    bindCartUpdate() {
        // Обновление при изменении количества
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('qty')) {
                this.updateCartQuantity(e.target);
            }
        });
        
        // Удаление из корзины
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove')) {
                e.preventDefault();
                this.removeFromCart(e.target);
            }
        });
    }
    
    updateCartQuantity(input) {
        const cartItemKey = input.dataset.cart_item_key;
        const quantity = input.value;
        
        if (!cartItemKey) return;
        
        fetch(themeWoo.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'woocommerce_update_cart_item',
                cart_item_key: cartItemKey,
                quantity: quantity,
                nonce: themeWoo.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.data.cart_count);
                this.updateCartTotal(data.data.cart_total);
            }
        })
        .catch(error => {
            console.error('Cart Update Error:', error);
        });
    }
    
    removeFromCart(button) {
        const cartItemKey = button.dataset.cart_item_key;
        
        if (!cartItemKey) return;
        
        fetch(themeWoo.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'woocommerce_remove_cart_item',
                cart_item_key: cartItemKey,
                nonce: themeWoo.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.data.cart_count);
                // Удаляем элемент из DOM
                button.closest('.cart-item')?.remove();
            }
        })
        .catch(error => {
            console.error('Remove from Cart Error:', error);
        });
    }
    
    // Quantity Controls
    bindQuantityControls() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('qty-btn')) {
                e.preventDefault();
                this.handleQuantityChange(e.target);
            }
        });
    }
    
    handleQuantityChange(button) {
        const input = button.parentElement.querySelector('.qty');
        if (!input) return;
        
        const currentValue = parseInt(input.value) || 0;
        const min = parseInt(input.getAttribute('min')) || 0;
        const max = parseInt(input.getAttribute('max')) || 999;
        
        let newValue = currentValue;
        
        if (button.classList.contains('qty-plus')) {
            newValue = Math.min(currentValue + 1, max);
        } else if (button.classList.contains('qty-minus')) {
            newValue = Math.max(currentValue - 1, min);
        }
        
        if (newValue !== currentValue) {
            input.value = newValue;
            input.dispatchEvent(new Event('change'));
        }
    }
    
    // Cart Count
    setupCartCount() {
        // Обновляем счетчик при загрузке страницы
        this.updateCartCount();
        
        // Обновляем при событиях WooCommerce
        document.addEventListener('added_to_cart', () => {
            this.updateCartCount();
        });
        
        document.addEventListener('removed_from_cart', () => {
            this.updateCartCount();
        });
    }
    
    updateCartCount(count = null) {
        if (count === null) {
            // Получаем актуальное количество через AJAX
            fetch(themeWoo.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'theme_get_cart_count',
                    nonce: themeWoo.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.setCartCount(data.data.cart_count);
                }
            })
            .catch(error => {
                console.error('Cart Count Error:', error);
            });
        } else {
            this.setCartCount(count);
        }
    }
    
    setCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count, .cart-counter, .woocommerce-cart-count');
        cartCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'block' : 'none';
        });
    }
    
    updateCartTotal(total) {
        const totalElements = document.querySelectorAll('.cart-total, .woocommerce-cart-total');
        totalElements.forEach(element => {
            element.textContent = total;
        });
    }
    
    updateCartHash(hash) {
        // Обновляем hash корзины для кэширования
        if (hash) {
            document.body.setAttribute('data-cart-hash', hash);
        }
    }
    
    // UI Helpers
    setButtonLoading(button, loading) {
        if (loading) {
            button.classList.add('loading');
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = 'Добавление...';
        } else {
            button.classList.remove('loading');
            button.disabled = false;
            button.textContent = button.dataset.originalText || 'В корзину';
        }
    }
    
    showSuccess(message) {
        this.showNotification(message, 'success');
    }
    
    showError(message) {
        this.showNotification(message, 'error');
    }
    
    showNotification(message, type = 'info') {
        // Создаем уведомление
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Добавляем стили
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '15px 20px',
            background: type === 'success' ? '#28a745' : '#dc3545',
            color: 'white',
            borderRadius: '4px',
            zIndex: '9999',
            transform: 'translateX(100px)',
            opacity: '0',
            transition: 'all 0.3s ease'
        });
        
        document.body.appendChild(notification);
        
        // Анимация появления
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        });
        
        // Автоудаление
        setTimeout(() => {
            notification.style.transform = 'translateX(100px)';
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
}

// Export for global use
window.WooCommerceAjax = WooCommerceAjax;
