<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Theme
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<div class="checkout-title__holder">
			<h1 class="page-title">
				ДЕТАЛИ ЗАКАЗА
			</h1>
			<a href="/shop/" class="checkout-go__back">Вернуться в магазин</a>
		</div>
		<div class="content">
			<?php the_content(); ?>
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();
