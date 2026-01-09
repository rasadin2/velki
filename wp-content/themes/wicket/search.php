<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package wicket
 */

get_header();
?>
<div class="container">
	<div class="ssss">
		<div class="col-md-12">
			<section id="primary" class="wicket-content-area">
				<main id="main" class="wicket-site-main">

				<?php 
				/**
				 * wicket_before_main_content hook
				 */
				do_action( 'wicket_before_main_content' );

				if ( have_posts() ) : ?>

					<header class="page-header">
						<h1 class="page-title">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'wicket' ), '<span>' . get_search_query() . '</span>' );
							?>
						</h1>
					</header><!-- .page-header -->

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/page/content', 'search' );

					endwhile;

					the_posts_navigation();

				else :

					get_template_part( 'template-parts/page/content', 'none' );

				endif;

				/**
				 * wicket_after_main_content hook
				 */
				do_action( 'wicket_after_main_content' );
				?>

				</main><!-- #main -->
			</section><!-- #primary -->
		</div>
	</div>
</div>
	

<?php
get_footer();
