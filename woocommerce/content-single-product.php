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
$description = nl2br(wp_kses_post($product->get_description()));
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
$attrs = $product->get_attributes();
$project_gal = get_field('project_examples_gallery');

renderComponentFunc(getComponentFunc('swiper/index.php'));
$optionsSwiper = [
	'speed' => 300,
	'spaceBetween' => 5,
	'autoHeight' => false,
	'autoplay' => false,
];
$pgId = uniqid();
$pgMobileId = uniqid();
generateSwiper($pgId, $optionsSwiper);
generateSwiper($pgMobileId, $optionsSwiper);

$related_products = $product->get_related(8);
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
							<div class="wc-single__product-oneclick" data-modal data-src="#modal-callback">
								Купить в 1 клик
							</div>
						<?php } ?>
						<?php if ($stock_status == 'outofstock') { ?>
							<div class="wc-single__product-zakaz" data-modal data-src="#modal-callback">
								Под заказ
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
	<?php if (!empty($description) || !empty($attrs) || !empty($project_gal)) { ?>
		<div class="wc-single__product-additional">
			<!-- Десктопная версия (скрывается на мобильных) -->
			<div class="wc-single__product-additional__info desktop-only">
				<div class="wc-single__product-additional__info-header">
					<?php if (!empty($description)) { ?>
						<div class="wc-single__product-additional__info-header__item wc-single__product-additional__info-header__item-desc active" data-tab="desc">
							О ТОВАРЕ
						</div>
					<?php } ?>
					<?php if (!empty($attrs)) { ?>
						<div class="wc-single__product-additional__info-header__item wc-single__product-additional__info-header__item-attrs <?php echo empty($description) ? 'active' : ''; ?>" data-tab="attrs">
							ХАРАКТЕРИСТИКИ
						</div>
					<?php } ?>
				</div>
				<div class="wc-single__product-additional__info-content">
					<?php if (!empty($description)) { ?>
						<div class="wc-single__product-additional__info-content__item wc-single__product-additional__info-content__item-desc active" data-tab-content="desc">
							<?= $description; ?>
						</div>
					<?php } ?>
					<?php if (!empty($attrs)) { ?>
						<div class="wc-single__product-additional__info-content__item wc-single__product-additional__info-content__item-attrs <?php echo empty($description) ? 'active' : ''; ?>" data-tab-content="attrs">
							<div class="single-product__attributes">
								<?php
								$attributes_count = 0;
								$total_attributes = count($attrs);
								foreach ($attrs as $key => $attribute) :
									$attribute_name = $attribute->get_name();
									$attribute_label = wc_attribute_label($attribute_name);
									$attribute_values = $product->get_attribute($attribute_name);

									if ($attribute_values == 'Да' || $attribute_values == 'да') {
										$attribute_values = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 6.99984L9 18.9998L3.5 13.4998L4.91 12.0898L9 16.1698L19.59 5.58984L21 6.99984Z" fill="white" />
                                </svg>';
									}

									if (!empty($attribute_values)) :
										$attributes_count++;
										$is_hidden = $attributes_count > 8;
								?>
										<div class="wc-product__card-attr <?php echo $is_hidden ? 'attr-hidden' : ''; ?>" <?php echo $is_hidden ? 'style="display: none;"' : ''; ?>>
											<div class="wc-product__card-attr__name single-product__attribute-group__attr"><?php echo esc_html($attribute_label); ?>:</div>
											<div class="wc-product__card-attr__value single-product__attribute-group__attr"><?php echo $attribute_values; ?></div>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>

								<?php if ($total_attributes > 8) : ?>
									<button type="button" class="show-more-attributes" data-text-more="+ БОЛЬШЕ ХАРАКТЕРИСТИК" data-text-less="- СКРЫТЬ">
										+ БОЛЬШЕ ХАРАКТЕРИСТИК
									</button>
								<?php endif; ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

			<!-- Мобильная версия (скрывается на десктопе) -->
			<div class="wc-single__product-additional__info mobile-only">
				<div class="wc-single__product-additional__mobile-accordion">
					<?php if (!empty($description)) { ?>
						<div class="mobile-accordion-item">
							<div class="mobile-accordion-header" data-tab="desc">
								О ТОВАРЕ
								<span class="mobile-accordion-arrow">
									<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M8.51471 9.683L12.6812 13.8075L16.8476 9.683C17.2664 9.26843 17.9429 9.26843 18.3617 9.683C18.7805 10.0976 18.7805 10.7673 18.3617 11.1819L13.4328 16.0611C13.014 16.4757 12.3375 16.4757 11.9187 16.0611L6.98988 11.1819C6.57108 10.7673 6.57108 10.0976 6.98988 9.683C7.40867 9.27906 8.09592 9.26843 8.51471 9.683Z" fill="white" />
									</svg>
								</span>
							</div>
							<div class="mobile-accordion-content" data-tab-content="desc">
								<?= $description; ?>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($attrs)) { ?>
						<div class="mobile-accordion-item">
							<div class="mobile-accordion-header" data-tab="attrs">
								ХАРАКТЕРИСТИКИ
								<span class="mobile-accordion-arrow">
									<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M8.51471 9.683L12.6812 13.8075L16.8476 9.683C17.2664 9.26843 17.9429 9.26843 18.3617 9.683C18.7805 10.0976 18.7805 10.7673 18.3617 11.1819L13.4328 16.0611C13.014 16.4757 12.3375 16.4757 11.9187 16.0611L6.98988 11.1819C6.57108 10.7673 6.57108 10.0976 6.98988 9.683C7.40867 9.27906 8.09592 9.26843 8.51471 9.683Z" fill="white" />
									</svg>
								</span>
							</div>
							<div class="mobile-accordion-content" data-tab-content="attrs">
								<div class="single-product__attributes">
									<?php
									$attributes_count = 0;
									$total_attributes = count($attrs);
									foreach ($attrs as $key => $attribute) :
										$attribute_name = $attribute->get_name();
										$attribute_label = wc_attribute_label($attribute_name);
										$attribute_values = $product->get_attribute($attribute_name);

										if ($attribute_values == 'Да' || $attribute_values == 'да') {
											$attribute_values = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 6.99984L9 18.9998L3.5 13.4998L4.91 12.0898L9 16.1698L19.59 5.58984L21 6.99984Z" fill="white" />
                                    </svg>';
										}

										if (!empty($attribute_values)) :
											$attributes_count++;
											$is_hidden = $attributes_count > 8;
									?>
											<div class="wc-product__card-attr <?php echo $is_hidden ? 'attr-hidden' : ''; ?>" <?php echo $is_hidden ? 'style="display: none;"' : ''; ?>>
												<div class="wc-product__card-attr__name single-product__attribute-group__attr"><?php echo esc_html($attribute_label); ?>:</div>
												<div class="wc-product__card-attr__value single-product__attribute-group__attr"><?php echo $attribute_values; ?></div>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>

									<?php if ($total_attributes > 8) : ?>
										<button type="button" class="show-more-attributes" data-text-more="+ БОЛЬШЕ ХАРАКТЕРИСТИК" data-text-less="- СКРЫТЬ">
											+ БОЛЬШЕ ХАРАКТЕРИСТИК
										</button>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($project_gal)) { ?>
						<div class="mobile-accordion-item">
							<div class="mobile-accordion-header" data-tab="examples">
								ПРИМЕРЫ РЕАЛИЗАЦИИ
								<span class="mobile-accordion-arrow">
									<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M8.51471 9.683L12.6812 13.8075L16.8476 9.683C17.2664 9.26843 17.9429 9.26843 18.3617 9.683C18.7805 10.0976 18.7805 10.7673 18.3617 11.1819L13.4328 16.0611C13.014 16.4757 12.3375 16.4757 11.9187 16.0611L6.98988 11.1819C6.57108 10.7673 6.57108 10.0976 6.98988 9.683C7.40867 9.27906 8.09592 9.26843 8.51471 9.683Z" fill="white" />
									</svg>
								</span>
							</div>
							<div class="mobile-accordion-content" data-tab-content="examples">
								<div class="wc-single__product-project__gal">
									<div class="wc-single__product-project__gal-swiper__holder swiper-holder">
										<div class="swiper swiper<?= $pgMobileId; ?>">
											<div class="swiper-wrapper">
												<?php foreach ($project_gal as $pg) { ?>
													<div class="swiper-slide" data-fancybox="pgGal" href="<?= $pg['url']; ?>">
														<?= renderImage($pg['ID'], null, null, ''); ?>
													</div>
												<?php } ?>
											</div>
										</div>
										<div class="wc-single__product-project__gal-swiper__pagination swiper-pagination<?= $pgMobileId; ?>"></div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

			<!-- Десктопная версия примеров реализации (отдельный блок) -->
			<?php if (!empty($project_gal)) { ?>
				<div class="wc-single__product-project__gal desktop-only">
					<div class="wc-single__product-project__gal-title">
						ПРИМЕРЫ РЕАЛИЗАЦИИ
					</div>
					<div class="wc-single__product-project__gal-swiper__holder swiper-holder">
						<div class="swiper swiper<?= $pgId; ?>">
							<div class="swiper-wrapper">
								<?php foreach ($project_gal as $pg) { ?>
									<div class="swiper-slide" data-fancybox="pgGal" href="<?= $pg['url']; ?>">
										<?= renderImage($pg['ID'], null, null, ''); ?>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="wc-single__product-project__gal-swiper__pagination swiper-pagination<?= $pgId; ?>"></div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if (!empty($related_products)) { ?>
		<div class="wc-single__product-related">
			<div class="wc-single__product-related__title">
				СМОТРИТЕ ТАКЖЕ
			</div>
			<div class="wc-single__product-related__items">
				<?php foreach ($related_products as $related_product_id) : ?>
					<?php
					// Get the product object for the related product
					$post_object = get_post($related_product_id);

					// Check if the post object is valid
					if ($post_object) {
						setup_postdata($GLOBALS['post'] = &$post_object); // Set up post data for the related product

						// Load the product template part
						wc_get_template_part('content', 'product');
					}
					?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php } ?>
</div>
<script>
	jQuery(document).ready(function($) {
		// Функция для табов
		function initTabs() {
			$('.wc-single__product-additional__info-header__item').on('click', function() {
				var tabId = $(this).data('tab');

				// Убираем активный класс у всех заголовков и контента
				$('.wc-single__product-additional__info-header__item').removeClass('active');
				$('.wc-single__product-additional__info-content__item').removeClass('active');

				// Добавляем активный класс текущему заголовку
				$(this).addClass('active');

				// Показываем соответствующий контент
				$('.wc-single__product-additional__info-content__item[data-tab-content="' + tabId + '"]').addClass('active');
			});
		}

		function initMobileAccordion() {
			$('.mobile-accordion-header').on('click', function() {
				var $header = $(this);
				var $content = $header.next('.mobile-accordion-content');
				var $parent = $header.closest('.mobile-accordion-item');

				// Если уже активен, закрываем
				if ($header.hasClass('active')) {
					$header.removeClass('active');
					$content.removeClass('active');
				} else {
					// Закрываем все остальные
					$('.mobile-accordion-header').removeClass('active');
					$('.mobile-accordion-content').removeClass('active');

					// Открываем текущий
					$header.addClass('active');
					$content.addClass('active');
				}
			});
		}

		function formatPrice(price) {
			return new Intl.NumberFormat('ru-RU', {
				minimumFractionDigits: 0,
				maximumFractionDigits: 0,
			}).format(price) + ' ₽';
		}

		function updatePrice() {
			var basePrice = parseFloat($('#price_holder_<?php echo $uniqId; ?>').data('base-price')) || 0;
			var regularPrice = parseFloat($('#price_holder_<?php echo $uniqId; ?>').data('regular-price')) || 0;
			var isOnSale = $('#price_holder_<?php echo $uniqId; ?>').data('is-on-sale') === 'yes';

			var addonsTotal = 0;
			$('input[name="product_addon[]"]:checked').each(function() {
				addonsTotal += parseFloat($(this).val()) || 0;
			});

			var totalPrice = basePrice + addonsTotal;
			var totalRegularPrice = regularPrice + addonsTotal;

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

		// Функция для показа/скрытия характеристик
		function initAttributesToggle() {
			$('.show-more-attributes').on('click', function() {
				var $button = $(this);
				var $hiddenAttrs = $button.closest('.single-product__attributes').find('.attr-hidden');

				if ($hiddenAttrs.is(':hidden')) {
					$hiddenAttrs.show();
					$button.text($button.data('text-less'));
				} else {
					$hiddenAttrs.hide();
					$button.text($button.data('text-more'));
				}
			});
		}

		$('input[name="product_addon[]"]').on('change', function() {
			updatePrice();
		});

		$('.add_to_cart_with_addons').on('click', function(e) {
			e.preventDefault();

			var $button = $(this);
			var product_id = $button.data('product_id');
			var quantity = $button.data('quantity') || 1;

			var selectedAddons = [];
			$('input[name="product_addon[]"]:checked').each(function() {
				selectedAddons.push({
					id: $(this).data('addon-id'),
					name: $(this).data('addon-name'),
					price: $(this).val()
				});
			});

			$button.prop('disabled', true).text('Добавляем...');

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
						$(document.body).trigger('wc_fragment_refresh');
						$(document.body).trigger('added_to_cart');
					}
				},
				error: function() {
					showMessage('Ошибка при добавлении в корзину', 'error');
				},
				complete: function() {
					$button.prop('disabled', false).text('Добавить в корзину');
				}
			});
		}

		// Инициализация табов
		initTabs();

		initMobileAccordion();

		// Инициализация переключения характеристик
		initAttributesToggle();
	});
</script>
<?php do_action('woocommerce_after_single_product'); ?>