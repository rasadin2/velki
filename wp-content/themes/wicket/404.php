<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package wicket
 */

get_header();
?>
 <div class="entry-header inner-common-header">
   <div class="container">
   <h1>404</h1>
   </div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<?php 
						/**
						 * wicket_before_main_content hook
						 */
						do_action( 'wicket_before_main_content' );
					?>
					 <section class="error-404 not-found">
                        <header class="page-header">
                            <h2 class="page-title">Oops! That page can’t be found.</h2>
                        </header><!-- .page-header -->

                        <div class="page-content">
                            <p>It looks like nothing was found at this location. Maybe try one of the links below?</p>

                            <!--<form role="search" method="get" class="search-form" action="https://www.leoberkeley.com/">
				<label>
					<span class="screen-reader-text">Search for:</span>
					<input type="search" class="search-field" placeholder="Search &hellip;" value="" name="s" />
				</label>
				<input type="submit" class="search-submit" value="Search" />
			</form>-->
                        </div><!-- .page-content -->
                    </section>

					<?php  
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
