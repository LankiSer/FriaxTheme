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
		<div class="products-list__category">
			<div class="products-list__category-holder">
				<?php wc_get_template('custom/filter/index.php'); ?>
				<div class="products-list__outer-holder">
					<?php
					$current_orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';
					?>
					<div class="productt-list__outer-holder__filters">
						<a href="<?php echo add_query_arg('orderby', 'popularity'); ?>" class="filter-link <?php echo ($current_orderby === 'popularity') ? 'active' : ''; ?>">По популярности</a>
						<a href="<?php echo add_query_arg('orderby', 'price'); ?>" class="filter-link <?php echo ($current_orderby === 'price') ? 'active' : ''; ?>">По цене</a>
						<a href="<?php echo add_query_arg('orderby', 'rating'); ?>" class="filter-link <?php echo ($current_orderby === 'rating') ? 'active' : ''; ?>">По рейтингу</a>
					</div>
					<?php wc_get_template('custom/productsList/index.php'); ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<?php if (is_product_category()) { ?>
		<?php wc_get_template('custom/category/categoryBanner.php'); ?>
		<div class="products-list__category">
			<div class="products-list__category-holder">
				<?php wc_get_template('custom/filter/index.php'); ?>
				<div class="products-list__outer-holder">
					<?php
					$current_orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';
					?>
					<div class="productt-list__outer-holder__filters">
						<a href="<?php echo add_query_arg('orderby', 'popularity'); ?>" class="filter-link <?php echo ($current_orderby === 'popularity') ? 'active' : ''; ?>">По популярности</a>
						<a href="<?php echo add_query_arg('orderby', 'price'); ?>" class="filter-link <?php echo ($current_orderby === 'price') ? 'active' : ''; ?>">По цене</a>
						<a href="<?php echo add_query_arg('orderby', 'rating'); ?>" class="filter-link <?php echo ($current_orderby === 'rating') ? 'active' : ''; ?>">По рейтингу</a>
					</div>
					<?php wc_get_template('custom/productsList/index.php'); ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<?php if (is_search()) { ?>

	<?php } ?>

</div>
<?php
do_action('woocommerce_after_main_content');
get_footer('shop');
