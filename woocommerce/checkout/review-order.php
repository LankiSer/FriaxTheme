<?php
defined('ABSPATH') || exit;
?>
<div class="custom-checkout-review-order">
	<h2 class="custom-checkout-review-order__title">ВАШ ЗАКАЗ</h2>
	<div class="custom-checkout-review-order__products">
		<?php
		$cart_items = WC()->cart->get_cart();

		if (!empty($cart_items)) :
			foreach ($cart_items as $cart_item_key => $cart_item) {
				$product = $cart_item['data'];
				$product_name = $product->get_name();
				$quantity = $cart_item['quantity'];
				$price = WC()->cart->get_product_price($product);
				$line_total = WC()->cart->get_product_subtotal($product, $cart_item['quantity']);
				// Получаем мета-данные (доп опции) для этого товара в корзине
				$item_data = $cart_item['addons'] ?? array();
		?>
				<div class="custom-checkout-review-order__product">
					<div class="product-name"><?php echo esc_html($product_name); ?> <span>×<?php echo esc_html($quantity); ?></span></div>
					<?php if (!empty($item_data)) : ?>
						<div class="product-addons">
							<?php foreach ($item_data as $addon) : ?>
								<div class="product-addon">
									<span class="addon-name"><?php echo esc_html($addon['name']); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
		<?php
			}
		endif;
		?>
	</div>

	<div class="custom-checkout-review-order__shipping">
		<div class="custom-checkout-review-order__label">Способ доставки:</div>
		<div class="custom-checkout-review-order__value">
			<?php
			$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
			if (!empty($chosen_shipping_methods)) {
				$shipping_packages = WC()->shipping->get_packages();
				foreach ($shipping_packages as $i => $package) {
					$chosen_method = $chosen_shipping_methods[$i];
					if (isset($package['rates'][$chosen_method])) {
						echo esc_html($package['rates'][$chosen_method]->get_label());
						break;
					}
				}
			} else {
				echo 'Доставка';
			}
			?>
		</div>
	</div>

	<div class="custom-checkout-review-order__payment">
		<div class="custom-checkout-review-order__label">Способ оплаты:</div>
		<div class="custom-checkout-review-order__value">
			<?php
			$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
			$chosen_payment_method = WC()->session->get('chosen_payment_method');

			if ($chosen_payment_method && isset($available_gateways[$chosen_payment_method])) {
				echo esc_html($available_gateways[$chosen_payment_method]->get_title());
			} else {
				echo 'По счету менеджера';
			}
			?>
		</div>
	</div>

	<div class="custom-checkout-review-order__total">
		<div class="custom-checkout-review-order__label">Сумма:</div>
		<div class="custom-checkout-review-order__total-value">
			<?php wc_cart_totals_order_total_html(); ?>
		</div>
	</div>

	<?php
	$order_button_text = apply_filters('woocommerce_order_button_text', __('Оставить заявку', 'woocommerce'));
	echo '<div class="order-btn">
    <button type="submit" class="button alt btn btn-v3 medium" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>
  </div>';
	?>
</div>