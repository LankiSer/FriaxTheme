<?php
/**
 * WooCommerce Support Functions
 * 
 * @package Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

//============= WOOCOMMERCE THEME SUPPORT =============

add_action('after_setup_theme', 'theme_add_woocommerce_support');
function theme_add_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

//============= WOOCOMMERCE SETTINGS =============

/**
 * Change number of products per page
 */
add_filter('loop_shop_per_page', 'theme_products_per_page', 20);
function theme_products_per_page() {
    return 12;
}

/**
 * Change number of related products
 */
add_filter('woocommerce_output_related_products_args', 'theme_related_products_args');
function theme_related_products_args($args) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}

/**
 * Change product thumbnails columns
 */
add_filter('woocommerce_product_thumbnails_columns', 'theme_thumbnail_columns');
function theme_thumbnail_columns() {
    return 4;
}

//============= CUSTOM WOOCOMMERCE HOOKS =============

/**
 * Remove default WooCommerce styles (подключаем свои в main.css)
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Wrap WooCommerce content
 */
add_action('woocommerce_before_main_content', 'theme_woocommerce_wrapper_start', 10);
function theme_woocommerce_wrapper_start() {
    echo '<div class="container"><main id="primary" class="site-main woocommerce-page">';
}

add_action('woocommerce_after_main_content', 'theme_woocommerce_wrapper_end', 10);
function theme_woocommerce_wrapper_end() {
    echo '</main></div>';
}

/**
 * Remove WooCommerce breadcrumbs (используем свои)
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Add custom breadcrumbs
 */
add_action('woocommerce_before_main_content', 'theme_custom_breadcrumbs', 20);
function theme_custom_breadcrumbs() {
    if (function_exists('bcn_display')) {
        echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
        bcn_display();
        echo '</div>';
    }
}

//============= AJAX ADD TO CART =============

/**
 * AJAX Add to cart handler
 */
