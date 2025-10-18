<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Theme
 */

get_header();
$errorImg = wp_get_attachment_image_url(theme('error-img'),'full');
?>
	
	<main id="main" class="site-main error-page">
		<div class="container">
			<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
				<?php if(function_exists('bcn_display'))
				{
					bcn_display();
				}?>
			</div>
			<div class="error-wrapper">
				<div class="error-right-side">
					<div class="error-title">Произошла ошибка!</div>
					<div class="error-subtitle">Такой страницы не существует. Пожалуйста, вернитесь на главную.</div>
					<a href="/" class="btn">На главную</a>
				</div>
				<?php if(!empty($errorImg)) { ?>
					<div class="error-img"><img src="<?=$errorImg?>" alt=""></div>
				<?php } ?>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();
