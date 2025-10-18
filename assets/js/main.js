/**
 * Main Theme Scripts
 * Главный файл скриптов темы
 * 
 * @package Theme
 */

jQuery(document).ready(function($) {
    
    //============= INITIALIZATION =============
    
    // Инициализация мобильного меню
    let mobileMenu = new MobileMenu();
    mobileMenu.init();
    
    // Инициализация модальных окон
    let modalManager = new ModalManager();
    
    // Инициализация WooCommerce AJAX (если WooCommerce активен)
    if (typeof themeWoo !== 'undefined') {
        let wooAjax = new WooCommerceAjax();
    }
    
    //============= FORM HANDLERS =============
    
    // Маска для телефонов
    $('input[type=tel]').inputmask({"mask": "+7 999 999-99-99"});
    
    // Валидатор телефона
    window.formPhoneValidator = function (input) {
        let tempInput = input.toString().replaceAll(/[^0-9]+/g, '');
        return tempInput.length > 10;
    }
    
    // Обработка файлов в формах
    $('.form input[type=file]').on('change', function(e) {
        if (e.target.files[0] !== undefined && e.target.files[0].name) {
            let fileName = '';
            [...e.target.files].forEach(e => fileName += e.name + '\n');
            $(this).parents('label').find('span.file__title').text(fileName);
        } else {
            $(this).parents('label').find('span.file__title').text('Прикрепить файл');
        }
    });
    
    //============= MOBILE MENU ENHANCEMENTS =============
    
    // Подменю в мобильном меню
    if ($(window).width() <= '996') {
        $('#mobile-mnu li.has-childs > a').on('click', function (e) {
            e.preventDefault();
            const parent = $(this).parent();
            parent.find('ul.sub-menu').slideToggle();
            parent.toggleClass('active');
        });
    }
    
    //============= CONTENT ENHANCEMENTS =============
    
    // Кнопка "Читать полностью" для отзывов
    $("body").on("click", ".text-all", function () {
        if (!$(this).hasClass("opened")) {
            $(this).closest(".reviews-text").find(".full-text").show();
            $(this).closest(".reviews-text").find(".short-text").hide();
            $(this).html("Свернуть");
            $(this).addClass("opened");
        } else {
            $(this).closest(".reviews-text").find(".full-text").hide();
            $(this).closest(".reviews-text").find(".short-text").show();
            $(this).html("Читать полностью");
            $(this).removeClass("opened");
        }
    });
    
    //============= WOOCOMMERCE ENHANCEMENTS =============
    
    // AJAX добавление в корзину
    $('body').on('click', '.ajax_add_to_cart', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const productId = $button.data('product_id');
        const quantity = $button.data('quantity') || 1;
        
        if (!productId) {
            console.error('Product ID not found');
            return;
        }
        
        // Показываем загрузку
        $button.addClass('loading').prop('disabled', true);
        
        $.ajax({
            url: themeWoo.ajaxurl,
            type: 'POST',
            data: {
                action: 'theme_add_to_cart',
                product_id: productId,
                quantity: quantity,
                nonce: themeWoo.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Обновляем счетчик корзины
                    $('.cart-count').text(response.data.cart_count);
                    
                    // Показываем уведомление
                    showNotification(response.data.message, 'success');
                    
                    // Анимация кнопки
                    $button.removeClass('loading').addClass('added');
                    setTimeout(() => {
                        $button.removeClass('added').prop('disabled', false);
                    }, 2000);
                } else {
                    showNotification(response.data?.message || 'Ошибка добавления товара', 'error');
                    $button.removeClass('loading').prop('disabled', false);
                }
            },
            error: function() {
                showNotification('Произошла ошибка', 'error');
                $button.removeClass('loading').prop('disabled', false);
            }
        });
    });
    
    //============= UTILITY FUNCTIONS =============
    
    // Функция показа уведомлений
    function showNotification(message, type = 'info') {
        const notification = $(`
            <div class="notification notification-${type}">
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        // Анимация
        setTimeout(() => notification.addClass('show'), 10);
        
        // Автоудаление
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Плавная прокрутка к якорям
    $('a[href^="#"]').not('[href="#"]').on('click', function(e) {
        const target = $(this.hash);
        
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    //============= RESPONSIVE HANDLERS =============
    
    // Обработка изменения размера окна
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Переинициализация компонентов при необходимости
            if ($(window).width() > 996) {
                // Закрываем мобильное меню на десктопе
                mobileMenu.forceClose();
            }
        }, 250);
    });
    
    //============= ACCESSIBILITY =============
    
    // Поддержка клавиатуры для модалок
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            // Закрываем модалки
            if (modalManager.isModalOpen()) {
                modalManager.closeAllModals();
            }
            
            // Закрываем мобильное меню
            if (mobileMenu.isMenuOpen()) {
                mobileMenu.closeMenu();
            }
        }
    });
    
    //============= PERFORMANCE =============
    
    // Lazy loading для изображений
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    //============= CUSTOM EVENTS =============
    
    // Событие при открытии мобильного меню
    document.addEventListener('mobileMenuOpened', function() {
        $('body').addClass('menu-open');
    });
    
    // Событие при закрытии мобильного меню
    document.addEventListener('mobileMenuClosed', function() {
        $('body').removeClass('menu-open');
    });
    
    //============= DEBUG =============
    
    // Отладочная информация (только в development)
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
        console.log('Theme JS initialized');
        console.log('Mobile Menu:', mobileMenu);
        console.log('Modal Manager:', modalManager);
    }
    
});