add_action('wp_ajax_theme_add_to_cart', 'theme_ajax_add_to_cart');
add_action('wp_ajax_nopriv_theme_add_to_cart', 'theme_ajax_add_to_cart');
function theme_ajax_add_to_cart() {
    check_ajax_referer('theme-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    
    if ($product_id && class_exists('WooCommerce')) {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
        
        if ($cart_item_key) {
            wp_send_json_success([
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'message' => __('Товар добавлен в корзину', 'theme'),
                'cart_hash' => WC()->cart->get_cart_hash(),
            ]);
        }
    }
    
    wp_send_json_error([
        'message' => __('Ошибка добавления товара', 'theme'),
    ]);
}

/**
 * Get cart count
 */
add_action('wp_ajax_theme_get_cart_count', 'theme_get_cart_count');
add_action('wp_ajax_nopriv_theme_get_cart_count', 'theme_get_cart_count');
function theme_get_cart_count() {
    if (class_exists('WooCommerce')) {
        wp_send_json_success([
            'cart_count' => WC()->cart->get_cart_contents_count(),
        ]);
    }
    
    wp_send_json_error();
}

//============= ACF FIELDS FOR PRODUCTS =============

/**
 * Product custom fields (создаются через ACF UI)
 * 
 * Рекомендуемые поля для товаров:
 * - product_badge (Select) - бейдж товара
 * - product_features (Repeater) - особенности
 * - product_specifications (Repeater) - характеристики
 * - product_video (URL) - видео обзор
 * - delivery_time (Text) - срок доставки
 */

/**
 * Get product badge
 */
function theme_product_badge() {
    if (!function_exists('get_field')) {
        return;
    }
    
    $badge = get_field('product_badge');
    
    if (!$badge) {
        return;
    }
    
    $badge_labels = [
        'new' => __('Новинка', 'theme'),
        'sale' => __('Скидка', 'theme'),
        'hit' => __('Хит продаж', 'theme'),
        'exclusive' => __('Эксклюзив', 'theme'),
    ];
    
    if (isset($badge_labels[$badge])) {
        echo '<span class="product-badge badge-' . esc_attr($badge) . '">' . esc_html($badge_labels[$badge]) . '</span>';
    }
}



//============= WOOCOMMERCE CUSTOMIZATION =============

/**
 * Change "Add to cart" button text
 */
add_filter('woocommerce_product_add_to_cart_text', 'theme_custom_add_to_cart_text');
function theme_custom_add_to_cart_text() {
    return __('В корзину', 'theme');
}

/**
 * Customize sale badge
 */
add_filter('woocommerce_sale_flash', 'theme_custom_sale_flash', 10, 3);
function theme_custom_sale_flash($html, $post, $product) {
    return '<span class="onsale">' . __('Скидка', 'theme') . '</span>';
}

function register_my_widgets()
{

	register_sidebar(array(
		'name'          => 'WooCommerce Sidebar',
		'id'            => "sidebar-shop",
		'description'   => '',
		'class'         => '',
		//		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		//		'after_widget'  => "</li>\n",
		//		'before_title'  => '<h2 class="widgettitle">',
		//		'after_title'   => "</h2>\n",
		// 'before_sidebar' => '', // WP 5.6
		// 'after_sidebar'  => '', // WP 5.6
	));
}

add_action('widgets_init', 'register_my_widgets');

function CUSTOM_woocommerce_pagination()
{
?>
    <?php

    $args = [
        'show_all'     => false, // показаны все страницы участвующие в пагинации
        'end_size'     => 1,     // количество страниц на концах
        'mid_size'     => 2,     // количество страниц вокруг текущей
        'prev_next'    => true,  // выводить ли боковые ссылки "предыдущая/следующая страница".
        'prev_text'    => '<div class="arrow-v1">←</div>',
        'next_text'    => '<div class="arrow-v1">→</div>',
        'type'         => 'array'
    ];

    $result = paginate_links($args);
    if ($result) {
        $prevArr = '<a href="#" class="prev disabled page-numbers arrow-v1">←</a>';
        $nextArr = '<a href="#" class="next disabled page-numbers arrow-v1">→</a>';
        if (strlen($result[0]) > 100) {
            $prevArr = $result[0];
            unset($result[0]);
        }
        if (strlen($result[array_key_last($result)]) > 100) {
            $nextArr = $result[array_key_last($result)];
            unset($result[array_key_last($result)]);
        }

    ?>
        <div class="navigation pagination default woocommerce-pagination">
            <?= $prevArr; ?>
            <div class="nav-links">
                <?php foreach ($result as $link) {
                    echo $link;
                } ?>
            </div>
            <?= $nextArr; ?>
        </div>
    <?php
    }
}


remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action('woocommerce_after_shop_loop', 'CUSTOM_woocommerce_pagination', 10);

add_filter( 'woocommerce_product_stock_status_options', 'rudr_product_statuses' );

function rudr_product_statuses( $product_statuses ){

	$product_statuses[ 'snyat-s-proizv' ] = 'Снят с производства';

	return $product_statuses;
}

add_filter( 'woocommerce_product_is_in_stock', 'rudr_allow_purchase', 10, 2 );

function rudr_allow_purchase( $allow, $product ) {
	
	if( 'snyat-s-proizv' === $product->get_stock_status() ) {
		$allow = false;
	}
	
	return $allow;
}

// Обработчик AJAX для добавления в корзину с доп опциями
add_action('wp_ajax_add_to_cart_with_addons', 'handle_add_to_cart_with_addons');
add_action('wp_ajax_nopriv_add_to_cart_with_addons', 'handle_add_to_cart_with_addons');

function handle_add_to_cart_with_addons() {
    // Проверка nonce для безопасности
    check_ajax_referer('add-to-cart-with-addons', 'security');
    
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $addons = isset($_POST['addons']) ? $_POST['addons'] : array();
    
    if ($product_id <= 0) {
        wp_send_json_error('Неверный ID товара');
        return;
    }
    
    // Получаем основной товар
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error('Товар не найден');
        return;
    }
    
    // Создаем уникальный ключ корзины на основе товара и доп опций
    $cart_item_key = generate_cart_item_key($product_id, $addons);
    
    // Подготавливаем данные для корзины
    $cart_item_data = array(
        'addons' => $addons,
        'unique_key' => $cart_item_key, // Уникальный ключ для разных комбинаций
    );
    
    // Добавляем в корзину
    $result = WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $cart_item_data);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => 'Товар добавлен в корзину',
            'cart_item_key' => $result
        ));
    } else {
        wp_send_json_error('Не удалось добавить товар в корзину');
    }
}

// Генерация уникального ключа для элемента корзины
function generate_cart_item_key($product_id, $addons) {
    $key_parts = array($product_id);
    
    if (!empty($addons)) {
        foreach ($addons as $addon) {
            $key_parts[] = $addon['id'] . '_' . $addon['price'];
        }
    }
    
    // Сортируем, чтобы порядок выбора доп опций не влиял на уникальность
    sort($key_parts);
    
    return md5(implode('_', $key_parts));
}

// Отображение доп опций в корзине
add_filter('woocommerce_get_item_data', 'display_addons_in_cart', 10, 2);

function display_addons_in_cart($item_data, $cart_item) {
    if (isset($cart_item['addons']) && !empty($cart_item['addons'])) {
        foreach ($cart_item['addons'] as $addon) {
            $item_data[] = array(
                'key' => $addon['name'],
                'value' => '+' . wc_price($addon['price']),
                'display' => ''
            );
        }
    }
    return $item_data;
}

// Расчет общей цены с учетом доп опций
add_action('woocommerce_before_calculate_totals', 'add_addons_to_price', 20, 1);

function add_addons_to_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    
    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['addons']) && !empty($cart_item['addons'])) {
            $addons_total = 0;
            foreach ($cart_item['addons'] as $addon) {
                $addons_total += floatval($addon['price']);
            }
            
            // Получаем базовую цену товара
            $base_price = $cart_item['data']->get_price();
            // Устанавливаем новую цену
            $cart_item['data']->set_price($base_price + $addons_total);
        }
    }
}

