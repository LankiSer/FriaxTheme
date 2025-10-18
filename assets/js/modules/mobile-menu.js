/**
 * Mobile Menu Module
 * Управление мобильным меню с поддержкой свайпов и модалок
 * 
 * @package Theme
 */

class MobileMenu {
    menu;
    burger;
    closeToggler;
    touchStats;
    isOpen = false;
    
    constructor(menu) {
        this.menu = document.getElementById('mobile-mnu');
        this.burger = document.querySelector('.burger.open_menu');
        this.closeToggler = document.querySelector('#mobile-mnu #close-mnu');
        this.touchStats = {
            'startX': 0,
            'startY': 0,
            'endX': 0,
            'endY': 0,
        };
        
        // Bind methods
        this.init = this.init.bind(this);
        this.destroy = this.destroy.bind(this);
        this.setOpenMenuHandler = this.setOpenMenuHandler.bind(this);
        this.setCloseMenuHandler = this.setCloseMenuHandler.bind(this);
        this.setSwipeMenuHandler = this.setSwipeMenuHandler.bind(this);
        this.removeSwipeMenuHandler = this.removeSwipeMenuHandler.bind(this);
        this.maybeCloseMenu = this.maybeCloseMenu.bind(this);
        this.toggleMenu = this.toggleMenu.bind(this);
        this.openMenu = this.openMenu.bind(this);
        this.closeMenu = this.closeMenu.bind(this);
        this.handleGesture = this.handleGesture.bind(this);
        this.onSwipeStart = this.onSwipeStart.bind(this);
        this.onSwipeEnd = this.onSwipeEnd.bind(this);
        this.handleEscape = this.handleEscape.bind(this);
    }
    
    init() {
        if (!this.menu) {
            console.warn('Мобильное меню не найдено в DOM!');
            return;
        }
        
        this.setOpenMenuHandler();
        this.setCloseMenuHandler();
        this.setEscapeHandler();
        
        // Инициализация подменю
        this.initSubmenus();
    }
    
    destroy() {
        this.removeCloseMenuHandler();
        this.removeSwipeMenuHandler();
        this.removeEscapeHandler();
    }
    
    // Event Handlers
    setOpenMenuHandler() {
        if (this.burger) {
            this.burger.addEventListener('click', this.toggleMenu, false);
        }
    }
    
    removeOpenMenuHandler() {
        if (this.burger) {
            this.burger.removeEventListener('click', this.toggleMenu, false);
        }
    }
    
    setCloseMenuHandler() {
        if (this.closeToggler) {
            this.closeToggler.addEventListener('click', this.closeMenu, false);
        }
        document.body.addEventListener('mouseup', this.maybeCloseMenu, false);
    }
    
    removeCloseMenuHandler() {
        if (this.closeToggler) {
            this.closeToggler.removeEventListener('click', this.closeMenu, false);
        }
        document.body.removeEventListener('mouseup', this.maybeCloseMenu, false);
    }
    
    setSwipeMenuHandler() {
        if (this.menu) {
            this.menu.addEventListener('touchstart', this.onSwipeStart, { passive: true });
            this.menu.addEventListener('touchend', this.onSwipeEnd, { passive: true });
        }
    }
    
    removeSwipeMenuHandler() {
        if (this.menu) {
            this.menu.removeEventListener('touchstart', this.onSwipeStart);
            this.menu.removeEventListener('touchend', this.onSwipeEnd);
        }
    }
    
    setEscapeHandler() {
        document.addEventListener('keydown', this.handleEscape, false);
    }
    
    removeEscapeHandler() {
        document.removeEventListener('keydown', this.handleEscape, false);
    }
    
    // Menu Actions
    maybeCloseMenu(e) {
        const target = e.target;
        if (!this.menu.isEqualNode(target) && 
            !this.burger.isEqualNode(target) && 
            !this.menu.contains(target)) {
            this.closeMenu();
        }
    }
    
    toggleMenu() {
        if (this.isOpen) {
            this.closeMenu();
        } else {
            this.openMenu();
        }
    }
    
    openMenu() {
        if (!this.menu) return;
        
        this.isOpen = true;
        this.burger?.classList.add('clicked');
        this.menu.classList.add('opened');
        document.body.classList.add('menu-open');
        
        this.setCloseMenuHandler();
        this.setSwipeMenuHandler();
        
        // Блокируем скролл
        document.body.style.overflow = 'hidden';
        
        // Закрываем все модалки при открытии меню
        this.closeAllModals();
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('mobileMenuOpened'));
    }
    
    closeMenu() {
        if (!this.menu) return;
        
        this.isOpen = false;
        this.menu.classList.remove('opened');
        this.burger?.classList.remove('clicked');
        document.body.classList.remove('menu-open');
        
        // Восстанавливаем скролл
        document.body.style.overflow = '';
        
        this.destroy();
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('mobileMenuClosed'));
    }
    
    // Touch Gestures
    handleGesture() {
        const moveX = (this.touchStats.startX - this.touchStats.endX);
        const moveY = Math.abs(this.touchStats.startY - this.touchStats.endY);
        
        if (moveX > moveY && moveX > 50) {
            this.closeMenu();
        }
    }
    
    onSwipeStart(e) {
        this.touchStats.startX = e.changedTouches[0].screenX;
        this.touchStats.startY = e.changedTouches[0].screenY;
    }
    
    onSwipeEnd(e) {
        this.touchStats.endX = e.changedTouches[0].screenX;
        this.touchStats.endY = e.changedTouches[0].screenY;
        this.handleGesture();
    }
    
    // Keyboard Support
    handleEscape(e) {
        if (e.key === 'Escape' && this.isOpen) {
            this.closeMenu();
        }
    }
    
    // Submenu Support
    initSubmenus() {
        const submenuToggles = this.menu?.querySelectorAll('li.has-childs > a');
        
        if (submenuToggles) {
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    const parent = toggle.parentElement;
                    const submenu = parent.querySelector('ul.sub-menu');
                    
                    if (submenu) {
                        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                        parent.classList.toggle('active');
                    }
                });
            });
        }
    }
    
    // Modal Integration
    closeAllModals() {
        // Закрываем Fancybox модалки
        if (typeof Fancybox !== 'undefined') {
            Fancybox.close();
        }
        
        // Закрываем другие модалки
        const modals = document.querySelectorAll('.modal, [data-modal]');
        modals.forEach(modal => {
            modal.classList.remove('active', 'show');
        });
    }
    
    // Public API
    isMenuOpen() {
        return this.isOpen;
    }
    
    forceClose() {
        this.closeMenu();
    }
}

// Export for global use
window.MobileMenu = MobileMenu;
