# JavaScript Modules Guide

Руководство по работе с JavaScript модулями в теме.

## Структура JS модулей

```
assets/js/
├── main.js                    # Главный файл (инициализация всех модулей)
├── modules/
│   ├── mobile-menu.js        # Мобильное меню
│   ├── modal-manager.js      # Управление модальными окнами
│   └── woocommerce-ajax.js   # AJAX для WooCommerce
└── библиотеки (swiper, fancybox, inputmask)
```

## Модули

### 1. MobileMenu (mobile-menu.js)

**Функционал:**
- Управление мобильным меню
- Поддержка свайпов (закрытие свайпом влево)
- Интеграция с модальными окнами
- Поддержка клавиатуры (ESC)
- Блокировка скролла при открытом меню

**API:**
```javascript
const mobileMenu = new MobileMenu();
mobileMenu.init();                    // Инициализация
mobileMenu.openMenu();               // Открыть меню
mobileMenu.closeMenu();              // Закрыть меню
mobileMenu.toggleMenu();             // Переключить
mobileMenu.isMenuOpen();             // Проверить состояние
mobileMenu.forceClose();             // Принудительно закрыть
```

**События:**
- `mobileMenuOpened` - меню открыто
- `mobileMenuClosed` - меню закрыто

### 2. ModalManager (modal-manager.js)

**Функционал:**
- Управление Fancybox модалками
- Поддержка кастомных модалок
- Интеграция с формами
- Автозакрытие статусных модалок
- Интеграция с мобильным меню

**API:**
```javascript
const modalManager = new ModalManager();
modalManager.openModal('#modal-id');     // Открыть модалку
modalManager.closeModal('#modal-id');    // Закрыть модалку
modalManager.closeAllModals();           // Закрыть все
modalManager.isModalOpen();              // Проверить состояние
```

**Поддерживаемые модалки:**
- `[data-modal]` - обычные модалки
- `[data-modal="service"]` - модалки для услуг/товаров
- `#modal-success` - статус успеха
- `#modal-error` - статус ошибки

### 3. WooCommerceAjax (woocommerce-ajax.js)

**Функционал:**
- AJAX добавление в корзину
- Обновление счетчика корзины
- Управление количеством товаров
- Уведомления о действиях
- Интеграция с WooCommerce событиями

**API:**
```javascript
const wooAjax = new WooCommerceAjax();
// Автоматически инициализируется при наличии themeWoo
```

**Поддерживаемые элементы:**
- `.ajax_add_to_cart` - кнопки добавления в корзину
- `.qty` - поля количества
- `.qty-btn` - кнопки +/- количества
- `.remove` - кнопки удаления из корзины

## Настройка

### Подключение модулей

В `functions.php`:
```php
wp_enqueue_script( 'mobileMenu', get_template_directory_uri() . '/assets/js/modules/mobile-menu.js', array('jquery'), _S_VERSION, true );
wp_enqueue_script( 'modalManager', get_template_directory_uri() . '/assets/js/modules/modal-manager.js', array('jquery'), _S_VERSION, true );
wp_enqueue_script( 'wooAjax', get_template_directory_uri() . '/assets/js/modules/woocommerce-ajax.js', array('jquery'), _S_VERSION, true );
wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'mobileMenu', 'modalManager', 'wooAjax'), _S_VERSION, true );
```

### Локализация для WooCommerce

В `woocommerce-support.php`:
```php
wp_localize_script('main', 'themeWoo', [
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('theme-nonce'),
    'strings' => [
        'addedToCart' => __('Товар добавлен в корзину', 'theme'),
        'error' => __('Произошла ошибка', 'theme'),
    ],
]);
```

## HTML структура

### Мобильное меню
```html
<!-- Кнопка открытия -->
<button class="burger open_menu">
    <span></span>
    <span></span>
    <span></span>
</button>

<!-- Меню -->
<div id="mobile-mnu" class="mobile-menu">
    <button id="close-mnu" class="close-btn">×</button>
    <nav>
        <ul>
            <li class="has-childs">
                <a href="#">Родительский пункт</a>
                <ul class="sub-menu">
                    <li><a href="#">Подпункт</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
```

### Модальные окна
```html
<!-- Триггер -->
<button data-modal="#modal-callback">Открыть модалку</button>

<!-- Модалка -->
<div id="modal-callback" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <button class="modal-close">×</button>
        <!-- Контент модалки -->
    </div>
</div>
```

### WooCommerce элементы
```html
<!-- AJAX кнопка добавления в корзину -->
<button class="ajax_add_to_cart" data-product_id="123" data-quantity="1">
    В корзину
</button>

<!-- Счетчик корзины -->
<span class="cart-count">0</span>

<!-- Управление количеством -->
<div class="quantity">
    <button class="qty-btn qty-minus">-</button>
    <input type="number" class="qty" value="1" min="1" max="10">
    <button class="qty-btn qty-plus">+</button>
</div>
```

## События

### Кастомные события
```javascript
// Мобильное меню
document.addEventListener('mobileMenuOpened', function() {
    console.log('Меню открыто');
});

document.addEventListener('mobileMenuClosed', function() {
    console.log('Меню закрыто');
});

// WooCommerce
document.addEventListener('added_to_cart', function() {
    console.log('Товар добавлен в корзину');
});

document.addEventListener('removed_from_cart', function() {
    console.log('Товар удален из корзины');
});
```

## Кастомизация

### Добавление своего модуля

1. Создайте файл `assets/js/modules/my-module.js`:
```javascript
class MyModule {
    constructor() {
        this.init();
    }
    
    init() {
        // Ваш код
    }
}

window.MyModule = MyModule;
```

2. Подключите в `functions.php`:
```php
wp_enqueue_script( 'myModule', get_template_directory_uri() . '/assets/js/modules/my-module.js', array('jquery'), _S_VERSION, true );
```

3. Инициализируйте в `main.js`:
```javascript
let myModule = new MyModule();
```

### Переопределение методов

```javascript
// Расширение MobileMenu
class CustomMobileMenu extends MobileMenu {
    openMenu() {
        super.openMenu();
        // Дополнительная логика
        console.log('Меню открыто с кастомной логикой');
    }
}

// Использование
let mobileMenu = new CustomMobileMenu();
```

## 🐛 Отладка

### Включение debug режима
```javascript
// В main.js
if (window.location.hostname === 'localhost') {
    console.log('Debug mode enabled');
    console.log('Mobile Menu:', mobileMenu);
    console.log('Modal Manager:', modalManager);
}
```

### Проверка состояния модулей
```javascript
// В консоли браузера
console.log('Menu open:', mobileMenu.isMenuOpen());
console.log('Modal open:', modalManager.isModalOpen());
```

## Адаптивность

### Breakpoints
```javascript
// В main.js
$(window).on('resize', function() {
    if ($(window).width() > 996) {
        // Закрываем мобильное меню на десктопе
        mobileMenu.forceClose();
    }
});
```

### Touch события
Все модули поддерживают touch события для мобильных устройств.

## Производительность

### Lazy loading
```javascript
// В main.js
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                observer.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
```

### Debounce для resize
```javascript
let resizeTimer;
$(window).on('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        // Логика при изменении размера
    }, 250);
});
```

## 📚 Полезные ссылки

- [MDN - Intersection Observer](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- [MDN - Touch Events](https://developer.mozilla.org/en-US/docs/Web/API/Touch_events)
- [Fancybox Documentation](https://fancyapps.com/docs/)
- [WooCommerce AJAX](https://woocommerce.com/document/woocommerce-shortcodes/)

---

**Успехов в разработке!**
