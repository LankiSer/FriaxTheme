<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$uniqId = uniqid();
$id = $product->get_id();
$productId = $product->get_id();
$description = $product->description;
$short_description = $product->get_short_description();
$in_stock = $product->is_in_stock();
$productPhoto = get_post_thumbnail_id($productId, 'single-post-thumbnail');
$galleryPhotosIds = $product->get_gallery_image_ids();
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$price_html = $product->get_price_html();

$stock_status = $product->get_stock_status();
$stock_text = '';
$stock_class = '';

if ($stock_status === 'instock') {
	$stock_text = 'В наличии';
	$stock_class = 'stock-instock';
} elseif ($stock_status === 'outofstock') {
	$stock_text = 'Нет в наличии';
	$stock_class = 'stock-outofstock';
} elseif ($stock_status === 'snyat-s-proizv') {
	$stock_text = 'Снят с производства';
	$stock_class = 'stock-snyat';
}

$gal = array();

if ($productPhoto) {
	$gal[] = $productPhoto;
}

if (!empty($galleryPhotosIds)) {
	$gal = array_merge($gal, $galleryPhotosIds);
}

if (empty($gal)) {
	$placeholder_id = get_option('woocommerce_placeholder_image', 0);
	if ($placeholder_id) {
		$gal[] = $placeholder_id;
	}
}

