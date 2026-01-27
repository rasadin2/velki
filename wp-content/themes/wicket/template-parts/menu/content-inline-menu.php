<div class="header-bg-box">
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
					
				</div>
				<div class="head-right-button" bis_skin_checked="1">
				  <ul>
					  <li class="login-btn"><a href="#"><span>Login</span> <svg xmlns="http://www.w3.org/2000/svg" width="10" height="11"><path d="M5.71 7.706l1.432-1.604H1.778V4.898h5.39L5.71 3.294l.781-.86L9.278 5.5 6.49 8.565l-.78-.86zM1.12 0C.825 0 .564.124.339.372a1.24 1.24 0 0 0-.339.86v8.536c0 .325.113.611.339.86.225.248.486.372.78.372H8.88c.295 0 .556-.124.781-.372a1.24 1.24 0 0 0 .339-.86V7.333H8.88v2.435H1.12V1.232h7.76v2.435H10V1.232a1.24 1.24 0 0 0-.339-.86C9.436.124 9.175 0 8.881 0H1.12z" fill="#FFF" fill-rule="evenodd"/></svg></a></li>
					  <li class="nibondon-btn"><a href="#">Agent List</a></li>
				 </ul>
		     </div>
			 
		    </div>
		</nav>
		
	<!--
		<div class="menu-wrap">
			<div class="full-wrap">
			<ul  class="menu">
			<li><a  href="#">Home</a></li>
			<li><a href="#">In-Play</a></li>
			<li><a href="#">Multi Markets</a></li>
			<li><a  href="#">WPL Winner</a></li>
			<li><a href="#">BBL Winner</a></li>
			<li><a href="#"><span id="tagLive" class="tag-live"><strong></strong>7</span> Cricket </a></li>
			<li><a href="#"><span id="tagLive" class="tag-live"><strong></strong>5</span> Soccer </a></li>
			<li><a href="#"><span id="tagLive" class="tag-live"><strong></strong>9</span> Tennis </a></li>
			<li><a href="#">Horse Racing</a></li>
			<li><a href="#">GreyHound Racing</a></li>
			<li><a class="casino tag-new" href="#">Casino</a></li>
			</ul>
			<ul class="setting-wrap">
			<li class="time_zone">Time Zone :<span class="gmt-time">GMT+ 5:30</span></li>
			<li><a href="#" class="one_click">One Click Bet</a></li>
			<li><a class="setting" style="cursor: pointer;">Setting <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"><path d="M6 8.106c.385 0 .745-.096 1.081-.289.336-.192.602-.45.8-.771a2.002 2.002 0 0 0 0-2.099 2.19 2.19 0 0 0-.8-.779A2.139 2.139 0 0 0 6 3.88c-.385 0-.743.096-1.074.288-.33.193-.594.452-.792.78a2.002 2.002 0 0 0 0 2.098c.198.322.462.579.792.771.331.193.689.289 1.074.289zm4.605-1.515l1.288.981c.06.048.094.11.104.188a.333.333 0 0 1-.044.216l-1.244 2.077a.269.269 0 0 1-.156.13.336.336 0 0 1-.214-.015l-1.526-.591c-.394.279-.745.476-1.05.591L7.54 11.74a.364.364 0 0 1-.111.188.272.272 0 0 1-.185.072H4.756a.29.29 0 0 1-.281-.26l-.237-1.572A3.752 3.752 0 0 1 3.2 9.577l-1.54.591c-.158.068-.28.03-.37-.115L.047 7.976a.333.333 0 0 1-.044-.216.278.278 0 0 1 .104-.188l1.303-.98A4.395 4.395 0 0 1 1.38 6c0-.26.01-.457.03-.591L.107 4.428a.278.278 0 0 1-.104-.188.333.333 0 0 1 .044-.216l1.244-2.077c.09-.144.212-.183.37-.115l1.54.591c.356-.26.701-.457 1.037-.591L4.475.26A.29.29 0 0 1 4.756 0h2.488c.069 0 .13.024.185.072.054.048.091.11.111.188l.222 1.572a3.87 3.87 0 0 1 1.051.591l1.526-.591a.336.336 0 0 1 .214-.015c.064.02.116.063.156.13l1.244 2.077c.04.067.054.14.044.216a.278.278 0 0 1-.104.188l-1.288.98c.02.135.03.332.03.592 0 .26-.01.457-.03.591z" fill="#FFF" fill-rule="evenodd"/></svg></a></li>
			</ul>
			</div>
     </div>
	-->
	   
	</header><!-- #masthead -->
	</div>