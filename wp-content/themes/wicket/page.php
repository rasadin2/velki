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
 * @package wicket
 */

get_header();
?>
  <div class="entry-header inner-common-header">
        <div class="container">
            <?php
            /**
             * wicket_before_entry_title hook
             */
            do_action( 'wicket_before_entry_title' );

            the_title( '<h1 class="entry-title">', '</h1>' );

            /**
             * wicket_after_entry_title hook
             */
            do_action( 'wicket_after_entry_title' );
            ?>
        </div>
    </div><!-- .entry-header -->
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div id="primary" class="wicket-content-area">
				<main id="main" class="wicket-site-main">

					<?php

					/**
					 * wicket_before_main_content hook
					 */
					do_action( 'wicket_before_main_content' );

					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/page/content', 'page' );
					
					endwhile; // End of the loop.

					/**
					 * wicket_after_main_content hook
					 */
					do_action( 'wicket_after_main_content' );
					?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
	</div>
</div>

<?php
get_footer();
