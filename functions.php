<?php

//=========== BASE CONFIG ============

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

function theme_setup() {

	load_theme_textdomain( 'theme', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'widgets' );
	add_theme_support( 'widgets-block-editor' );
	add_theme_support( 'woocommerce' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

}
add_action( 'after_setup_theme', 'theme_setup' );


function theme_scripts() {

    wp_enqueue_style( 'constructor-colors', get_template_directory_uri() . '/assets/css/constructor-colors.css', array(), filemtime(__DIR__ . '/assets/css/constructor-colors.css'));
    wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css', array('constructor-colors'), filemtime(__DIR__ . '/assets/css/main.css'));
    wp_enqueue_style( 'fonts', get_template_directory_uri() . '/assets/fonts/fonts.css');
    wp_enqueue_style( 'swiperCss', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css');
    wp_enqueue_style( 'fancybox', get_template_directory_uri() . '/assets/css/fancybox.min.css');

    wp_enqueue_script( 'swiperJs', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array('jquery'), _S_VERSION, true );
    wp_enqueue_script( 'swiperJsCustom', get_template_directory_uri() . '/assets/js/swiper.js', array('jquery','swiperJs'), _S_VERSION, true );
    wp_enqueue_script( 'fancyboxJs', get_template_directory_uri() . '/assets/js/fancybox.min.js', array('jquery'), _S_VERSION, true );
    wp_enqueue_script( 'inputmask', get_template_directory_uri() . '/assets/js/inputmask.js', array('jquery'), _S_VERSION, true );
    wp_enqueue_script( 'mobileMenu', get_template_directory_uri() . '/assets/js/modules/mobile-menu.js', array('jquery'), _S_VERSION, true );
    wp_enqueue_script( 'modalManager', get_template_directory_uri() . '/assets/js/modules/modal-manager.js', array('jquery'), _S_VERSION, true );
    wp_enqueue_script( 'wooAjax', get_template_directory_uri() . '/assets/js/modules/woocommerce-ajax.js', array('jquery'), _S_VERSION, true );
	wp_enqueue_script('wooNotices', get_template_directory_uri() . '/assets/js/woocommerceNotices.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_script('wooNoticer', get_template_directory_uri() . '/assets/js/modules/noticer.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'mobileMenu', 'modalManager', 'wooAjax', 'fancyboxJs', 'inputmask'), filemtime(__DIR__ . '/assets/js/main.js'), true );

}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');





function slider_nav() {
	?>
		<div class="nav">
			<div class="buttons">
				<div class="swiper-button-prev">
					<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M6 1L1 6L6 11" stroke="#FFBD00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</div>
				<div class="swiper-button-next">
					<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 11L6 6L1 1" stroke="#FFBD00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</div>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	<?php
};





/*========= SUPPORT ES6 MODULES ===========*/
function scripts_as_es6_modules( $tag, $handle, $src ) {
	
	if ('mobileMenu' === $handle || 'themeModal' === $handle || 'main' === $handle) {
		return str_replace( '<script ', '<script type="module"', $tag );
	}
	
	return $tag;
}
// add_filter( 'script_loader_tag', 'scripts_as_es6_modules', 10, 3 );


function show_post($path) {
    $post = get_page_by_path($path);
    $content = apply_filters('the_content', $post->post_content);
    echo $content;
}
add_filter( 'script_loader_tag', 'scripts_as_es6_modules', 10, 3 );


// Удалены лишние кастомные типы постов (products, reviews, stocks, works)
// Для WooCommerce используем встроенные типы




/*========= ADD CANNONICAL LINKS ===========*/
add_filter( 'wpseo_canonical', 'return_canon' );
function return_canon () {
    if (is_paged()) {
        $canon_page = get_pagenum_link(1);
        return $canon_page;
    }
}


//============= THEME FUNCTIONS =============

require get_template_directory() . '/inc/template-functions.php';

//============= WOOCOMMERCE SUPPORT =============

require get_template_directory() . '/inc/woocommerce-support.php';

//============= THEME SETTINGS =============

require get_template_directory() . '/inc/theme-settings.php';

//============= ACF BLOCKS =============

// Автозагрузка ACF блоков
$blocks_dir = get_template_directory() . '/inc/blocks';
if (is_dir($blocks_dir)) {
    $blocks = glob($blocks_dir . '/*/index.php');
    foreach ($blocks as $block) {
        require $block;
    }
}


/*=========== MENUS ==============*/

register_nav_menu( 'TopMenu', 'Верхнее меню' );
register_nav_menu( 'footCat', 'Каталог подвал' );
register_nav_menu( 'footMenu', 'Меню подвал' );
register_nav_menu( 'mobileMenu', 'Мобильное меню' );


function num_decline( $number, $titles, $show_number = true ){

	if( is_string( $titles ) ){
		$titles = preg_split( '/, */', $titles );
	}

	// когда указано 2 элемента
	if( empty( $titles[2] ) ){
		$titles[2] = $titles[1];
	}

	$cases = [ 2, 0, 1, 1, 1, 2 ];

	$intnum = abs( (int) strip_tags( $number ) );

	$title_index = ( $intnum % 100 > 4 && $intnum % 100 < 20 )
		? 2
		: $cases[ min( $intnum % 10, 5 ) ];

	return ( $show_number ? "$number " : '' ) . $titles[ $title_index ];
}
