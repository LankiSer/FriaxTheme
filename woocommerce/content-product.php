<?php

defined('ABSPATH') || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
	return;
}

$thumbnail_id = $product->get_image_id();

$product_name = $product->get_name();

$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$price_html = $product->get_price_html();

$url = get_permalink($product->get_id());

?>
<a href="<?= $url; ?>" <?php wc_product_class('wc-product__card', $product); ?>>
	<div class="wc-product__card-container">
		<?php if (!empty($thumbnail_id)) { ?>
			<div class="wc-product__card-photo">
				<?= renderImage($thumbnail_id, null, null, '', $product_name); ?>
			</div>
		<?php } ?>
		<div class="wc-product__card-holder">
			<div class="wc-product__card-name">
				<?= $product_name; ?>
			</div>
			<div class="wc-product__card-actions">
				<div class="wc-product__card-price__holder">
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

				<?php if ($product->is_type('simple')) { ?>
					<button
						class="wc-product__card-btn cart-btn__v1 button add_to_cart_button ajax_add_to_cart"
						data-product_id="<?php echo $product->get_id(); ?>"
						data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
						data-quantity="1"
						aria-label="<?php echo esc_attr__('В корзину', 'woocommerce'); ?>"
						<?php if (!$product->is_in_stock()) { ?> disabled style="pointer-events:none" <?php } ?>>

						<div class="wc-product__card-btn__text">
							В корзину
						</div>

						<svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M21.1024 20.9134L19.5276 7.05512C19.4961 6.67717 19.1496 6.3622 18.7402 6.3622H16.4409V5.88976C16.4409 2.64567 13.7953 0 10.5512 0C7.30709 0 4.66142 2.64567 4.66142 5.88976V6.3622H2.3622C1.95276 6.3622 1.6378 6.67717 1.5748 7.05512L0 20.9134C0 20.9449 0 20.9764 0 21.0079C0 22.6457 1.35433 24 2.99213 24H18.1102C19.748 24 21.1024 22.6457 21.1024 21.0079C21.1024 20.9764 21.1024 20.9449 21.1024 20.9134ZM6.23622 5.88976C6.23622 3.49606 8.18898 1.5748 10.5512 1.5748C12.9134 1.5748 14.8661 3.49606 14.8661 5.88976V6.3622H6.23622V5.88976ZM18.1102 22.4252H2.99213C2.23622 22.4252 1.6063 21.7953 1.5748 21.0394L3.05512 7.93701H4.66142V11.9685C4.66142 12.4094 5.00787 12.7559 5.44882 12.7559C5.88976 12.7559 6.23622 12.4094 6.23622 11.9685V7.93701H14.8661V11.9685C14.8661 12.4094 15.2126 12.7559 15.6535 12.7559C16.0945 12.7559 16.4409 12.4094 16.4409 11.9685V7.93701H18.0472L19.5276 21.0394C19.4961 21.8268 18.8661 22.4252 18.1102 22.4252Z" fill="white" />
						</svg>
					</button>
				<?php } ?>

			</div>
		</div>
	</div>
</a>