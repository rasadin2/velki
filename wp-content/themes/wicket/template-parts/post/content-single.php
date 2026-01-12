<?php
/**
 * Template part for displaying single blog post details
 * Matches Bengali design screenshot
 *
 * @package wicket
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-details-article'); ?>>

	<!-- Back Button - Simple style matching screenshot -->
	<div class="blog-back-button">
		<?php
		$archive_link = get_post_type_archive_link('post');
		if ( ! $archive_link ) {
			$archive_link = home_url('/');
		}
		?>
		<a href="<?php echo esc_url($archive_link); ?>" class="back-link">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<span>সব ব্লগ দেখুন</span>
		</a>
	</div>

	<!-- Featured Image -->
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-featured-image">
			<?php the_post_thumbnail('full'); ?>
		</div>
	<?php endif; ?>

	<!-- Meta Information - Inline layout with author -->
	<div class="blog-meta-info">
		<div class="blog-meta-left">
			<!-- Category Badge -->
			<?php
			$categories = get_the_category();
			if ( ! empty( $categories ) ) :
			?>
				<span class="meta-category">
					<svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M2 4H14M2 8H14M2 12H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
					<?php echo esc_html( $categories[0]->name ); ?>
				</span>
			<?php endif; ?>

			<!-- Date -->
			<span class="meta-date">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.5"/>
					<path d="M5 1V4M11 1V4M2 6H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<?php echo get_the_date('d F, Y'); ?>
			</span>

			<!-- Author -->
			<span class="meta-author">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.5"/>
					<path d="M2 14C2 11.2 4.7 9 8 9C11.3 9 14 11.2 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<?php echo get_the_author(); ?>
			</span>
		</div>

		<!-- Share Button with Dropdown -->
		<div class="blog-share-wrapper">
			<button class="blog-share-btn" onclick="toggleShareMenu(event)">
				<svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4 12V17H16V12M8 6L10 4L12 6M10 4V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span><?php _e('শেয়ার করুন', 'wicket'); ?></span>
			</button>

			<!-- Social Share Menu -->
			<div class="share-menu" id="shareMenu">
				<button class="share-option share-facebook" onclick="shareToFacebook()">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
					</svg>
					<span>Facebook</span>
				</button>
				<button class="share-option share-twitter" onclick="shareToTwitter()">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
					</svg>
					<span>X (Twitter)</span>
				</button>
			</div>
		</div>
	</div>

	<!-- Blog Title -->
	<header class="blog-header">
		<?php the_title( '<h1 class="blog-title">', '</h1>' ); ?>
	</header>

	<!-- Blog Content -->
	<div class="blog-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wicket' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<!-- Blog Footer with Share Section matching screenshot -->
	<footer class="blog-footer">
		<div class="blog-share-section">
			<span class="share-text"><?php _e('এই আর্টিকেলটি শেয়ার করুন', 'wicket'); ?></span>
			<button class="blog-share-btn-footer" onclick="sharePost()">
				<svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4 12V17H16V12M8 6L10 4L12 6M10 4V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span><?php _e('শেয়ার করুন', 'wicket'); ?></span>
			</button>
		</div>

		<!-- Bottom Navigation matching screenshot -->
		<div class="blog-bottom-nav">
			<a href="<?php echo esc_url($archive_link); ?>" class="view-all-blogs">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span><?php _e('সব ব্লগ দেখুন', 'wicket'); ?></span>
			</a>
		</div>
	</footer>

</article>

<script>
// Toggle share menu dropdown
function toggleShareMenu(event) {
	event.stopPropagation();
	const menu = document.getElementById('shareMenu');
	if (menu) {
		menu.classList.toggle('active');
	}
}

// Share to Facebook
function shareToFacebook() {
	const url = encodeURIComponent('<?php echo esc_js(get_permalink()); ?>');
	window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '_blank', 'width=600,height=400');
	closeShareMenu();
}

// Share to X (Twitter)
function shareToTwitter() {
	const url = encodeURIComponent('<?php echo esc_js(get_permalink()); ?>');
	const title = encodeURIComponent('<?php echo esc_js(get_the_title()); ?>');
	window.open('https://twitter.com/intent/tweet?url=' + url + '&text=' + title, '_blank', 'width=600,height=400');
	closeShareMenu();
}

// Close share menu
function closeShareMenu() {
	const menu = document.getElementById('shareMenu');
	if (menu) {
		menu.classList.remove('active');
	}
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
	const shareWrapper = document.querySelector('.blog-share-wrapper');
	const shareMenu = document.getElementById('shareMenu');
	if (shareMenu && shareWrapper && !shareWrapper.contains(event.target)) {
		shareMenu.classList.remove('active');
	}
});

// Footer share button - using same Web Share API or fallback
function sharePost() {
	try {
		if (navigator.share) {
			navigator.share({
				title: '<?php echo esc_js(get_the_title()); ?>',
				url: '<?php echo esc_js(get_permalink()); ?>'
			}).catch(function(error) {
				copyToClipboard();
			});
		} else {
			copyToClipboard();
		}
	} catch (error) {
		console.error('Share error:', error);
		alert('<?php _e('শেয়ার করুন:', 'wicket'); ?> ' + '<?php echo esc_js(get_permalink()); ?>');
	}
}

function copyToClipboard() {
	const url = '<?php echo esc_js(get_permalink()); ?>';
	if (navigator.clipboard && navigator.clipboard.writeText) {
		navigator.clipboard.writeText(url).then(function() {
			alert('<?php _e('লিঙ্ক কপি করা হয়েছে!', 'wicket'); ?>');
		}).catch(function(error) {
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