// Убедитесь, что товары с разными доп опциями считаются разными
add_filter('woocommerce_cart_id', 'custom_cart_item_id', 10, 5);

function custom_cart_item_id($cart_id, $product_id, $variation_id, $variation, $cart_item_data) {
    if (isset($cart_item_data['unique_key'])) {
        $cart_id = $cart_id . '_' . $cart_item_data['unique_key'];
    }
    return $cart_id;
}

// Сохранение доп опций в заказе
add_action('woocommerce_checkout_create_order_line_item', 'save_addons_to_order_item', 10, 4);

function save_addons_to_order_item($item, $cart_item_key, $cart_item, $order) {
    if (isset($cart_item['addons']) && !empty($cart_item['addons'])) {
        // Сохраняем общую стоимость дополнений
        $addons_total = 0;
        foreach ($cart_item['addons'] as $addon) {
            $addons_total += floatval($addon['price']);
            
            // Сохраняем каждое дополнение как мета-данные
            $item->add_meta_data(
                $addon['name'],
                wc_price($addon['price']),
                true
            );
        }
        
        // Сохраняем общую стоимость всех дополнений
        $item->add_meta_data(
            '_addons_total',
            $addons_total,
            true
        );
        
        // Сохраняем список всех дополнений в сериализованном виде
        $item->add_meta_data(
            '_addons_list',
            $cart_item['addons'],
            true
        );
    }
}

// Отображение доп опций в админке в заказах
add_action('woocommerce_before_order_itemmeta', 'display_addons_in_admin_order', 10, 3);

function display_addons_in_admin_order($item_id, $item, $product) {
    // Проверяем, есть ли у товара дополнения
    $addons_list = $item->get_meta('_addons_list');
    
    if (!empty($addons_list)) {
        echo '<div class="wc-order-item-addons" style="margin-top: 10px; padding: 10px; background: #f8f8f8; border-radius: 4px;">';
        echo '<strong style="display: block; margin-bottom: 5px;">Дополнительные опции:</strong>';
        
        foreach ($addons_list as $addon) {
            echo '<div style="display: flex; justify-content: space-between; margin-bottom: 3px;">';
            echo '<span>' . esc_html($addon['name']) . '</span>';
            echo '<span style="font-weight: bold;">+' . wc_price($addon['price']) . '</span>';
            echo '</div>';
        }
        
        $addons_total = $item->get_meta('_addons_total');
        if ($addons_total) {
            echo '<div style="display: flex; justify-content: space-between; margin-top: 5px; padding-top: 5px; border-top: 1px solid #ddd; font-weight: bold;">';
            echo '<span>Общая стоимость дополнений:</span>';
            echo '<span>+' . wc_price($addons_total) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

// Отображение доп опций в emails
add_filter('woocommerce_order_item_name', 'display_addons_in_emails', 10, 2);

function display_addons_in_emails($item_name, $item) {
    $addons_list = $item->get_meta('_addons_list');
    
    if (!empty($addons_list) && is_array($addons_list)) {
        $addons_html = '<div style="margin: 5px 0 0 10px; font-size: 0.9em;">';
        $addons_html .= '<strong>Доп. опции:</strong><br>';
        
        foreach ($addons_list as $addon) {
            $addons_html .= '— ' . esc_html($addon['name']) . ' (+' . wc_price($addon['price']) . ')<br>';
        }
        
        $addons_html .= '</div>';
        $item_name .= $addons_html;
    }
    
    return $item_name;
}

remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 20);


// Добавляем nonce для безопасности
add_action('wp_enqueue_scripts', 'add_custom_checkout_nonce');
function add_custom_checkout_nonce() {
    if (is_checkout()) {
        wp_localize_script('wc-checkout', 'custom_checkout_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'update_order_review_nonce' => wp_create_nonce('update_order_review_nonce')
        ));
    }
}

// AJAX обработчик для обновления review order
add_action('wp_ajax_update_order_review', 'ajax_update_order_review');
add_action('wp_ajax_nopriv_update_order_review', 'ajax_update_order_review');

function ajax_update_order_review() {
    // Проверка nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'update_order_review_nonce')) {
        wp_die('Security check failed');
    }
    
    // Парсим переданные данные
    if (isset($_POST['post_data'])) {
        parse_str($_POST['post_data'], $post_data);
        
        // Устанавливаем выбранные методы в сессии
        if (isset($post_data['shipping_method'])) {
            WC()->session->set('chosen_shipping_methods', $post_data['shipping_method']);
        }
        
        if (isset($post_data['payment_method'])) {
            WC()->session->set('chosen_payment_method', $post_data['payment_method']);
        }
    }
    
    // Обновляем итоги
    WC()->cart->calculate_totals();
    
    // Выводим обновленный блок review order
    woocommerce_order_review();
    
    wp_die();
}