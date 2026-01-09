<header id="masthead" class="wicket-header wicket-inline-menu">
		<nav id="site-navigation" class="wicket-navbar">
		    <div class="container">
				<div class="wicket-menu-wrap">
					<div class="wicket-brand-wrap">
						<?php  
							/**
							 * wicket_before_logo hook
							 */
							do_action( 'wicket_before_logo' );
						?>
						<a class="wicket-navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php wicket_custom_logo(); ?>
						</a>
						<?php  
							/**
							 * wicket_after_logo hook
							 */
							do_action( 'wicket_after_logo' );
						?>
						<span class="wicket-navbar-toggler js-show-nav">
							<i class="fas fa-bars"></i>
						</span>
					</div>
					<?php
						if( has_nav_menu( 'primary' ) ) :
							wp_nav_menu( array(
								'theme_location'	=> 'primary',
								'container'			=> false,
								'menu_class'		=> 'wicket-main-menu wicket-inline-menu',
								'menu_id'			=> false,
							) );
						endif;
					?>
					<!--<div class="right-button"><a href="<?php echo esc_url( home_url( '/জরুরি-যোগাযোগ/' ) ); ?>">জরুরি যোগাযোগ</a></div>-->
					 <div class="search-box"><?php echo do_shortcode( '[wpdreams_ajaxsearchlite]'); ?></div>
				</div>
				
		    </div>
		</nav>
	</header><!-- #masthead -->