<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>

		<div class="col2-set" id="customer_details">

			<div class="col-1">
				<?php do_action('woocommerce_checkout_billing'); ?>
			</div>

			<div class="col-2">
				<?php do_action('woocommerce_checkout_shipping'); ?>
			</div>
			<div class="col-2__additional">
				<div class="need__shipping">
					<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

						<?php do_action('woocommerce_review_order_before_shipping'); ?>

						<?php wc_cart_totals_shipping_html(); ?>

						<?php do_action('woocommerce_review_order_after_shipping'); ?>

					<?php endif; ?>
				</div>
				<?php do_action('woocommerce_checkout_after_customer_details'); ?>
			</div>
		</div>


	<?php endif; ?>

	<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

	<?php /*
		<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
	*/ ?>

	<?php do_action('woocommerce_checkout_before_order_review'); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action('woocommerce_checkout_order_review'); ?>
	</div>

	<?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<script>
	jQuery(document).ready(function($) {
		// Функция для обновления review order
		function updateReviewOrder() {
			// Показываем индикатор загрузки
			$('#order_review').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			// Получаем данные формы
			var formData = $('form.checkout').serialize();

			// AJAX запрос для обновления review order
			$.ajax({
				type: 'POST',
				url: custom_checkout_params.ajax_url,
				data: {
					action: 'update_order_review',
					security: custom_checkout_params.update_order_review_nonce,
					post_data: formData
				},
				success: function(response) {
					// Обновляем блок review order
					if (response && response !== 'Security check failed') {
						$('#order_review').html(response);
						// Инициализируем новые элементы
						$(document.body).trigger('updated_checkout');
					} else {
						console.error('Security error');
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX error:', error);
				},
				complete: function() {
					// Снимаем блокировку
					$('#order_review').unblock();
				}
			});
		}

		// Обработчик изменения способа доставки
		$(document).on('change', 'input[name^="shipping_method"]', function() {
			updateReviewOrder();
		});

		// Обработчик изменения способа оплаты
		$(document).on('change', 'input[name="payment_method"]', function() {
			updateReviewOrder();
		});

		// Обработчик изменения полей доставки с дебаунсом
		let shippingTimeout;
		$(document).on('change input', 'select#shipping_country, select#shipping_state, input#shipping_postcode', function() {
			clearTimeout(shippingTimeout);
			shippingTimeout = setTimeout(updateReviewOrder, 1000);
		});
	});
</script>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>