<?php
/**
 * Block Name - ACF Block Registration
 */

if (!class_exists('BlockName')) {
	class BlockName
	{

		public function __construct()
		{
			add_action('acf/init', array($this, '_register'));
			add_action('acf/init', array($this,'_init_fields'));
		}

		public function _register()
		{
			if (function_exists('acf_register_block_type')) {
				acf_register_block_type(
					array(
						'name'              => 'name-block',
						'title'             => 'Имя блока',
						'description'       => 'Описание',
						// 'post_types'        => array(),
						'render_callback'   => array($this, '_render'),
						'category'          => 'theme-blocks',
						'icon'              => 'format-aside',
						'mode'              => 'edit',
						'align'             => 'wide',
						'supports'          => array(
							'align' => array('wide','full'),
							'mode'  => true,
						),
						'enqueue_assets'    => array($this, '_enqueue_assets'),
					)
				);
			}
		}

		public function _enqueue_assets()
		{
			$tpath = wp_normalize_path(get_template_directory());
			$cpath = wp_normalize_path(__DIR__);
			$path = explode($tpath,$cpath)[1];
			wp_enqueue_style('theme/name-block',wp_normalize_path(GSE()::theme_uri().$path.'/block.css'),array(),filemtime($cpath . '/block.css'));
			wp_enqueue_script('theme/name-block',wp_normalize_path(GSE()::theme_uri().$path.'/block.js'),array(),filemtime($cpath . '/block.js'));
			return;
		}

		public function _render($block, $content = '', $is_preview = false)
		{
			$id = $block['id'];
			include 'render.php';
		}

		public function _init_fields()
		{
			// Загрузка полей из fields.json
			$fields_file = __DIR__ . '/fields.json';
			
			if (file_exists($fields_file)) {
				$fields = json_decode(file_get_contents($fields_file), true);
				
				if ($fields && function_exists('acf_add_local_field_group')) {
					acf_add_local_field_group($fields);
				}
			}
		}
	}
}

// Инициализация
if (class_exists('BlockName')) {
	return new BlockName();
}
?>