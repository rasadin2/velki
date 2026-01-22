<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wicket
 */

?>

	</div><!-- #content -->
	<?php 
		/**
		 * wicket_before_footer hook
		 */
		do_action( 'wicket_before_footer' );
	?>

	<footer class="wicket-footer">
		<!-- Widgets -->
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-sm-6 footer-colume-1">
					<?php dynamic_sidebar( 'footer-widget-1' ); ?>
				</div>
				<div class="col-md-2 col-sm-6 footer-colume-2">
					<?php dynamic_sidebar( 'footer-widget-2' ); ?>
				</div>
				<div class="col-md-3 col-sm-6 footer-colume-3">
					<?php dynamic_sidebar( 'footer-widget-3' ); ?>
				</div>
				<div class="col-md-4 col-sm-6 footer-colume-4">
					<div class="new-account-widgets"><?php dynamic_sidebar( 'footer-widget-4' ); ?></div>
				</div>
			</div>
		</div>
		<!-- Copyright -->
		<div class="container">
		<div class="border-bottom-copy">
			<div class="row">
				<div class="col-md-12 footer-copyright-widgets1">
					<?php dynamic_sidebar( 'copyright-widget-1' ); ?>
				</div>
				<div class="col-md-6 footer-copyright-widgets2">
					<?php dynamic_sidebar( 'copyright-widget-2' ); ?>
				</div>
			</div>
			</div>
		</div>
		<!--
		<div class="foo-bottom-border">
		<div class="container">
		<div class="bottom-to-top"><a id="up"></a></div>
			<div class="row">
				<div class="col-md-12 footer-copyright-widgets2">
					<?php dynamic_sidebar( 'copyright-widget-2' ); ?>
				</div>
			</div>
		</div>
		</div>
		-->
		
	</footer>

	<?php  
		/**
		 * wicket_after_footer hook
		 */
		do_action( 'wicket_after_footer' );
	?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
