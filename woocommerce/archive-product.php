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
					<div class="product-list__outer-holder__filters-mobile">
						<div class="mobile-filter-trigger">
							<span class="mobile-filter-text">
								<?php
								switch ($current_orderby) {
									case 'price':
										echo 'По цене';
										break;
									case 'rating':
										echo 'По рейтингу';
										break;
									default:
										echo 'По популярности';
										break;
								}
								?>
							</span>
							<span class="mobile-filter-arrow">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M4.08301 5.83301L6.99967 8.74967L9.91634 5.83301" stroke="#801E1E" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
						</div>
						<div class="mobile-filter-dropdown">
							<a href="<?php echo add_query_arg('orderby', 'popularity'); ?>" class="mobile-filter-option <?php echo ($current_orderby === 'popularity') ? 'active' : ''; ?>">По популярности</a>
							<a href="<?php echo add_query_arg('orderby', 'price'); ?>" class="mobile-filter-option <?php echo ($current_orderby === 'price') ? 'active' : ''; ?>">По цене</a>
							<a href="<?php echo add_query_arg('orderby', 'rating'); ?>" class="mobile-filter-option <?php echo ($current_orderby === 'rating') ? 'active' : ''; ?>">По рейтингу</a>
						</div>
						<div class="mobile-open-filter__action">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g opacity="0.8">
									<path d="M14.1663 7.9996H5.92967M3.02234 7.9996H1.83301M3.02234 7.9996C3.02234 7.61415 3.17546 7.24449 3.44801 6.97194C3.72057 6.69939 4.09023 6.54627 4.47567 6.54627C4.86112 6.54627 5.23078 6.69939 5.50334 6.97194C5.77589 7.24449 5.92901 7.61415 5.92901 7.9996C5.92901 8.38505 5.77589 8.75471 5.50334 9.02726C5.23078 9.29982 4.86112 9.45294 4.47567 9.45294C4.09023 9.45294 3.72057 9.29982 3.44801 9.02726C3.17546 8.75471 3.02234 8.38505 3.02234 7.9996ZM14.1663 12.4043H10.3343M10.3343 12.4043C10.3343 12.7898 10.1809 13.1599 9.90824 13.4325C9.63562 13.7051 9.26588 13.8583 8.88034 13.8583C8.49489 13.8583 8.12523 13.7045 7.85268 13.4319C7.58013 13.1594 7.42701 12.7897 7.42701 12.4043M10.3343 12.4043C10.3343 12.0187 10.1809 11.6493 9.90824 11.3767C9.63562 11.1041 9.26588 10.9509 8.88034 10.9509C8.49489 10.9509 8.12523 11.1041 7.85268 11.3766C7.58013 11.6492 7.42701 12.0188 7.42701 12.4043M7.42701 12.4043H1.83301M14.1663 3.59493H12.0963M9.18901 3.59493H1.83301M9.18901 3.59493C9.18901 3.20949 9.34213 2.83983 9.61468 2.56727C9.88723 2.29472 10.2569 2.1416 10.6423 2.1416C10.8332 2.1416 11.0222 2.17919 11.1985 2.25223C11.3748 2.32527 11.535 2.43232 11.67 2.56727C11.805 2.70223 11.912 2.86244 11.985 3.03877C12.0581 3.21509 12.0957 3.40408 12.0957 3.59493C12.0957 3.78579 12.0581 3.97478 11.985 4.1511C11.912 4.32743 11.805 4.48764 11.67 4.6226C11.535 4.75755 11.3748 4.8646 11.1985 4.93764C11.0222 5.01068 10.8332 5.04827 10.6423 5.04827C10.2569 5.04827 9.88723 4.89515 9.61468 4.6226C9.34213 4.35004 9.18901 3.98038 9.18901 3.59493Z" stroke="#686868" stroke-width="0.8" stroke-miterlimit="10" stroke-linecap="round" />
								</g>
							</svg>
							<div class="mobile-open-filter__action-name">
								Фильтры
							</div>
						</div>
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
					<div class="product-list__outer-holder__filters-mobile">
						<div class="mobile-filter-trigger">
							<span class="mobile-filter-text">
								<?php
								switch ($current_orderby) {
									case 'price':
										echo 'По цене';
										break;
									case 'rating':
										echo 'По рейтингу';
										break;
									default:
										echo 'По популярности';
										break;
								}
								?>
							</span>
							<span class="mobile-filter-arrow">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M4.08301 5.83301L6.99967 8.74967L9.91634 5.83301" stroke="#801E1E" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
						</div>
						<div class="mobile-filter-dropdown">
							<a href="<?php echo add_query_arg('orderby', 'popularity'); ?>" class="mobile-filter-option <?php echo ($current_orderby === 'popularity') ? 'active' : ''; ?>">По популярности</a>
							<a href="<?php echo add_query_arg('orderby', 'price'); ?>" class="mobile-filter-option <?php echo ($current_orderby === 'price') ? 'active' : ''; ?>">По цене</a>
							<a href="<?php echo add_query_arg('orderby', 'rating'); ?>" class="mobile-filter-option <?php echo ($current_orderby === 'rating') ? 'active' : ''; ?>">По рейтингу</a>
						</div>
						<div class="mobile-open-filter__action">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g opacity="0.8">
									<path d="M14.1663 7.9996H5.92967M3.02234 7.9996H1.83301M3.02234 7.9996C3.02234 7.61415 3.17546 7.24449 3.44801 6.97194C3.72057 6.69939 4.09023 6.54627 4.47567 6.54627C4.86112 6.54627 5.23078 6.69939 5.50334 6.97194C5.77589 7.24449 5.92901 7.61415 5.92901 7.9996C5.92901 8.38505 5.77589 8.75471 5.50334 9.02726C5.23078 9.29982 4.86112 9.45294 4.47567 9.45294C4.09023 9.45294 3.72057 9.29982 3.44801 9.02726C3.17546 8.75471 3.02234 8.38505 3.02234 7.9996ZM14.1663 12.4043H10.3343M10.3343 12.4043C10.3343 12.7898 10.1809 13.1599 9.90824 13.4325C9.63562 13.7051 9.26588 13.8583 8.88034 13.8583C8.49489 13.8583 8.12523 13.7045 7.85268 13.4319C7.58013 13.1594 7.42701 12.7897 7.42701 12.4043M10.3343 12.4043C10.3343 12.0187 10.1809 11.6493 9.90824 11.3767C9.63562 11.1041 9.26588 10.9509 8.88034 10.9509C8.49489 10.9509 8.12523 11.1041 7.85268 11.3766C7.58013 11.6492 7.42701 12.0188 7.42701 12.4043M7.42701 12.4043H1.83301M14.1663 3.59493H12.0963M9.18901 3.59493H1.83301M9.18901 3.59493C9.18901 3.20949 9.34213 2.83983 9.61468 2.56727C9.88723 2.29472 10.2569 2.1416 10.6423 2.1416C10.8332 2.1416 11.0222 2.17919 11.1985 2.25223C11.3748 2.32527 11.535 2.43232 11.67 2.56727C11.805 2.70223 11.912 2.86244 11.985 3.03877C12.0581 3.21509 12.0957 3.40408 12.0957 3.59493C12.0957 3.78579 12.0581 3.97478 11.985 4.1511C11.912 4.32743 11.805 4.48764 11.67 4.6226C11.535 4.75755 11.3748 4.8646 11.1985 4.93764C11.0222 5.01068 10.8332 5.04827 10.6423 5.04827C10.2569 5.04827 9.88723 4.89515 9.61468 4.6226C9.34213 4.35004 9.18901 3.98038 9.18901 3.59493Z" stroke="#686868" stroke-width="0.8" stroke-miterlimit="10" stroke-linecap="round" />
								</g>
							</svg>
							<div class="mobile-open-filter__action-name">
								Фильтры
							</div>
						</div>
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
