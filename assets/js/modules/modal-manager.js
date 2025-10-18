/**
 * Modal Manager Module
 * Управление модальными окнами с интеграцией Fancybox
 * 
 * @package Theme
 */

class ModalManager {
    constructor() {
        this.activeModals = new Set();
        this.init();
    }
    
    init() {
        this.bindFancyboxModals();
        this.bindCustomModals();
        this.setupFormHandlers();
        this.setupMobileMenuIntegration();
    }
    
    // Fancybox Integration
    bindFancyboxModals() {
        if (typeof Fancybox === 'undefined') {
            console.warn('Fancybox не загружен');
            return;
        }
        
        // Обычные изображения
        Fancybox.bind("[data-fancybox]");
        
        // Модальные окна
        Fancybox.bind('[data-modal]', {
            dragToClose: false,
            on: {
                init: (fancybox) => {
                    this.handleModalInit(fancybox);
                },
                destroy: (fancybox) => {
                    this.handleModalDestroy(fancybox);
                }
            }
        });
        
        // Специальные модалки для услуг/товаров
        Fancybox.bind('[data-modal="service"]', {
            dragToClose: false,
            on: {
                init: (fancybox) => {
                    this.handleServiceModalInit(fancybox);
                },
                destroy: (fancybox) => {
                    this.handleServiceModalDestroy(fancybox);
                }
            }
        });
    }
    
    // Custom Modal Support
    bindCustomModals() {
        // Обработка кликов по триггерам модалок
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-trigger]');
            if (trigger) {
                e.preventDefault();
                const modalId = trigger.dataset.modalTrigger;
                this.openCustomModal(modalId);
            }
        });
        
        // Закрытие модалок по клику на overlay
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.closeCustomModal(e.target.closest('.modal'));
            }
        });
        
        // Закрытие по кнопке
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-close')) {
                this.closeCustomModal(e.target.closest('.modal'));
            }
        });
    }
    
    // Form Handlers
    setupFormHandlers() {
        // Успешная отправка формы
        document.addEventListener('ajaxformsent', (e) => {
            this.triggerStatusModal('#modal-success');
        });
        
        // Ошибка отправки
        document.addEventListener('ajaxformerror', (e) => {
            this.triggerStatusModal('#modal-error');
        });
    }
    
    // Mobile Menu Integration
    setupMobileMenuIntegration() {
        // Закрываем модалки при открытии мобильного меню
        document.addEventListener('mobileMenuOpened', () => {
            this.closeAllModals();
        });
    }
    
    // Modal Handlers
    handleModalInit(fancybox) {
        const modalId = fancybox.userSlides[0].src;
        const modalSuccess = document.getElementById('modal-success');
        
        if (modalId === '#modal-review' && modalSuccess) {
            modalSuccess.querySelector('.title').textContent = 'Отзыв успешно отправлен';
            modalSuccess.querySelector('.subtitle').textContent = 'Отзыв пройдет модерацию и появится на сайте';
        } else if (modalSuccess) {
            modalSuccess.querySelector('.title').textContent = 'Заявка успешно отправлена';
            modalSuccess.querySelector('.subtitle').textContent = 'Скоро мы свяжемся с Вами и уточним детали заказа';
        }
    }
    
    handleModalDestroy(fancybox) {
        // Cleanup при закрытии модалки
    }
    
    handleServiceModalInit(fancybox) {
        const el = fancybox.userSlides[0].triggerEl;
        const modal = document.querySelector(fancybox.userSlides[0].src);
        const title = el.getAttribute('data-title');
        
        if (title && modal) {
            const titleInput = modal.querySelector('[name="service-title"]');
            if (titleInput) {
                titleInput.value = title;
            }
        }
    }
    
    handleServiceModalDestroy(fancybox) {
        const modal = document.querySelector(fancybox.userSlides[0].src);
        if (modal) {
            const titleInput = modal.querySelector('[name="service-title"]');
            if (titleInput) {
                const defaultTitle = titleInput.getAttribute('data-title') || 'Оставить заявку';
                titleInput.value = defaultTitle;
            }
        }
    }
    
    // Status Modals
    triggerStatusModal(modalId) {
        // Закрываем все открытые модалки
        this.closeAllModals();
        
        // Показываем статус модалку
        if (typeof Fancybox !== 'undefined') {
            Fancybox.show([{ 
                src: modalId,
                type: "inline"
            }], {
                dragToClose: false,
                on: {
                    close: () => {
                        document.dispatchEvent(new CustomEvent('statusModalClosed'));
                    }
                }
            });
        }
        
        // Автозакрытие через 2.5 секунды
        const timerId = setTimeout(() => {
            this.closeAllModals();
        }, 2500);
        
        // Очистка таймера при ручном закрытии
        document.addEventListener('statusModalClosed', () => {
            clearTimeout(timerId);
        }, { once: true });
    }
    
    // Custom Modal Methods
    openCustomModal(modalId) {
        const modal = document.querySelector(modalId);
        if (modal) {
            modal.classList.add('active', 'show');
            document.body.classList.add('modal-open');
            this.activeModals.add(modal);
            
            // Блокируем скролл
            document.body.style.overflow = 'hidden';
        }
    }
    
    closeCustomModal(modal) {
        if (modal) {
            modal.classList.remove('active', 'show');
            this.activeModals.delete(modal);
            
            if (this.activeModals.size === 0) {
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }
        }
    }
    
    closeAllModals() {
        // Закрываем Fancybox модалки
        if (typeof Fancybox !== 'undefined') {
            Fancybox.close();
        }
        
        // Закрываем кастомные модалки
        this.activeModals.forEach(modal => {
            this.closeCustomModal(modal);
        });
        
        this.activeModals.clear();
    }
    
    // Public API
    openModal(modalId) {
        if (modalId.startsWith('#')) {
            this.openCustomModal(modalId);
        } else {
            // Fancybox modal
            if (typeof Fancybox !== 'undefined') {
                Fancybox.show([{ src: modalId }]);
            }
        }
    }
    
    closeModal(modalId) {
        if (modalId.startsWith('#')) {
            const modal = document.querySelector(modalId);
            this.closeCustomModal(modal);
        } else {
            if (typeof Fancybox !== 'undefined') {
                Fancybox.close();
            }
        }
    }
    
    isModalOpen() {
        return this.activeModals.size > 0 || 
               (typeof Fancybox !== 'undefined' && Fancybox.getInstance());
    }
}

// Export for global use
window.ModalManager = ModalManager;
