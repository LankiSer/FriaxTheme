<?php
// ================= ФИЛЬТРЫ ====================
add_filter('excerpt_more', function($more) {
    return '...';
});
add_filter( 'excerpt_length', function(){
    return 25;
} );


// ================ FUNCTIONS =============== //



/*--------- Рендеринг хлебных крошек --------*/
function render_breads() {
	if(function_exists('bcn_display')) { ?>
		<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
			<?php bcn_display(); ?>
		</div>
	<?php }
}


/*-------- ГЕНЕРАЦИЯ ID БЛОКА -----------*/
function blockId($block) {
    if(!$block) {
        return;
    }
    $blockNum = $block . '-block-0';

    $blockName = $block . '-block';

    if(array_key_exists($blockName, $GLOBALS) && !empty($GLOBALS[$blockName])) {
        $blockNum = $block . '-block-' . count($GLOBALS[$blockName]);
        $GLOBALS[$blockName][] = $blockNum;
    }else{
        $GLOBALS[$blockName][] = $blockNum;
    }

    return $blockNum;
}



/*------- ПОЛУЧЕНИЕ КОНТЕНТА С ОПРЕДЕЛЁННОЙ СТРАНИЦЫ ----------*/

function get_page_content($page_id) {
	if(!$page_id) {
		return;
	}
	$content = get_the_content( '', false, $page_id  );
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	return $content;
}


/*------- ПОЛУЧЕНИЕ ФОРМЫ ----------*/

function get_form($formname = '', $params = []) {
	$echo = true;
	
	if(array_key_exists('echo', $params)) {
		$echo = $params['echo'];
	}
	
	if(!$formname) {
		if($echo === true) {
			echo 'Форма не найдена!';
            return '';
		}else{
			return false;
		}
	}
	
	if($echo) {
		get_template_part('inc/parts/forms/form', $formname, $params);
	}else{
		ob_start();
		get_template_part('inc/parts/forms/form', $formname, $params);
		$out = ob_get_clean();
		return $out;
	}
}


/*-------- ПЕРЕВОД ПОЛЕЙ ---------*/

if( function_exists('GSE') ) {
    GSE()::add_translation('subject','Тема письма');
    GSE()::add_translation('your-name','Имя');
    GSE()::add_translation('your-tel','Телефон');
    GSE()::add_translation('message','Сообщение');
    GSE()::add_translation('service-title','Название услуги');
}




// ============== ADD THEME PAGE ===============

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'        => 'Параметры темы',
        'menu_title'        => 'Параметры темы',
        'menu_slug'         => 'gs-theme-params',
        'capability'        => 'manage_options',
        'parent_slug'       => 'themes.php',
        'icon_url'          => 'dashicons-location-alt',
        'redirect'          => false,
        'autoload'          => true,
        'update_button'     => 'Обновить',
        'updated_message'   => 'Параметры темы обновлены',
    ));
}


function theme($type)
{
    $setting = get_field($type,'options');
    if($setting)
    {
        return $setting;
    }
    else
    {
        return '';
    }
}




// =========== РЕГИСТРАЦИЯ БЛОКОВ ===============
// (Перенесено в functions.php - автозагрузка блоков)

function wide_Setup() {
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'wide_Setup' );

function renderImage($id, $width = null, $height = null, $class = '', $custom_alt = '') {
    if (empty($id)) {
        return;
    }

    $size = 'full';
    if ($width !== null && $height !== null) {
        $size = [$width, $height];
    } elseif ($width !== null) {
        $size = [$width, 'full'];
    } elseif ($height !== null) {
        $size = ['full', $height];
    }

    $image_html = wp_get_attachment_image($id, $size);

    $alt_text = get_post_meta($id, '_wp_attachment_image_alt', true);

    if (empty($alt_text) && !empty($custom_alt)) {
        $image_html = str_replace('alt=""', 'alt="' . esc_attr($custom_alt) . '"', $image_html);
    }

    if (!empty($class)) {
        $image_html = str_replace('class="', 'class="' . esc_attr($class) . ' ', $image_html);
    }

    echo $image_html;
}