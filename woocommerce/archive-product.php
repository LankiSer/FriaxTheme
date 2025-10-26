<?php
defined('ABSPATH') || exit;
get_header('shop');

?>
<div class="container">
	<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
		<?php if (function_exists('bcn_display')) {
			bcn_display();
		} ?>
	</div>

	<?php if (is_shop()) { ?>
		<h1 class="page-title"><?= get_the_title(get_option('woocommerce_shop_page_id')) ?></h1>
	<?php } ?>


	<?php if (is_shop() && !is_search()) { ?>
		<?php wc_get_template('custom/category/categories.php'); ?>
	<?php } ?>

	<?php if (is_product_category()) { ?>
		<?php wc_get_template('custom/category/categoryBanner.php'); ?>
	<?php } ?>

	<?php if (is_search()) { ?>

	<?php } ?>

</div>
<?php
do_action('woocommerce_after_main_content');
get_footer('shop');
