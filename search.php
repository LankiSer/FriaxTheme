<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Dopog
 */

get_header();
?>

	<main id="main" class="site-main search-page">
		<div class="container">
			<?php if ( have_posts() ) : ?>

				<h1 class="page-title">
					Результаты поиска: <span><?=get_search_query();?></span>
				</h1>

				<div class="search__holder">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post(); 

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						?>
						<a href="<?=get_permalink()?>?search=true" class="item">
							<div class="item__name"><?php the_title()?></div>
							<div class="item__array">
								<svg width="4" height="6" viewBox="0 0 4 6" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M3.89139 2.76588L0.861476 0.0958529C0.791398 0.0340485 0.697849 0 0.598101 0C0.498353 0 0.404805 0.0340485 0.334726 0.0958529L0.111594 0.292437C-0.0335999 0.420533 -0.0335999 0.628727 0.111594 0.756628L2.65589 2.99876L0.108771 5.24337C0.0386925 5.30518 0 5.38757 0 5.47542C0 5.56337 0.0386925 5.64576 0.108771 5.70761L0.331903 5.90415C0.402037 5.96595 0.49553 6 0.595278 6C0.695026 6 0.788575 5.96595 0.858653 5.90415L3.89139 3.23168C3.96164 3.16968 4.00022 3.0869 4 2.9989C4.00022 2.91056 3.96164 2.82783 3.89139 2.76588Z" fill="#F5F5F5"/>
								</svg>
							</div>
						</a>
					<?php endwhile;

					// the_posts_navigation();
					?>
				</div>
					<?php
				else : ?>
				<div class="search__holder">
					<h1>Результатов не найдено</h1>
				</div>
			<?php
			endif;
			?>
		</div>


	</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
