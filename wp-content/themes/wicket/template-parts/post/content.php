<?php
/**
 * Template part for displaying single blog post details
 *
 * @package wicket
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-details-article'); ?>>

	<!-- Back Button -->
	<div class="blog-back-button">
		<?php
		$archive_link = get_post_type_archive_link('post');
		if ( ! $archive_link ) {
			$archive_link = home_url('/');
		}
		?>
		<a href="<?php echo esc_url($archive_link); ?>" class="back-link">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<span>ব্লগ ফিরুন যান</span>
		</a>
	</div>

	<!-- Featured Image -->
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-featured-image">
			<?php the_post_thumbnail('full'); ?>
		</div>
	<?php endif; ?>

	<!-- Blog Meta Information -->
	<div class="blog-meta-wrapper">
		<div class="blog-meta-left">
			<!-- Category Badge -->
			<?php
			$categories = get_the_category();
			if ( ! empty( $categories ) ) :
			?>
				<span class="blog-category-badge">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M2 4H14M2 8H14M2 12H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
					<?php echo esc_html( $categories[0]->name ); ?>
				</span>
			<?php endif; ?>

			<!-- Date -->
			<span class="blog-date">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.5"/>
					<path d="M5 1V4M11 1V4M2 6H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<?php echo get_the_date('d F, Y'); ?>
			</span>

			<!-- Blog Type Icon -->
			<span class="blog-type-icon">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3 4.5L8 2L13 4.5V11.5L8 14L3 11.5V4.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
				</svg>
				<?php _e('ব্লগ লিখ', 'wicket'); ?>
			</span>
		</div>

		<!-- Share Button -->
		<div class="blog-meta-right">
			<button class="blog-share-button" onclick="sharePost()">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4 12V17H16V12M8 6L10 4L12 6M10 4V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span><?php _e('শেয়ার করুন', 'wicket'); ?></span>
			</button>
		</div>
	</div>

	<!-- Blog Title -->
	<header class="blog-entry-header">
		<?php the_title( '<h1 class="blog-entry-title">', '</h1>' ); ?>
	</header>

	<!-- Blog Content -->
	<div class="blog-entry-content">
		<?php
		the_content( sprintf(
			wp_kses(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'wicket' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		) );

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wicket' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<!-- Blog Footer (Tags, etc.) -->
	<footer class="blog-entry-footer">
		<?php
		$tags_list = get_the_tag_list( '', ' ' );
		if ( $tags_list ) {
			printf( '<div class="blog-tags">' . esc_html__( 'Tagged: %1$s', 'wicket' ) . '</div>', $tags_list );
		}
		?>
	</footer>

</article>

<script>
function sharePost() {
	try {
		if (navigator.share) {
			navigator.share({
				title: '<?php echo esc_js(get_the_title()); ?>',
				url: '<?php echo esc_js(get_permalink()); ?>'
			}).catch(function(error) {
				console.error('Share failed:', error);
				// Fallback to clipboard if share fails
				copyToClipboard();
			});
		} else {
			// Fallback: Copy to clipboard
			copyToClipboard();
		}
	} catch (error) {
		console.error('Share error:', error);
		// Ultimate fallback: Simple alert with URL
		alert('<?php _e('শেয়ার করুন:', 'wicket'); ?> ' + '<?php echo esc_js(get_permalink()); ?>');
	}
}

function copyToClipboard() {
	const url = '<?php echo esc_js(get_permalink()); ?>';
	if (navigator.clipboard && navigator.clipboard.writeText) {
		navigator.clipboard.writeText(url).then(function() {
			alert('<?php _e('লিঙ্ক কপি করা হয়েছে!', 'wicket'); ?>');
		}).catch(function(error) {
			console.error('Clipboard copy failed:', error);
			// Fallback: Create temporary input
			fallbackCopyToClipboard(url);
		});
	} else {
		fallbackCopyToClipboard(url);
	}
}

function fallbackCopyToClipboard(text) {
	const textArea = document.createElement('textarea');
	textArea.value = text;
	textArea.style.position = 'fixed';
	textArea.style.left = '-999999px';
	document.body.appendChild(textArea);
	textArea.select();
	try {
		document.execCommand('copy');
		alert('<?php _e('লিঙ্ক কপি করা হয়েছে!', 'wicket'); ?>');
	} catch (error) {
		console.error('Fallback copy failed:', error);
		alert('<?php _e('শেয়ার করুন:', 'wicket'); ?> ' + text);
	}
	document.body.removeChild(textArea);
}
</script>