$addons = get_field('product_addons');

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('wc-single__product', $product); ?>>
	<div class="wc-single__product-summary">
		<?php if (!empty($gal)) { ?>
			<?php
			renderComponentFunc(getComponentFunc('swiperThumbs/index.php')); // here is js function and template html
			?>
			<?= templateThumbsSlider($uniqId, $gal, get_the_title()); ?>
		<?php } ?>
		<div class="wc-single__product-content">
			<div class="wc-single__product-content__container">
				<div class="wc-single__product-stock__badge <?= $stock_class; ?>">
					<?= $stock_text; ?>
				</div>
				<div class="wc-single__product-title__holder">
					<div class="wc-single__product-title">
						<?= $product->name; ?>
					</div>
					<?php if (!empty($short_description)) { ?>
						<div class="wc-single__product-short__desc">
							<?= $short_description; ?>
						</div>
					<?php } ?>
				</div>
				<?php if (!empty($addons)) { ?>
					<div class="wc-single__product-addons">
						<div class="wc-single__product-addons__title">
							ДОБАВИТЬ:
						</div>
						<div class="wc-single__product-addons__items">
							<?php foreach ($addons as $key => $addon) {
								$addon_name = $addon['addon_name'];
								$addon_price = $addon['addon_price'];
								// Создаем уникальный ID для каждой опции
								$addon_id = 'addon_' . $productId . '_' . $key;
							?>
								<div class="wc-single__product-addons__item">
									<input type="checkbox"
										name="product_addon[]"
										value="<?php echo esc_attr($addon_price); ?>"
										data-addon-name="<?php echo esc_attr($addon_name); ?>"
										data-addon-id="<?php echo esc_attr($addon_id); ?>"
										id="<?php echo $addon_id; ?>">
									<label for="<?php echo $addon_id; ?>">
										<?php echo esc_html($addon_name); ?>
										<br>
										+<?php echo number_format($addon_price, 0, '', ' '); ?> ₽
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<div class="wc-single__product-actions">
					<div class="wc-single__product-price__holder"
						id="price_holder_<?php echo $uniqId; ?>"
						data-base-price="<?php echo $sale_price ? $sale_price : $regular_price; ?>"
						data-regular-price="<?php echo $regular_price; ?>"
						data-is-on-sale="<?php echo $sale_price ? 'yes' : 'no'; ?>">
						<?php if ($sale_price && $regular_price) : ?>
							<span class="sale-price">
								<?php echo wc_price($sale_price); ?>
							</span>
							<div class="regular-price__holder">
								<span class="regular-price">
									<?php echo wc_price($regular_price); ?>
								</span>
							</div>
						<?php else : ?>
							<span class="price">
								<?php echo $price_html; ?>
							</span>
						<?php endif; ?>
					</div>
					<div class="wc-single__product-actions__buttons">
						<?php if ($product->is_type('simple') && $stock_status == 'instock') { ?>
							<button
								class="wc-single__product-btn cart-btn__v1 button add_to_cart_with_addons"
								data-product_id="<?php echo $product->get_id(); ?>"
								data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
								data-quantity="1"
								aria-label="<?php echo esc_attr__('Добавить в корзину', 'woocommerce'); ?>"
								<?php if (!$product->is_in_stock()) { ?> disabled style="pointer-events:none" <?php } ?>>
								Добавить в корзину
							</button>
						<?php } ?>
						<?php if ($stock_status == 'instock') { ?>
							<div class="wc-single__product-oneclick">
								Купить в 1 клик
							</div>
						<?php } ?>
					</div>

					<!-- Скрытые поля для передачи данных доп опций -->
					<div class="addons-data" style="display: none;">
						<input type="hidden" id="selected_addons_<?php echo $uniqId; ?>" name="selected_addons" value="">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		// Функция для форматирования цены
		function formatPrice(price) {
			return new Intl.NumberFormat('ru-RU', {
				minimumFractionDigits: 0,
				maximumFractionDigits: 0,
			}).format(price) + ' ₽';
		}

		// Функция обновления цены
		function updatePrice() {
			var basePrice = parseFloat($('#price_holder_<?php echo $uniqId; ?>').data('base-price')) || 0;
			var regularPrice = parseFloat($('#price_holder_<?php echo $uniqId; ?>').data('regular-price')) || 0;
			var isOnSale = $('#price_holder_<?php echo $uniqId; ?>').data('is-on-sale') === 'yes';

			// Суммируем выбранные доп опции
			var addonsTotal = 0;
			$('input[name="product_addon[]"]:checked').each(function() {
				addonsTotal += parseFloat($(this).val()) || 0;
			});

			var totalPrice = basePrice + addonsTotal;
			var totalRegularPrice = regularPrice + addonsTotal;

			// Обновляем отображение цены
			if (isOnSale) {
				$('#price_holder_<?php echo $uniqId; ?>').html(
					'<span class="sale-price">' + formatPrice(totalPrice) + '</span>' +
					'<div class="regular-price__holder">' +
					'<span class="regular-price">' + formatPrice(totalRegularPrice) + '</span>' +
					'</div>'
				);
			} else {
				$('#price_holder_<?php echo $uniqId; ?>').html(
					'<span class="price">' + formatPrice(totalPrice) + '</span>'
				);
			}
		}

		// Обработчик изменения чекбоксов доп опций
		$('input[name="product_addon[]"]').on('change', function() {
			updatePrice();
		});

		// Обработчик добавления в корзину с доп опциями
		$('.add_to_cart_with_addons').on('click', function(e) {
			e.preventDefault();

			var $button = $(this);
			var product_id = $button.data('product_id');
			var quantity = $button.data('quantity') || 1;

			// Собираем выбранные доп опции
			var selectedAddons = [];
			$('input[name="product_addon[]"]:checked').each(function() {
				selectedAddons.push({
					id: $(this).data('addon-id'),
					name: $(this).data('addon-name'),
					price: $(this).val()
				});
			});

			// Блокируем кнопку на время добавления
			$button.prop('disabled', true).text('Добавляем...');

			// Добавляем товар с доп опциями в корзину
			addToCartWithAddons(product_id, quantity, selectedAddons, $button);
		});

		function addToCartWithAddons(product_id, quantity, addons, $button) {
			var data = {
				action: 'add_to_cart_with_addons',
				product_id: product_id,
				quantity: quantity,
				addons: addons,
				security: '<?php echo wp_create_nonce("add-to-cart-with-addons"); ?>'
			};

			$.ajax({
				type: 'POST',
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: data,
				success: function(response) {
					if (response.success) {
						// Обновляем мини-корзину
						$(document.body).trigger('wc_fragment_refresh');
					}
				},
				error: function() {
					showMessage('Ошибка при добавлении в корзину', 'error');
				},
				complete: function() {
					// Разблокируем кнопку
					$button.prop('disabled', false).text('Добавить в корзину');
				}
			});
		}
	});
</script>
<?php do_action('woocommerce_after_single_product'); ?>