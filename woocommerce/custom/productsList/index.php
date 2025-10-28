<?php
defined('ABSPATH') || exit;

if (woocommerce_product_loop()) {
?>

    <div class="products-holder__loop">
    <?php
    woocommerce_product_loop_start();
    if (wc_get_loop_prop('total')) {

        while (have_posts()) {
            the_post();

            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action('woocommerce_shop_loop');

            wc_get_template_part('content', 'product');
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action('woocommerce_after_shop_loop');
    if (is_product_category()) {
        do_action('woocommerce_after_main_content');
    }
} else {
    /**
     * Hook: woocommerce_no_products_found.
     *
     * @hooked wc_no_products_found - 10
     */
    do_action('woocommerce_no_products_found');
}
    ?>