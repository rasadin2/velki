<?php
/**
 * Wicket functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wicket
 */

if ( ! function_exists( 'wicket_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wicket_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Wicket, use a find and replace
		 * to change 'wicket' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wicket', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'wicket' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wicket_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		/**
		 * Add Post Format Support
		 */
		add_theme_support( 'post-formats', array( 
			'audio',
			'aside', 
			'gallery', 
			'image',
			'link',
			'video',
		) );

		/**
		 * WooCommerce Support
		 */
		add_theme_support( 'woocommerce' );
	}
endif;
add_action( 'after_setup_theme', 'wicket_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wicket_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'wicket_content_width', 640 );
}
add_action( 'after_setup_theme', 'wicket_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wicket_widgets_init() {
	
// 	register_sidebar( array(
// 		'name'          => esc_html__( 'পুরস্কার:', 'wicket' ),
// 		'id'            => 'gift-1',
// 		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
// 		'before_widget' => '<section id="%1$s" class="widget %2$s">',
// 		'after_widget'  => '</section>',
// 		'before_title'  => '<h2 class="widget-title">',
// 		'after_title'   => '</h2>',
// 	) );
	
// 	register_sidebar( array(
// 		'name'          => esc_html__( 'অংশগ্রহণের যোগ্যতা:', 'wicket' ),
// 		'id'            => 'qu-1',
// 		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
// 		'before_widget' => '<section id="%1$s" class="widget %2$s">',
// 		'after_widget'  => '</section>',
// 		'before_title'  => '<h2 class="widget-title">',
// 		'after_title'   => '</h2>',
// 	) );
	
	
// 		register_sidebar( array(
// 		'name'          => esc_html__( 'কুইজের কাঠামো:', 'wicket' ),
// 		'id'            => 'qu-ka',
// 		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
// 		'before_widget' => '<section id="%1$s" class="widget %2$s">',
// 		'after_widget'  => '</section>',
// 		'before_title'  => '<h2 class="widget-title">',
// 		'after_title'   => '</h2>',
// 	) );
	
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wicket' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	// Footer Widget 1
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 1', 'wicket' ),
		'id'            => 'footer-widget-1',
		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
		'before_widget' => '<div id="%1$s" class="widget wicket-footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	// Footer Widget 2
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 2', 'wicket' ),
		'id'            => 'footer-widget-2',
		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
		'before_widget' => '<div id="%1$s" class="widget wicket-footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	// Footer Widget 3
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 3', 'wicket' ),
		'id'            => 'footer-widget-3',
		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
		'before_widget' => '<div id="%1$s" class="widget wicket-footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	// Footer Widget 4
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 4', 'wicket' ),
		'id'            => 'footer-widget-4',
		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
		'before_widget' => '<div id="%1$s" class="widget wicket-footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	// Copyright Widget 1
	register_sidebar( array(
		'name'          => esc_html__( 'Copyright Widget 1', 'wicket' ),
		'id'            => 'copyright-widget-1',
		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
		'before_widget' => '<div id="%1$s" class="widget wicket-copyright-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	// Copyright Widget 2
	register_sidebar( array(
		'name'          => esc_html__( 'Copyright Widget 2', 'wicket' ),
		'id'            => 'copyright-widget-2',
		'description'   => esc_html__( 'Add widgets here.', 'wicket' ),
		'before_widget' => '<div id="%1$s" class="widget wicket-copyright-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'wicket_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wicket_public_scripts() {
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
    wp_enqueue_style( 'wicket', get_template_directory_uri() . '/assets/css/theme.css' );
    wp_enqueue_style( 'wicket-responsive', get_template_directory_uri() . '/assets/css/responsive.css' );
    wp_enqueue_style( 'wicket-style', get_stylesheet_uri() );

    // Enqueue blog details CSS on single blog posts
    if ( is_single() && get_post_type() === 'post' ) {
        wp_enqueue_style( 'wicket-blog-details', get_template_directory_uri() . '/assets/css/blog-details.css', array('wicket-style'), '2.0.0' );
    }
	
	
 // Enqueue jQuery UI for the autocomplete functionality
    wp_enqueue_script('jquery-ui-autocomplete');

    // Enqueue jQuery UI CSS for styling the autocomplete dropdown
    wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

    wp_enqueue_script( 'wicket-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'wicket-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script( 'popper', get_template_directory_uri() . '/assets/js/popper.min.js', array('jquery'), '20180708', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('popper'), '20180708', true );
	wp_enqueue_script( 'wicket', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), '20180708', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	 // Localize script for Ajax URL
    wp_localize_script('ajax-search-script', 'ajaxSearchParams', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action( 'wp_enqueue_scripts', 'wicket_public_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Bootstrap 4 Navwalkers
 */
require get_template_directory() . '/navwalkers.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce/wicket-woocommerce.php';
	require get_template_directory() . '/inc/woocommerce/wicket-woocommerce-template-hooks.php';
	require get_template_directory() . '/inc/woocommerce/wicket-woocommerce-functions.php';
}

/**
 * TGM Plugin Activation
 */
require get_template_directory() . '/inc/plugins/tgm-plugin-activation.php';

/**
 * Shortcode Documentation Settings Page
 */
require get_template_directory() . '/inc/shortcode-settings.php';

/**
 * Custom Post Type: Offer List
 */
require get_template_directory() . '/inc/custom-post-type-offer-list.php';

/**
 * Custom Post Type: Velki FAQ
 */
require get_template_directory() . '/inc/custom-post-type-faq.php';

/**
 * Custom Post Type: Velki Agent List
 */
require get_template_directory() . '/inc/custom-post-type-agent.php';

/**
 * Change the defualt WP login logo
 */
function wicket_login_screen_logo() {
	echo '<style type="text/css">
	.login h1 a {background-image: url('.get_bloginfo( 'template_directory' ).'/assets/img/login-screen.png) !important; height: auto !important; }
	</style>';
}
add_action( 'login_head', 'wicket_login_screen_logo' );

function wicket_login_head_url( $url ) {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'wicket_login_head_url' );

/**
 * A function that contains all options value
 */
function wicket_theme_options() {
	return $wicket_options = array(
		'wicket_header_type' => 'default', // Options: default, large, minimal, none
	);
}












// custom function added
function custom_user_list_posts_html_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '', // Default value for category
        ),
        $atts,
        'custom_user_list' // Shortcode name
    );


	// Get categories from shortcode attribute and convert them into an array
	$categories = !empty($atts['category']) ? array_map('trim', explode(',', $atts['category'])) : array();

    $args = array(
        'post_type' => 'user-list',
        'posts_per_page' => -1, // Retrieve all posts
    );

    // If categories are specified, add them to the query
    if (!empty($categories)) {
        $args['category_name'] = implode(',', $categories);
    }

    $user_list_posts = new WP_Query($args);
	
    ob_start(); // Start output buffering

    if ($user_list_posts->have_posts()) {
        while ($user_list_posts->have_posts()) {
            $user_list_posts->the_post();
            ?>


			<div class="list-box">
				<div class="focus-part">
					<div class="user-id">
						<p class="id-ti">আইডি</p>
						<div class="id-value"><?php echo get_post_meta(get_the_ID(), 'user_id', true); ?></div>
					</div>
					<div class="name-dig">

						<div class="img"><?php the_post_thumbnail(); ?></div>

						<div class="title-cat">
							<div class="title"><?php the_title(); ?></div>
							<div class="cat-dig <?php echo esc_attr(implode(' ', get_post_class())); ?>">
								<?php 
								$postcat = get_the_category(get_the_ID());
								if(isset($postcat[0])){
								$postcat_name = $postcat[0]->name;
								// var_dump(esc_html( $postcat[0]->name )); 
								//echo get_the_category_list(', '); ?>
								<?php //echo "বাংলাদেশ"; ?>


								<?php
								if ($postcat_name == "Customer Service") {
									echo "কাস্টমার সার্ভিস";
								} elseif ($postcat_name == "Sub Admin") {
									echo "সাব-এডমিন";
								} elseif ($postcat_name == "Admin") {
									echo "এডমিন";
								} elseif ($postcat_name == "Super Agent") {
									echo "সুপার এজেন্ট";
								} elseif ($postcat_name == "Master Agent") {
									echo "মাস্টার এজেন্ট";
								}else {
									echo "";
								}
							    }
								?>


							</div>
						</div>

					</div>
				</div>
				<!-- <div class="social-connection-group" style=""> -->
				<div class="social-connection-group" style="display: none;">

				<?php if(get_post_meta(get_the_ID(), 'whatsapp_primary', true) != ''){ ?>
					<div class="group-1">
						<span class="value-1">WhatsApp <span>(Primary)</span></span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_primary', true); ?></a></span>
						<span class="copy-btn">Copy Number</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank">Message</a></span>
					</div>
                <?php } ?>

				<?php if(get_post_meta(get_the_ID(), 'whatsapp_secondary', true) != ''){ ?>
					<div class="group-2">
						<span class="value-1">WhatsApp <span>(Secondary)</span></span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary', true); ?></a></span>
						<span class="copy-btn">Copy Number</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?>" target="_blank">Message</a></span>
					</div>
				<?php } ?>	

				<?php if(get_post_meta(get_the_ID(), 'messenger', true) != ''){ ?>
					<div class="group-3">
						<span class="value-1">Messenger</span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank"><?php echo get_post_meta(get_the_ID(), 'messenger', true); ?></a></span>
						<span class="copy-btn">Copy</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank">Message</a></span>
					</div>
				<?php } ?>


				</div>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        // No posts found
    }

    $output = ob_get_clean(); // Get the output and clean the buffer
    return $output; // Return the generated HTML
}
add_shortcode('custom_user_list', 'custom_user_list_posts_html_shortcode');
// [custom_user_list category="Category Name"]
// [custom_user_list category="Category Name 1, Category Name 2, Category Name 3"]










function custom_user_list_posts_html_shortcode_complain($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '', // Default value for category
        ),
        $atts,
        'custom_user_list' // Shortcode name
    );

    $args = array(
        'post_type' => 'user-list',
        'posts_per_page' => -1, // Retrieve all posts
    );

    // If a category is specified, add it to the query
    if (!empty($atts['category'])) {
        $args['category_name'] = $atts['category'];
    }

    $user_list_posts = new WP_Query($args);

    ob_start(); // Start output buffering

    if ($user_list_posts->have_posts()) {
        while ($user_list_posts->have_posts()) {
            $user_list_posts->the_post();

			// var_dump(get_post_meta(get_the_ID(), 'complain_agent', true));

			if(get_post_meta(get_the_ID(), 'complain_agent', true) == '1'){
				?>
					<div class="complain-box">
						<div class="focus-part-complain">

							<div class="name-dig">

								<div class="img"><?php the_post_thumbnail(); ?></div>

								<div class="title-cat">
									<div class="title"><?php the_title(); ?></div>
									<div class="cat-dig <?php echo esc_attr(implode(' ', get_post_class())); ?>">
										<?php 
										$postcat = get_the_category(get_the_ID());
										if(isset($postcat[0])){
										$postcat_name = $postcat[0]->name;
										// var_dump(esc_html( $postcat[0]->name )); 
										//echo get_the_category_list(', '); ?>
										<?php //echo "বাংলাদেশ"; ?>


										<?php
										if ($postcat_name == "Customer Service") {
											echo "কাস্টমার সার্ভিস";
										} elseif ($postcat_name == "Sub Admin") {
											echo "সাব-এডমিন";
										} elseif ($postcat_name == "Admin") {
											echo "এডমিন";
										} elseif ($postcat_name == "Super Agent") {
											echo "সুপার এজেন্ট";
										} elseif ($postcat_name == "Master Agent") {
											echo "মাস্টার এজেন্ট";
										}else {
											echo "";
										}
									    }
										?>


									</div>
								</div>

							</div>

							<div class="user-id">
								<p class="id-ti">আইডি</p>
								<div class="id-value"><?php echo get_post_meta(get_the_ID(), 'user_id', true); ?></div>
							</div>


						</div>
						<div class="social-connection-group complain-ss">

						   <?php if(get_post_meta(get_the_ID(), 'whatsapp_primary', true) != ''){ ?>
								<div class="group-1">
									<span class="value-1">WhatsApp <span>(Primary)</span></span>
									<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_primary', true); ?></a></span>
									<span class="copy-btn">Copy Number</span>
									<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank">Message</a></span>
								</div>
							<?php } ?>

							<?php if(get_post_meta(get_the_ID(), 'whatsapp_secondary', true) != ''){ ?>
								<div class="group-2">
									<span class="value-1">WhatsApp <span>(Secondary)</span></span>
									<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary', true); ?></a></span>
									<span class="copy-btn">Copy Number</span>
									<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?>" target="_blank">Message</a></span>
								</div>
							<?php } ?>

							<?php if(get_post_meta(get_the_ID(), 'messenger', true) != ''){ ?>
								<div class="group-3">
									<span class="value-1">Messenger</span>
									<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank"><?php echo get_post_meta(get_the_ID(), 'messenger', true); ?></a></span>
									<span class="copy-btn">Copy</span>
									<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank">Message</a></span>
								</div>
							<?php } ?>

						</div>
					</div>
				<?php
			}
        }
        wp_reset_postdata();
    } else {
        // No posts found
    }

    $output = ob_get_clean(); // Get the output and clean the buffer
    return $output; // Return the generated HTML
}
add_shortcode('complain_admin_list_2', 'custom_user_list_posts_html_shortcode_complain');





function custom_user_list_posts_html_shortcode_3($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '', // Default value for category
        ),
        $atts,
        'custom_user_list' // Shortcode name
    );


	// Get categories from shortcode attribute and convert them into an array
	$categories = !empty($atts['category']) ? array_map('trim', explode(',', $atts['category'])) : array();

    $args = array(
        'post_type' => 'user-list',
        'posts_per_page' => -1, // Retrieve all posts
    );

    // If categories are specified, add them to the query
    if (!empty($categories)) {
        $args['category_name'] = implode(',', $categories);
    }

    $user_list_posts = new WP_Query($args);
	
    ob_start(); // Start output buffering

    if ($user_list_posts->have_posts()) {
        while ($user_list_posts->have_posts()) {
            $user_list_posts->the_post();
			if(get_post_meta(get_the_ID(), 'complain_agent', true) == '1'){
            ?>


			<div class="list-box">
				<div class="focus-part">
					<div class="user-id">
						<p class="id-ti">আইডি</p>
						<div class="id-value"><?php echo get_post_meta(get_the_ID(), 'user_id', true); ?></div>
					</div>
					<div class="name-dig">

						<div class="img"><?php the_post_thumbnail(); ?></div>

						<div class="title-cat">
							<div class="title"><?php the_title(); ?></div>
							<div class="cat-dig <?php echo esc_attr(implode(' ', get_post_class())); ?>">
								<?php 
								$postcat = get_the_category(get_the_ID());
								if(isset($postcat[0])){
								$postcat_name = $postcat[0]->name;
								// var_dump(esc_html( $postcat[0]->name )); 
								//echo get_the_category_list(', '); ?>
								<?php //echo "বাংলাদেশ"; ?>


								<?php
								if ($postcat_name == "Customer Service") {
									echo "কাস্টমার সার্ভিস";
								} elseif ($postcat_name == "Sub Admin") {
									echo "সাব-এডমিন";
								} elseif ($postcat_name == "Admin") {
									echo "এডমিন";
								} elseif ($postcat_name == "Super Agent") {
									echo "সুপার এজেন্ট";
								} elseif ($postcat_name == "Master Agent") {
									echo "মাস্টার এজেন্ট";
								}else {
									echo "";
								}
							    }
								?>


							</div>
						</div>

					</div>
				</div>
				<!-- <div class="social-connection-group" style=""> -->
				<div class="social-connection-group" style="display: none;">

				<?php if(get_post_meta(get_the_ID(), 'whatsapp_primary', true) != ''){ ?>
					<div class="group-1">
						<span class="value-1">WhatsApp <span>(Primary)</span></span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_primary', true); ?></a></span>
						<span class="copy-btn">Copy Number</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank">Message</a></span>
					</div>
                <?php } ?>

				<?php if(get_post_meta(get_the_ID(), 'whatsapp_secondary', true) != ''){ ?>
					<div class="group-2">
						<span class="value-1">WhatsApp <span>(Secondary)</span></span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary', true); ?></a></span>
						<span class="copy-btn">Copy Number</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?>" target="_blank">Message</a></span>
					</div>
				<?php } ?>	

				<?php if(get_post_meta(get_the_ID(), 'messenger', true) != ''){ ?>
					<div class="group-3">
						<span class="value-1">Messenger</span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank"><?php echo get_post_meta(get_the_ID(), 'messenger', true); ?></a></span>
						<span class="copy-btn">Copy</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank">Message</a></span>
					</div>
				<?php } ?>


				</div>
            </div>
            <?php
			}
        }
        wp_reset_postdata();
    } else {
        // No posts found
    }

    $output = ob_get_clean(); // Get the output and clean the buffer
    return $output; // Return the generated HTML
}
add_shortcode('complain_admin_list', 'custom_user_list_posts_html_shortcode_3');







function custom_user_list_posts_html_shortcode_new_account($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '', // Default value for category
        ),
        $atts,
        'custom_user_list' // Shortcode name
    );

    $args = array(
        'post_type' => 'user-list',
        'posts_per_page' => -1, // Retrieve all posts
    );

    // If a category is specified, add it to the query
    if (!empty($atts['category'])) {
        $args['category_name'] = $atts['category'];
    }

    $user_list_posts = new WP_Query($args);

    ob_start(); // Start output buffering

    if ($user_list_posts->have_posts()) {
        while ($user_list_posts->have_posts()) {
            $user_list_posts->the_post();

			// var_dump(get_post_meta(get_the_ID(), 'complain_agent', true));

			if(get_post_meta(get_the_ID(), 'new_account_agent', true) == '1'){
				?>
					<div class="complain-box">
						<div class="focus-part-complain">

							<div class="name-dig">

								<div class="img"><?php the_post_thumbnail(); ?></div>

								<div class="title-cat">
									<div class="title"><?php the_title(); ?></div>
									<div class="cat-dig <?php echo esc_attr(implode(' ', get_post_class())); ?>">
										<?php 
										$postcat = get_the_category(get_the_ID());

										// var_dump($postcat);
                                    if(isset($postcat[0])){
										$postcat_name = $postcat[0]->name;
										// var_dump(esc_html( $postcat[0]->name )); 
										//echo get_the_category_list(', '); ?>
										<?php //echo "বাংলাদেশ"; ?>


										<?php
										if ($postcat_name == "Customer Service") {
											echo "কাস্টমার সার্ভিস";
										} elseif ($postcat_name == "Sub Admin") {
											echo "সাব-এডমিন";
										} elseif ($postcat_name == "Admin") {
											echo "এডমিন";
										} elseif ($postcat_name == "Super Agent") {
											echo "সুপার এজেন্ট";
										} elseif ($postcat_name == "Master Agent") {
											echo "মাস্টার এজেন্ট";
										}else {
											echo "";
										}
									}
										?>


									</div>
								</div>

							</div>

							<div class="user-id">
								<p class="id-ti">আইডি</p>
								<div class="id-value"><?php echo get_post_meta(get_the_ID(), 'user_id', true); ?></div>
							</div>


						</div>
						<div class="social-connection-group complain-ss">

						    <?php if(get_post_meta(get_the_ID(), 'whatsapp_primary', true) != ''){ ?>
								<div class="group-1">
									<span class="value-1">WhatsApp <span>(Primary)</span></span>
									<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_primary', true); ?></a></span>
									<span class="copy-btn">Copy Number</span>
									<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank">Message</a></span>
								</div>
							<?php } ?>

							<?php if(get_post_meta(get_the_ID(), 'whatsapp_secondary', true) != ''){ ?>
								<div class="group-2">
									<span class="value-1">WhatsApp <span>(Secondary)</span></span>
									<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary', true); ?></a></span>
									<span class="copy-btn">Copy Number</span>
									<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?>" target="_blank">Message</a></span>
								</div>
							<?php } ?>

                            <?php if(get_post_meta(get_the_ID(), 'messenger', true) != ''){ ?>
								<div class="group-3">
									<span class="value-1">Messenger</span>
									<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank"><?php echo get_post_meta(get_the_ID(), 'messenger', true); ?></a></span>
									<span class="copy-btn">Copy</span>
									<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank">Message</a></span>
								</div>
							<?php } ?>

						</div>
					</div>
				<?php
			}
        }
        wp_reset_postdata();
    } else {
        // No posts found
    }

    $output = ob_get_clean(); // Get the output and clean the buffer
    return $output; // Return the generated HTML
}
add_shortcode('new_account_list_2', 'custom_user_list_posts_html_shortcode_new_account');







function custom_user_list_posts_html_shortcode_2($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '', // Default value for category
        ),
        $atts,
        'custom_user_list' // Shortcode name
    );


	// Get categories from shortcode attribute and convert them into an array
	$categories = !empty($atts['category']) ? array_map('trim', explode(',', $atts['category'])) : array();

    $args = array(
        'post_type' => 'user-list',
        'posts_per_page' => -1, // Retrieve all posts
          'meta_key'       => 'serial_number_for_new_account_agent',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC', // Change to 'DESC' for descending order
    );

    // If categories are specified, add them to the query
    if (!empty($categories)) {
        $args['category_name'] = implode(',', $categories);
    }

    $user_list_posts = new WP_Query($args);
	
    ob_start(); // Start output buffering

    if ($user_list_posts->have_posts()) {
        while ($user_list_posts->have_posts()) {
            $user_list_posts->the_post();
			if(get_post_meta(get_the_ID(), 'new_account_agent', true) == '1'){
            ?>


			<div class="list-box">
				<div class="focus-part">
					<div class="user-id">
						<p class="id-ti">আইডি</p>
						<div class="id-value"><?php echo get_post_meta(get_the_ID(), 'user_id', true); ?></div>
					</div>
					<div class="name-dig">

						<div class="img"><?php the_post_thumbnail(); ?></div>

						<div class="title-cat">
							<div class="title"><?php the_title(); ?></div>
							<div class="cat-dig <?php echo esc_attr(implode(' ', get_post_class())); ?>">
								<?php 
								$postcat = get_the_category(get_the_ID());
								if(isset($postcat[0])){
								$postcat_name = $postcat[0]->name;
								// var_dump(esc_html( $postcat[0]->name )); 
								//echo get_the_category_list(', '); ?>
								<?php //echo "বাংলাদেশ"; ?>


								<?php
								if ($postcat_name == "Customer Service") {
									echo "কাস্টমার সার্ভিস";
								} elseif ($postcat_name == "Sub Admin") {
									echo "সাব-এডমিন";
								} elseif ($postcat_name == "Admin") {
									echo "এডমিন";
								} elseif ($postcat_name == "Super Agent") {
									echo "সুপার এজেন্ট";
								} elseif ($postcat_name == "Master Agent") {
									echo "মাস্টার এজেন্ট";
								}else {
									echo "";
								}
							    }
								?>


							</div>
						</div>

					</div>
				</div>
				<!-- <div class="social-connection-group" style=""> -->
				<div class="social-connection-group" style="display: none;">

				<?php if(get_post_meta(get_the_ID(), 'whatsapp_primary', true) != ''){ ?>
					<div class="group-1">
						<span class="value-1">WhatsApp <span>(Primary)</span></span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_primary', true); ?></a></span>
						<span class="copy-btn">Copy Number</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_primary_link', true); ?> " target="_blank">Message</a></span>
					</div>
                <?php } ?>

				<?php if(get_post_meta(get_the_ID(), 'whatsapp_secondary', true) != ''){ ?>
					<div class="group-2">
						<span class="value-1">WhatsApp <span>(Secondary)</span></span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?> " target="_blank"><?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary', true); ?></a></span>
						<span class="copy-btn">Copy Number</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true); ?>" target="_blank">Message</a></span>
					</div>
				<?php } ?>	

				<?php if(get_post_meta(get_the_ID(), 'messenger', true) != ''){ ?>
					<div class="group-3">
						<span class="value-1">Messenger</span>
						<span class="value-2"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank"><?php echo get_post_meta(get_the_ID(), 'messenger', true); ?></a></span>
						<span class="copy-btn">Copy</span>
						<span class="value-4"><a href="<?php echo get_post_meta(get_the_ID(), 'messenger', true); ?>" target="_blank">Message</a></span>
					</div>
				<?php } ?>


				</div>
            </div>
            <?php
			}
        }
        wp_reset_postdata();
    } else {
        // No posts found
    }

    $output = ob_get_clean(); // Get the output and clean the buffer
    return $output; // Return the generated HTML
}
add_shortcode('new_account_list', 'custom_user_list_posts_html_shortcode_2');





















function custom_post_thumbnail_with_dummy_image( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    // Check if the post has a thumbnail
    if ( ! has_post_thumbnail( $post_id ) ) {
        // URL of your dummy image
        $dummy_image_url = home_url('/wp-content/themes/wicket/assets/img/a.png');

        // Generate HTML for the dummy image
        $dummy_image_html = '<img src="' . esc_url( $dummy_image_url ) . '" alt="Dummy Image" />';

        // Return the dummy image HTML
        return $dummy_image_html;
    }

    // If the post has a thumbnail, return the original HTML
    return $html;
}
add_filter( 'post_thumbnail_html', 'custom_post_thumbnail_with_dummy_image', 10, 5 );







function quiz_post_list_shortcode_old() {
    // WP Query to get posts of custom post type 'ninequiz'
    $args = array(
        'post_type'      => 'ninequiz', // Change to the custom post type 'ninequiz'
        'posts_per_page' => -1,         // Number of posts to display
        'meta_query'     => array(
            array(
                'key'     => 'quiz_date', // Ensures posts have the quiz_date meta field
                'compare' => 'EXISTS'
            )
        )
    );
    $query = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($query->have_posts()) {
        echo '<ul class="quiz-post-list">';

        // Loop through posts
        while ($query->have_posts()) {
            $query->the_post();

            // Get meta field values
            $quiz_date            = get_post_meta(get_the_ID(), 'quiz_date', true);
            $quiz_form_id         = get_post_meta(get_the_ID(), 'quiz_form_id', true);
            $quiz_time_hour       = get_post_meta(get_the_ID(), 'quiz_count_time_hour', true);
            $quiz_time_minute     = get_post_meta(get_the_ID(), 'quiz_count_time_minute', true);
            $quiz_time_second     = get_post_meta(get_the_ID(), 'quiz_count_time_second', true);

            echo '<li class="quiz-post-item">';
            
            // Post thumbnail
            if (has_post_thumbnail()) {
                echo '<div class="quiz-post-thumbnail">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</div>';
            }

            // Post title and link
            echo '<div class="quiz-post-content">';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

            // Display meta fields
            echo '<p>Quiz Date: ' . esc_html($quiz_date) . '</p>';
            echo '<p>Form ID: ' . esc_html($quiz_form_id) . '</p>';
            echo '<p>Time: ' . esc_html($quiz_time_hour) . ' hour(s), ' . esc_html($quiz_time_minute) . ' minute(s), ' . esc_html($quiz_time_second) . ' second(s)</p>';
            echo '</div>'; // Close quiz-post-content

            echo '</li>';
        }

        echo '</ul>';
    } else {
        // No posts found
        echo '<p>No quiz posts found.</p>';
    }

    // Restore original post data
    wp_reset_postdata();

    // Return buffered output
    return ob_get_clean();
}
add_shortcode('quiz_post_list_pld', 'quiz_post_list_shortcode_old');





function quiz_timer_card_shortcode_old() {
    // WP Query to get posts of custom post type 'ninequiz'
    $args = array(
        'post_type'      => 'ninequiz', // Custom post type
        'posts_per_page' => -1,         // Number of posts to display
    );
    $query = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="timer-card-container">';

        // Loop through posts
        while ($query->have_posts()) {
            $query->the_post();

            // Get meta field values
            $quiz_date        = get_post_meta(get_the_ID(), 'quiz_date', true);
            $quiz_time_hour   = get_post_meta(get_the_ID(), 'quiz_count_time_hour', true);
            $quiz_time_minute = get_post_meta(get_the_ID(), 'quiz_count_time_minute', true);
            $quiz_time_second = get_post_meta(get_the_ID(), 'quiz_count_time_second', true);
            $quiz_image_url   = get_the_post_thumbnail_url(get_the_ID(), 'full'); // Get the post thumbnail
            $quiz_link        = get_permalink(); // Post link
            $quiz_title       = get_the_title();
            $timezone         = 'Asia/Dhaka'; // Hardcoded timezone "Dhaka"

            // Calculate total countdown time in seconds
            //$total_seconds = ($quiz_time_hour * 3600) + ($quiz_time_minute * 60) + $quiz_time_second;
            
$quiz_time_hour = isset($quiz_time_hour) && is_numeric($quiz_time_hour) ? $quiz_time_hour : 0;
$quiz_time_minute = isset($quiz_time_minute) && is_numeric($quiz_time_minute) ? $quiz_time_minute : 0;
$quiz_time_second = isset($quiz_time_second) && is_numeric($quiz_time_second) ? $quiz_time_second : 0;

$total_seconds = ($quiz_time_hour * 3600) + ($quiz_time_minute * 60) + $quiz_time_second;


            // Formatting target time
            $target_time = !empty($quiz_date) ? date('Y-m-d H:i:s', strtotime($quiz_date)) : '';

            ?>
            <div class="card card-info">
				<a href="<?= esc_url($quiz_link) ?>">
                <div class="card-header">
                    <img class="thumb" src="<?= esc_url($quiz_image_url) ?>" alt="<?= esc_attr($quiz_title) ?>">
                </div>
                <div class="card-body text-info">
                    <div id="current-time" style="display: none;">--:--:--</div>

                    <div id="countdown-container" class="counter-on" style="display:none">
                        <div id="countdown" data-value="<?= esc_attr($total_seconds) ?>">00:00</div>
                        <div id="targetTime" data-value="<?= esc_attr($target_time) ?>" style="display: none;"></div>
                        <div id="timezone" data-value="<?= esc_attr($timezone) ?>" style="display: none;"></div> <!-- Hardcoded Dhaka timezone -->
                    </div>

                    <div class="message counter-closed" style="display:none">
                        <div class="closed-msg">Quiz Closed</div>
                        <div class="closed-msg-2">
                            <a href="<?= esc_url($quiz_link) ?>">Click to view results</a>
                        </div>
                    </div>
                    <div class="message counter-coming" style="display:none">
                        <div class="coming-msg">Coming Soon...</div>
                    </div>
                    <div class="card-title">
                        <a href="<?= esc_url($quiz_link) ?>"><?= esc_html($quiz_title) ?></a>
                    </div>
                </div>
</a>
            </div>

            <?php
        }

        echo '</div>';
    } else {
        echo '<p>No quiz posts found.</p>';
    }

    // Restore original post data
    wp_reset_postdata();

    // Enqueue the timer JavaScript
    quiz_timer_card_enqueue_script();

    // Return buffered output
    return ob_get_clean();
}
add_shortcode('quiz_timer_card_old', 'quiz_timer_card_shortcode_old');

// Function to enqueue the script with the logic provided
function quiz_timer_card_enqueue_script_old() {
    ?>
    <script>
    var timerCardWidget = function ($scope, $) {
    // Loop through each timer card in the container
    $scope.find('.card').each(function () {
        var $card = $(this);

        // Fetching values from data attributes within the current card
        var $countdown = $card.find('#countdown');
        var totalSeconds = $countdown.data('value'); // Countdown duration in seconds
        var targetTime = $card.find('#targetTime').data('value'); // Start time from HTML
        var timezone = $card.find('#timezone').data('value'); // Timezone from HTML

        var countdownStarted = false; // To prevent multiple countdowns
        var countdownEnded = false; // To track if countdown has ended

        // Function to update the current time in the specified timezone
        function updateCurrentTime() {
            var now = new Date().toLocaleString("en-US", {
                timeZone: timezone,
                hour12: false,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            });

            // Adjust the format to YYYY-MM-DD HH:MM:SS for consistency
            var currentTime = now.replace(/(\d{2})\/(\d{2})\/(\d{4}),\s(\d{2}:\d{2}):(\d{2})/, '$3-$1-$2 $4:$5');

            $card.find('#current-time').text(currentTime); // Scoped to current card

            checkTimeAndStartCountdown(currentTime);
        }

        // Function to check if the current time matches the target time or is within the countdown duration
        function checkTimeAndStartCountdown(currentTime) {
            var targetDateTime = new Date(targetTime).getTime(); // Target time as a timestamp
            var endTime = targetDateTime + totalSeconds * 1000; // End time by adding total seconds (converted to ms)
            var currentTimestamp = new Date(currentTime).getTime(); // Current time as a timestamp

            // Check if the current time is within the countdown period
            if (!countdownStarted && currentTimestamp >= targetDateTime && currentTimestamp <= endTime) {
                var remainingTime = endTime - currentTimestamp; // Remaining time in milliseconds
                startCountdown(remainingTime);
                countdownStarted = true; // Mark countdown as started

                // Toggle visibility of elements based on countdown state
                $card.find('.counter-closed').hide();
                $card.find('.counter-on').show();
                $card.find('.counter-coming').hide();
				$card.removeClass("closed-qz");
				$card.find('.card-title').show();

            } else if (currentTimestamp > endTime && !countdownEnded) {
                // Countdown has ended
                countdownEnded = true;
                $card.find('.counter-closed').show();
				$card.addClass("closed-qz");
                $card.find('.counter-on').hide();
                $card.find('.counter-coming').hide();
				
				$card.find('.card-title').hide();
				
				
            }
        }

        // Function to start the countdown with remaining time
        function startCountdown(remainingTime) {
            var countdownTime = Math.floor(remainingTime / 1000); // Convert remaining time to seconds

            var countdownInterval = setInterval(function () {
                var hours = Math.floor(countdownTime / 3600);
                var minutes = Math.floor((countdownTime % 3600) / 60);
                var seconds = countdownTime % 60;

                // Display the countdown in the format HH:MM:SS or MM:SS if no hours
                $countdown.html(
                    (hours > 0 ? String(hours).padStart(2, '0') + ":" : "") +
                    String(minutes).padStart(2, '0') + ":" +
                    String(seconds).padStart(2, '0')
                );

                // Check if countdown has reached zero
                if (countdownTime <= 0) {
                    clearInterval(countdownInterval);
                    $countdown.html("00:00:00");

                    // Countdown has ended, show "counter-closed"
                    $card.find('.counter-closed').show();
                    $card.find('.counter-on').hide();
                    $card.find('.counter-coming').hide();

                    countdownEnded = true; // Mark countdown as ended
                }

                countdownTime--;
            }, 1000); // Update every second
        }

        // Initially show "coming soon" state before countdown starts
        $card.find('.counter-closed').hide();
        $card.find('.counter-on').hide();
        $card.find('.counter-coming').show();

        // Start updating the current time every second
        setInterval(updateCurrentTime, 1000);
    });
};


    // Initialize the widget for the quiz cards on document ready
    jQuery(document).ready(function($) {
        timerCardWidget($('.timer-card-container'), $);
    });
    </script>
    <?php
}




function quiz_post_list_shortcode() {
    // WP Query to get posts of custom post type 'ninequiz'
    $args = array(
        'post_type'      => 'ninequiz', // Change to the custom post type 'ninequiz'
        'posts_per_page' => -1,         // Number of posts to display
        'meta_query'     => array(
            array(
                'key'     => 'quiz_date', // Ensures posts have the quiz_date meta field
                'compare' => 'EXISTS'
            )
        )
    );
    $query = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($query->have_posts()) {
        echo '<ul class="quiz-post-list">';

        // Loop through posts
        while ($query->have_posts()) {
            $query->the_post();

            // Get meta field values
            $quiz_date            = get_post_meta(get_the_ID(), 'quiz_date', true);
            $quiz_form_id         = get_post_meta(get_the_ID(), 'quiz_form_id', true);
            $quiz_time_hour       = get_post_meta(get_the_ID(), 'quiz_count_time_hour', true);
            $quiz_time_minute     = get_post_meta(get_the_ID(), 'quiz_count_time_minute', true);
            $quiz_time_second     = get_post_meta(get_the_ID(), 'quiz_count_time_second', true);

            echo '<li class="quiz-post-item">';
            
            // Post thumbnail
            if (has_post_thumbnail()) {
                echo '<div class="quiz-post-thumbnail">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</div>';
            }

            // Post title and link
            echo '<div class="quiz-post-content">';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

            // Display meta fields
            echo '<p>Quiz Date: ' . esc_html($quiz_date) . '</p>';
            echo '<p>Form ID: ' . esc_html($quiz_form_id) . '</p>';
            echo '<p>Time: ' . esc_html($quiz_time_hour) . ' hour(s), ' . esc_html($quiz_time_minute) . ' minute(s), ' . esc_html($quiz_time_second) . ' second(s)</p>';
            echo '</div>'; // Close quiz-post-content

            echo '</li>';
        }

        echo '</ul>';
    } else {
        // No posts found
        echo '<p>No quiz posts found.</p>';
    }

    // Restore original post data
    wp_reset_postdata();

    // Return buffered output
    return ob_get_clean();
}
add_shortcode('quiz_post_list', 'quiz_post_list_shortcode');





function quiz_timer_card_shortcode() {
    // WP Query to get posts of custom post type 'ninequiz'
    $args = array(
        'post_type'      => 'ninequiz', 
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="timer-card-container">';

        while ($query->have_posts()) {
            $query->the_post();

            // Get meta values
            $quiz_date = get_post_meta(get_the_ID(), 'quiz_date', true);
            $quiz_end_date_time = get_post_meta(get_the_ID(), 'end_time', true);
            $quiz_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); 
            $quiz_link = get_permalink(); 
            $quiz_title = get_the_title();
            $unique_id = uniqid('quiz_'); // Ensure unique IDs

            $timezone = 'Asia/Dhaka';
            $start_time = !empty($quiz_date) ? new DateTime($quiz_date, new DateTimeZone($timezone)) : null;
            $end_time = !empty($quiz_end_date_time) ? new DateTime($quiz_end_date_time, new DateTimeZone($timezone)) : null;

            $start_timestamp = $start_time ? $start_time->getTimestamp() : 0;
            $end_timestamp = $end_time ? $end_time->getTimestamp() : 0;

            ?>
            <div class="card card-info" id="<?= esc_attr($unique_id); ?>">
                <a href="<?= esc_url($quiz_link) ?>">
                    <div class="card-header">
                        <img class="thumb" src="<?= esc_url($quiz_image_url) ?>" alt="<?= esc_attr($quiz_title) ?>">
                    </div>
                    <div class="card-body text-info">
                        <div class="counter-coming message">Coming Soon...</div>
                        <div class="counter-on message" style="display:none;">
                            <div id="countdown-<?= esc_attr($unique_id); ?>">00:00:00</div>
                        </div>
                        <div class="counter-closed message" style="display:none;">
                            <div class="closed-msg">Quiz Closed</div>
                        </div>
                        <input type="hidden" class="start-time" value="<?= esc_attr($start_timestamp); ?>">
                        <input type="hidden" class="end-time" value="<?= esc_attr($end_timestamp); ?>">
                        <input type="hidden" class="timezone" value="<?= esc_attr($timezone); ?>">
                        <div class="card-title">
                            <a href="<?= esc_url($quiz_link) ?>"><?= esc_html($quiz_title) ?></a>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }

        echo '</div>';
    } else {
        echo '<p>No quiz posts found.</p>';
    }

    wp_reset_postdata();

    quiz_timer_card_enqueue_script();
    return ob_get_clean();
}

add_shortcode('quiz_timer_card', 'quiz_timer_card_shortcode');


function quiz_timer_card_enqueue_script() {
    ?>
<script>
jQuery(document).ready(function ($) {
    function initCountdown() {
        $(".card").each(function () {
            var $card = $(this);

            var startTime = parseInt($card.find(".start-time").val(), 10);
            var endTime = parseInt($card.find(".end-time").val(), 10);
            var timezone = $card.find(".timezone").val();

            function getCurrentTimestamp() {
                return Math.floor(new Date().getTime() / 1000);
            }

            function updateCountdown() {
                var currentTimestamp = getCurrentTimestamp();

                if (currentTimestamp < startTime) {
                    $card.find(".counter-coming").show();
                    $card.find(".counter-on, .counter-closed").hide();
                } else if (currentTimestamp >= startTime && currentTimestamp <= endTime) {
                    $card.find(".counter-coming, .counter-closed").hide();
                    $card.find(".counter-on").show();

                    var remainingTime = endTime - currentTimestamp;
                    var hours = Math.floor(remainingTime / 3600);
                    var minutes = Math.floor((remainingTime % 3600) / 60);
                    var seconds = remainingTime % 60;

                    $card.find("#countdown-" + $card.attr("id")).text(
                        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
                    );

                    if (remainingTime <= 0) {
                        clearInterval(interval);
                        $card.find(".counter-on").hide();
                        $card.find(".counter-closed").show();
                    }
                } else {
                    $card.find(".counter-on, .counter-coming").hide();
                    $card.find(".counter-closed").show();
                }
            }

            var interval = setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    }

    initCountdown();
});

</script>

    <?php
}




function load_quiz_form() {
    if (isset($_POST['quiz_form_id'])) {
        $quiz_form_id = sanitize_text_field($_POST['quiz_form_id']);
        echo do_shortcode("[formidable id='{$quiz_form_id}']");
    }
    wp_die();
}
add_action('wp_ajax_load_quiz_form', 'load_quiz_form');
add_action('wp_ajax_nopriv_load_quiz_form', 'load_quiz_form');







// Register meta boxes for custom post type 'ninequiz'
function ninequiz_register_meta_boxes() {
    add_meta_box(
        'ninequiz_result_group_1', 
        'Result Group (1st position))', 
        'ninequiz_repeater_group_1_html', 
        'ninequiz'
    );

    add_meta_box(
        'ninequiz_result_group_2', 
        'Result Group (2nd position)', 
        'ninequiz_repeater_group_2_html', 
        'ninequiz'
    );

    add_meta_box(
        'ninequiz_result_group_3', 
        'Result Group (3rd position)', 
        'ninequiz_repeater_group_3_html', 
        'ninequiz'
    );

    add_meta_box(
        'ninequiz_result_group_4', 
        'Result Group (4th position)', 
        'ninequiz_repeater_group_4_html', 
        'ninequiz'
    );
}
add_action( 'add_meta_boxes', 'ninequiz_register_meta_boxes' );

// Display HTML for Result Group 1
function ninequiz_repeater_group_1_html( $post ) {
    $group_1_results = get_post_meta( $post->ID, 'ninequiz_result_group_1', true );

    wp_nonce_field( 'ninequiz_group_1_nonce', 'ninequiz_group_1_nonce_field' );
    ?>

    <div id="repeater-group-1">
        <?php if ( !empty($group_1_results) && is_array($group_1_results) ) : ?>
            <?php foreach ( $group_1_results as $index => $result ) : ?>
                <div class="repeater-item">
                    <label>Name:</label>
                    <input type="text" name="ninequiz_result_group_1[<?php echo $index; ?>][name]" value="<?php echo esc_attr($result['name']); ?>" />

                    <label>Email:</label>
                    <input type="text" name="ninequiz_result_group_1[<?php echo $index; ?>][email]" value="<?php echo esc_attr($result['email']); ?>" />

                    <label>Phone:</label>
                    <input type="text" name="ninequiz_result_group_1[<?php echo $index; ?>][phone]" value="<?php echo esc_attr($result['phone']); ?>" />

                    <label>Description:</label>
                    <textarea name="ninequiz_result_group_1[<?php echo $index; ?>][description]"><?php echo esc_textarea($result['description']); ?></textarea>

                    <button class="remove-row">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="repeater-item">
                <label>Name:</label>
                <input type="text" name="ninequiz_result_group_1[0][name]" />

                <label>Email:</label>
                <input type="text" name="ninequiz_result_group_1[0][email]" />

                <label>Phone:</label>
                <input type="text" name="ninequiz_result_group_1[0][phone]" />

                <label>Description:</label>
                <textarea name="ninequiz_result_group_1[0][description]"></textarea>

                <button class="remove-row">Remove</button>
            </div>
        <?php endif; ?>
    </div>

    <button id="add-row-group-1">Add Result to Group 1</button>

    <script>
        jQuery(document).ready(function($) {
            let index = $('#repeater-group-1 .repeater-item').length;

            // Add new row
            $('#add-row-group-1').on('click', function(e) {
                e.preventDefault();
                $('#repeater-group-1').append(`
                    <div class="repeater-item">
                        <label>Name:</label>
                        <input type="text" name="ninequiz_result_group_1[` + index + `][name]" />

                        <label>Email:</label>
                        <input type="text" name="ninequiz_result_group_1[` + index + `][email]" />

                        <label>Phone:</label>
                        <input type="text" name="ninequiz_result_group_1[` + index + `][phone]" />

                        <label>Description:</label>
                        <textarea name="ninequiz_result_group_1[` + index + `][description]"></textarea>

                        <button class="remove-row">Remove</button>
                    </div>
                `);
                index++;
            });

            // Remove row
            $('#repeater-group-1').on('click', '.remove-row', function(e) {
                e.preventDefault();
                $(this).closest('.repeater-item').remove();
            });
        });
    </script>
    <?php
}

// Display HTML for Result Group 2
function ninequiz_repeater_group_2_html( $post ) {
    $group_2_results = get_post_meta( $post->ID, 'ninequiz_result_group_2', true );

    wp_nonce_field( 'ninequiz_group_2_nonce', 'ninequiz_group_2_nonce_field' );
    ?>

    <div id="repeater-group-2">
        <?php if ( !empty($group_2_results) && is_array($group_2_results) ) : ?>
            <?php foreach ( $group_2_results as $index => $result ) : ?>
                <div class="repeater-item">
                    <label>Name:</label>
                    <input type="text" name="ninequiz_result_group_2[<?php echo $index; ?>][name]" value="<?php echo esc_attr($result['name']); ?>" />

                    <label>Email:</label>
                    <input type="text" name="ninequiz_result_group_2[<?php echo $index; ?>][email]" value="<?php echo esc_attr($result['email']); ?>" />

                    <label>Phone:</label>
                    <input type="text" name="ninequiz_result_group_2[<?php echo $index; ?>][phone]" value="<?php echo esc_attr($result['phone']); ?>" />

                    <label>Description:</label>
                    <textarea name="ninequiz_result_group_2[<?php echo $index; ?>][description]"><?php echo esc_textarea($result['description']); ?></textarea>

                    <button class="remove-row">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="repeater-item">
                <label>Name:</label>
                <input type="text" name="ninequiz_result_group_2[0][name]" />

                <label>Email:</label>
                <input type="text" name="ninequiz_result_group_2[0][email]" />

                <label>Phone:</label>
                <input type="text" name="ninequiz_result_group_2[0][phone]" />

                <label>Description:</label>
                <textarea name="ninequiz_result_group_2[0][description]"></textarea>

                <button class="remove-row">Remove</button>
            </div>
        <?php endif; ?>
    </div>

    <button id="add-row-group-2">Add Result to Group 2</button>

    <script>
        jQuery(document).ready(function($) {
            let index = $('#repeater-group-2 .repeater-item').length;

            // Add new row
            $('#add-row-group-2').on('click', function(e) {
                e.preventDefault();
                $('#repeater-group-2').append(`
                    <div class="repeater-item">
                        <label>Name:</label>
                        <input type="text" name="ninequiz_result_group_2[` + index + `][name]" />

                        <label>Email:</label>
                        <input type="text" name="ninequiz_result_group_2[` + index + `][email]" />

                        <label>Phone:</label>
                        <input type="text" name="ninequiz_result_group_2[` + index + `][phone]" />

                        <label>Description:</label>
                        <textarea name="ninequiz_result_group_2[` + index + `][description]"></textarea>

                        <button class="remove-row">Remove</button>
                    </div>
                `);
                index++;
            });

            // Remove row
            $('#repeater-group-2').on('click', '.remove-row', function(e) {
                e.preventDefault();
                $(this).closest('.repeater-item').remove();
            });
        });
    </script>
    <?php
}

// Display HTML for Result Group 3
function ninequiz_repeater_group_3_html( $post ) {
    $group_3_results = get_post_meta( $post->ID, 'ninequiz_result_group_3', true );

    wp_nonce_field( 'ninequiz_group_3_nonce', 'ninequiz_group_3_nonce_field' );
    ?>

    <div id="repeater-group-3">
        <?php if ( !empty($group_3_results) && is_array($group_3_results) ) : ?>
            <?php foreach ( $group_3_results as $index => $result ) : ?>
                <div class="repeater-item">
                    <label>Name:</label>
                    <input type="text" name="ninequiz_result_group_3[<?php echo $index; ?>][name]" value="<?php echo esc_attr($result['name']); ?>" />

                    <label>Email:</label>
                    <input type="text" name="ninequiz_result_group_3[<?php echo $index; ?>][email]" value="<?php echo esc_attr($result['email']); ?>" />

                    <label>Phone:</label>
                    <input type="text" name="ninequiz_result_group_3[<?php echo $index; ?>][phone]" value="<?php echo esc_attr($result['phone']); ?>" />

                    <label>Description:</label>
                    <textarea name="ninequiz_result_group_3[<?php echo $index; ?>][description]"><?php echo esc_textarea($result['description']); ?></textarea>

                    <button class="remove-row">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="repeater-item">
                <label>Name:</label>
                <input type="text" name="ninequiz_result_group_3[0][name]" />

                <label>Email:</label>
                <input type="text" name="ninequiz_result_group_3[0][email]" />

                <label>Phone:</label>
                <input type="text" name="ninequiz_result_group_3[0][phone]" />

                <label>Description:</label>
                <textarea name="ninequiz_result_group_3[0][description]"></textarea>

                <button class="remove-row">Remove</button>
            </div>
        <?php endif; ?>
    </div>

    <button id="add-row-group-3">Add Result to Group 3</button>

    <script>
        jQuery(document).ready(function($) {
            let index = $('#repeater-group-3 .repeater-item').length;

            // Add new row
            $('#add-row-group-3').on('click', function(e) {
                e.preventDefault();
                $('#repeater-group-3').append(`
                    <div class="repeater-item">
                        <label>Name:</label>
                        <input type="text" name="ninequiz_result_group_3[` + index + `][name]" />

                        <label>Email:</label>
                        <input type="text" name="ninequiz_result_group_3[` + index + `][email]" />

                        <label>Phone:</label>
                        <input type="text" name="ninequiz_result_group_3[` + index + `][phone]" />

                        <label>Description:</label>
                        <textarea name="ninequiz_result_group_3[` + index + `][description]"></textarea>

                        <button class="remove-row">Remove</button>
                    </div>
                `);
                index++;
            });

            // Remove row
            $('#repeater-group-3').on('click', '.remove-row', function(e) {
                e.preventDefault();
                $(this).closest('.repeater-item').remove();
            });
        });
    </script>
    <?php
}

// Display HTML for Result Group 4
function ninequiz_repeater_group_4_html( $post ) {
    $group_4_results = get_post_meta( $post->ID, 'ninequiz_result_group_4', true );

    wp_nonce_field( 'ninequiz_group_4_nonce', 'ninequiz_group_4_nonce_field' );
    ?>

    <div id="repeater-group-4">
        <?php if ( !empty($group_4_results) && is_array($group_4_results) ) : ?>
            <?php foreach ( $group_4_results as $index => $result ) : ?>
                <div class="repeater-item">
                    <label>Name:</label>
                    <input type="text" name="ninequiz_result_group_4[<?php echo $index; ?>][name]" value="<?php echo esc_attr($result['name']); ?>" />

                    <label>Email:</label>
                    <input type="text" name="ninequiz_result_group_4[<?php echo $index; ?>][email]" value="<?php echo esc_attr($result['email']); ?>" />

                    <label>Phone:</label>
                    <input type="text" name="ninequiz_result_group_4[<?php echo $index; ?>][phone]" value="<?php echo esc_attr($result['phone']); ?>" />

                    <label>Description:</label>
                    <textarea name="ninequiz_result_group_4[<?php echo $index; ?>][description]"><?php echo esc_textarea($result['description']); ?></textarea>

                    <button class="remove-row">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="repeater-item">
                <label>Name:</label>
                <input type="text" name="ninequiz_result_group_4[0][name]" />

                <label>Email:</label>
                <input type="text" name="ninequiz_result_group_4[0][email]" />

                <label>Phone:</label>
                <input type="text" name="ninequiz_result_group_4[0][phone]" />

                <label>Description:</label>
                <textarea name="ninequiz_result_group_4[0][description]"></textarea>

                <button class="remove-row">Remove</button>
            </div>
        <?php endif; ?>
    </div>

    <button id="add-row-group-4">Add Result to Group 4</button>

    <script>
        jQuery(document).ready(function($) {
            let index = $('#repeater-group-4 .repeater-item').length;

            // Add new row
            $('#add-row-group-4').on('click', function(e) {
                e.preventDefault();
                $('#repeater-group-4').append(`
                    <div class="repeater-item">
                        <label>Name:</label>
                        <input type="text" name="ninequiz_result_group_4[` + index + `][name]" />

                        <label>Email:</label>
                        <input type="text" name="ninequiz_result_group_4[` + index + `][email]" />

                        <label>Phone:</label>
                        <input type="text" name="ninequiz_result_group_4[` + index + `][phone]" />

                        <label>Description:</label>
                        <textarea name="ninequiz_result_group_4[` + index + `][description]"></textarea>

                        <button class="remove-row">Remove</button>
                    </div>
                `);
                index++;
            });

            // Remove row
            $('#repeater-group-4').on('click', '.remove-row', function(e) {
                e.preventDefault();
                $(this).closest('.repeater-item').remove();
            });
        });
    </script>
    <?php
}

// Save the meta box data
function ninequiz_save_meta_boxes( $post_id ) {
    // Check nonce for each group
    if ( !isset($_POST['ninequiz_group_1_nonce_field']) || !wp_verify_nonce($_POST['ninequiz_group_1_nonce_field'], 'ninequiz_group_1_nonce') ) {
        return;
    }

    if ( !isset($_POST['ninequiz_group_2_nonce_field']) || !wp_verify_nonce($_POST['ninequiz_group_2_nonce_field'], 'ninequiz_group_2_nonce') ) {
        return;
    }

    if ( !isset($_POST['ninequiz_group_3_nonce_field']) || !wp_verify_nonce($_POST['ninequiz_group_3_nonce_field'], 'ninequiz_group_3_nonce') ) {
        return;
    }

    if ( !isset($_POST['ninequiz_group_4_nonce_field']) || !wp_verify_nonce($_POST['ninequiz_group_4_nonce_field'], 'ninequiz_group_4_nonce') ) {
        return;
    }

    // Save each group
    if ( isset($_POST['ninequiz_result_group_1']) ) {
        update_post_meta( $post_id, 'ninequiz_result_group_1', $_POST['ninequiz_result_group_1'] );
    }

    if ( isset($_POST['ninequiz_result_group_2']) ) {
        update_post_meta( $post_id, 'ninequiz_result_group_2', $_POST['ninequiz_result_group_2'] );
    }

    if ( isset($_POST['ninequiz_result_group_3']) ) {
        update_post_meta( $post_id, 'ninequiz_result_group_3', $_POST['ninequiz_result_group_3'] );
    }

    if ( isset($_POST['ninequiz_result_group_4']) ) {
        update_post_meta( $post_id, 'ninequiz_result_group_4', $_POST['ninequiz_result_group_4'] );
    }
}
add_action( 'save_post', 'ninequiz_save_meta_boxes' );



function ninequiz_save_meta_boxes_data( $post_id ) {
    // Check if nonce is set and valid
    if ( ! isset( $_POST['ninequiz_group_1_nonce_field'] ) || ! wp_verify_nonce( $_POST['ninequiz_group_1_nonce_field'], 'ninequiz_group_1_nonce' ) ) {
        return;
    }

    // Prevent auto save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check user permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Sanitize and save group 1 results
    if ( isset( $_POST['ninequiz_result_group_1'] ) && is_array( $_POST['ninequiz_result_group_1'] ) ) {
        $group_1_results = array_map( function( $result ) {
            return [
                'name'        => sanitize_text_field( $result['name'] ),
                'email'       => sanitize_text_field( $result['email'] ),
                'phone'       => sanitize_text_field( $result['phone'] ),
                'description' => sanitize_textarea_field( $result['description'] ),
            ];
        }, $_POST['ninequiz_result_group_1'] );

        update_post_meta( $post_id, 'ninequiz_result_group_1', $group_1_results );
    } else {
        delete_post_meta( $post_id, 'ninequiz_result_group_1' );
    }
}
add_action( 'save_post', 'ninequiz_save_meta_boxes_data' );


// Register sidebar from jaagoit

function single_blog_page_widget() {

    // Register the new sidebar

    register_sidebar( array(
        'name'          => __( 'Single Blog page Widget', 'wicket' ),
        'id'            => 'single-blog-page-widget', // Unique ID for the sidebar
        'description'   => __( 'Add widgets here for single blog page', 'wicket' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'single_blog_page_widget' );



function asif_khalid_add_above_menu(){ ?>
    <div class="nav-top-ticker">
        <h4 class="asf-heading">Admin: Asif Khalid</h4>
    </div>
<?php }


add_action('wicket_before_header_menu', 'asif_khalid_add_above_menu');




add_action( 'admin_init', 'disable_autosave' );

function disable_autosave() {
wp_deregister_script( 'autosave' );
}



function add_pagesense_script_to_head() {
    // Target pages
    $target_pages = array( '9wicket-master-agent', '9wicket-new-account-open' );

    if ( is_front_page() || is_page( $target_pages ) ) {
        echo '<script src="https://cdn.pagesense.io/js/904723425/6fd33c70d4694fa48885faa97d134a96.js"></script>';
    }
}
add_action( 'wp_head', 'add_pagesense_script_to_head' );


/**
 * Velki Agent List Shortcode
 * Display agents from velki-agent post type filtered by agent-group taxonomy
 * Usage: [velki_agent_list group="মাস্টার এজেন্ট"]
 * Usage: [velki_agent_list group="মাস্টার এজেন্ট,সুপার এজেন্ট"]
 */
function velki_agent_list_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'group' => '', // Agent group(s) to filter by
            'limit' => -1, // Number of agents to display (-1 for all)
        ),
        $atts,
        'velki_agent_list'
    );

    // Parse groups into array
    $groups = !empty($atts['group']) ? array_map('trim', explode(',', $atts['group'])) : array();

    // Build query args
    $args = array(
        'post_type' => 'velki-agent',
        'posts_per_page' => intval($atts['limit']),
        'post_status' => 'publish',
    );

    // Add taxonomy filter if groups specified
    if (!empty($groups)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'agent-group',
                'field' => 'name',
                'terms' => $groups,
            ),
        );
    }

    $agents = new WP_Query($args);

    ob_start();

    if ($agents->have_posts()) {
        echo '<div class="velki-agent-list-container">';

        while ($agents->have_posts()) {
            $agents->the_post();

            // Get meta data
            $agent_id = get_post_meta(get_the_ID(), '_agent_id', true);
            $rating = get_post_meta(get_the_ID(), '_agent_rating', true);
            $is_verified = get_post_meta(get_the_ID(), '_agent_verified', true);
            $is_premium = get_post_meta(get_the_ID(), '_agent_premium', true);
            $whatsapp_url_1 = get_post_meta(get_the_ID(), '_agent_whatsapp_url_1', true);
            $whatsapp_url_2 = get_post_meta(get_the_ID(), '_agent_whatsapp_url_2', true);
            $messenger_url = get_post_meta(get_the_ID(), '_agent_messenger_url', true);

            // Get agent groups
            $terms = get_the_terms(get_the_ID(), 'agent-group');
            $agent_group_name = '';
            if ($terms && !is_wp_error($terms)) {
                $agent_group_name = $terms[0]->name;
            }

            // Extract phone numbers from WhatsApp URLs
            $phone_1 = $whatsapp_url_1 ? preg_replace('/[^0-9+]/', '', str_replace('https://wa.me/', '', $whatsapp_url_1)) : '';
            $phone_2 = $whatsapp_url_2 ? preg_replace('/[^0-9+]/', '', str_replace('https://wa.me/', '', $whatsapp_url_2)) : '';

            // Extract messenger username from URL
            $messenger_username = $messenger_url ? str_replace(array('https://m.me/', 'http://m.me/'), '', $messenger_url) : '';
            ?>

            <div class="velki-agent-card">
                <div class="agent-left-section">
                    <div class="agent-photo-wrapper">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail', array('class' => 'agent-photo')); ?>
                        <?php else : ?>
                            <div class="agent-photo agent-photo-placeholder">
                                <span class="dashicons dashicons-businessman"></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($is_verified) : ?>
                            <span class="agent-verified-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="agent-info">
                        <div class="agent-name-row">
                            <h3 class="agent-name"><?php the_title(); ?></h3>
                            <?php if ($is_premium) : ?>
                                <span class="agent-premium-crown">👑</span>
                            <?php endif; ?>
                        </div>

                        <div class="agent-group"><?php echo esc_html($agent_group_name); ?></div>

                        <?php if ($rating) : ?>
                            <div class="agent-rating">
                                <?php echo str_repeat('⭐', intval($rating)); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($agent_id) : ?>
                            <div class="agent-id-section">
                                <span class="agent-id-label">ID:</span>
                                <span class="agent-id-value"><?php echo esc_html($agent_id); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="agent-contact-section">
                    <?php if ($whatsapp_url_1 || $whatsapp_url_2) : ?>
                        <div class="contact-column whatsapp-column">
                            <div class="contact-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.304-1.654a11.882 11.882 0 005.713 1.456h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span>WhatsApp</span>
                            </div>

                            <?php if ($phone_1) : ?>
                                <div class="contact-item">
                                    <span class="contact-number"><?php echo esc_html($phone_1); ?></span>
                                    <button class="copy-btn" data-copy="<?php echo esc_attr($phone_1); ?>" title="Copy number">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                    </button>
                                    <a href="<?php echo esc_url($whatsapp_url_1); ?>" target="_blank" class="message-btn whatsapp-message-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        Message
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ($phone_2) : ?>
                                <div class="contact-item">
                                    <span class="contact-number"><?php echo esc_html($phone_2); ?></span>
                                    <button class="copy-btn" data-copy="<?php echo esc_attr($phone_2); ?>" title="Copy number">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                    </button>
                                    <a href="<?php echo esc_url($whatsapp_url_2); ?>" target="_blank" class="message-btn whatsapp-message-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        Message
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($messenger_url) : ?>
                        <div class="contact-column messenger-column">
                            <div class="contact-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 0C5.373 0 0 4.975 0 11.111c0 3.497 1.745 6.616 4.472 8.652V24l4.086-2.242c1.09.301 2.246.464 3.442.464 6.627 0 12-4.974 12-11.111C24 4.975 18.627 0 12 0zm1.193 14.963l-3.056-3.259-5.963 3.259L10.732 8l3.13 3.259L19.752 8l-6.559 6.963z"/>
                                </svg>
                                <span>Messenger</span>
                            </div>

                            <div class="contact-item">
                                <span class="contact-number"><?php echo esc_html($messenger_username); ?></span>
                                <button class="copy-btn" data-copy="<?php echo esc_attr($messenger_username); ?>" title="Copy username">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                </button>
                                <a href="<?php echo esc_url($messenger_url); ?>" target="_blank" class="message-btn messenger-message-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                    Contact
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php
        }

        echo '</div>';

        // Add CSS
        velki_agent_list_inline_css();

        // Add JavaScript
        velki_agent_list_inline_js();
    } else {
        echo '<p>No agents found.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('velki_agent_list', 'velki_agent_list_shortcode');


/**
 * Inline CSS for Agent List
 */
function velki_agent_list_inline_css() {
    static $css_output = false;
    if ($css_output) return;
    $css_output = true;
    ?>
    <style>
    .velki-agent-list-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .velki-agent-card {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        gap: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .velki-agent-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4);
    }

    .agent-left-section {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .agent-photo-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .agent-photo {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .agent-photo-placeholder {
        background: linear-gradient(135deg, #475569 0%, #64748b 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.3);
        font-size: 48px;
    }

    .agent-verified-badge {
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        background: #fbbf24;
        color: #1e293b;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #1e293b;
    }

    .agent-verified-badge svg {
        width: 18px;
        height: 18px;
    }

    .agent-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .agent-name-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .agent-name {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    .agent-premium-crown {
        font-size: 20px;
    }

    .agent-group {
        color: #94a3b8;
        font-size: 14px;
    }

    .agent-rating {
        font-size: 14px;
        line-height: 1;
    }

    .agent-id-section {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }

    .agent-id-label {
        color: #fbbf24;
        font-size: 14px;
        font-weight: 600;
    }

    .agent-id-value {
        color: #fbbf24;
        font-size: 18px;
        font-weight: 700;
    }

    .agent-contact-section {
        display: flex;
        gap: 32px;
        flex: 1;
    }

    .contact-column {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .contact-header {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #10b981;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .messenger-column .contact-header {
        color: #3b82f6;
    }

    .contact-header svg {
        width: 16px;
        height: 16px;
    }

    .contact-item {
        background: rgba(15, 23, 42, 0.6);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .contact-number {
        color: #e2e8f0;
        font-size: 14px;
        font-family: 'Courier New', monospace;
        flex: 1;
    }

    .copy-btn {
        background: rgba(71, 85, 105, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 6px;
        padding: 6px 8px;
        color: #cbd5e1;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .copy-btn:hover {
        background: rgba(100, 116, 139, 0.8);
        color: #ffffff;
    }

    .copy-btn.copied {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
    }

    .message-btn {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .whatsapp-message-btn {
        background: #10b981;
    }

    .whatsapp-message-btn:hover {
        background: #059669;
    }

    .messenger-message-btn {
        background: #3b82f6;
    }

    .messenger-message-btn:hover {
        background: #2563eb;
    }

    .message-btn svg {
        width: 14px;
        height: 14px;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .velki-agent-card {
            flex-direction: column;
        }

        .agent-contact-section {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .velki-agent-card {
            padding: 16px;
        }

        .agent-left-section {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .agent-name {
            font-size: 20px;
        }

        .agent-photo {
            width: 80px;
            height: 80px;
        }
    }
    </style>
    <?php
}


/**
 * Inline JavaScript for Copy Functionality
 */
function velki_agent_list_inline_js() {
    static $js_output = false;
    if ($js_output) return;
    $js_output = true;
    ?>
    <script>
    (function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.copy-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.copy-btn');
                const textToCopy = btn.getAttribute('data-copy');

                // Create temporary textarea
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();

                try {
                    document.execCommand('copy');

                    // Visual feedback
                    const originalHTML = btn.innerHTML;
                    btn.classList.add('copied');
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>';

                    setTimeout(function() {
                        btn.classList.remove('copied');
                        btn.innerHTML = originalHTML;
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                }

                document.body.removeChild(textarea);
            }
        });
    })();
    </script>
    <?php
}


/**
 * Velki Featured Agents Shortcode
 * Displays one Admin and one Sub-Admin agent in card layout
 * Usage: [velki_featured_agents]
 */
function velki_featured_agents_shortcode($atts) {
    ob_start();

    // Query for one Admin agent
    $admin_args = array(
        'post_type' => 'velki-agent',
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'agent-group',
                'field' => 'name',
                'terms' => 'এ্যাডমিন',
            ),
        ),
    );
    $admin_query = new WP_Query($admin_args);

    // Query for one Sub-Admin agent
    $subadmin_args = array(
        'post_type' => 'velki-agent',
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'agent-group',
                'field' => 'name',
                'terms' => 'সাব এ্যাডমিন',
            ),
        ),
    );
    $subadmin_query = new WP_Query($subadmin_args);

    if (!$admin_query->have_posts() && !$subadmin_query->have_posts()) {
        echo '<p>No featured agents found.</p>';
        wp_reset_postdata();
        return ob_get_clean();
    }

    echo '<div class="velki-featured-agents-container">';

    // Display Admin agent
    if ($admin_query->have_posts()) {
        while ($admin_query->have_posts()) {
            $admin_query->the_post();
            $whatsapp_url_1 = get_post_meta(get_the_ID(), '_agent_whatsapp_url_1', true);
            $phone_1 = $whatsapp_url_1 ? preg_replace('/[^0-9+]/', '', str_replace('https://wa.me/', '', $whatsapp_url_1)) : '';

            ?>
            <div class="velki-featured-card velki-featured-admin">
                <div class="velki-featured-header">
                    <div class="velki-featured-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h3 class="velki-featured-title-bn">ভেলকি এ্যাডমিন</h3>
                    <p class="velki-featured-title-en">Velki Admin</p>
                </div>

                <div class="velki-featured-contact">
                    <div class="velki-featured-contact-label">হোয়াটসঅ্যাপ নাম্বার</div>
                    <div class="velki-featured-phone"><?php echo esc_html($phone_1); ?></div>
                </div>

                <div class="velki-featured-buttons">
                    <a href="<?php echo esc_url($whatsapp_url_1); ?>" target="_blank" class="velki-featured-btn velki-featured-whatsapp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.304-1.654a11.882 11.882 0 005.713 1.456h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        ভেলকি এ্যাডমিন
                    </a>
                    <button class="velki-featured-btn velki-featured-copy" data-copy="<?php echo esc_attr($phone_1); ?>" title="Copy number">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        নাম্বার কপি করুন
                    </button>
                </div>
            </div>
            <?php
        }
    }

    // Display Sub-Admin agent
    if ($subadmin_query->have_posts()) {
        while ($subadmin_query->have_posts()) {
            $subadmin_query->the_post();
            $whatsapp_url_1 = get_post_meta(get_the_ID(), '_agent_whatsapp_url_1', true);
            $phone_1 = $whatsapp_url_1 ? preg_replace('/[^0-9+]/', '', str_replace('https://wa.me/', '', $whatsapp_url_1)) : '';

            ?>
            <div class="velki-featured-card velki-featured-subadmin">
                <div class="velki-featured-header">
                    <div class="velki-featured-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="velki-featured-title-bn">ভেলকি সাব-এ্যাডমিন</h3>
                    <p class="velki-featured-title-en">Velki Sub-Admin</p>
                </div>

                <div class="velki-featured-contact">
                    <div class="velki-featured-contact-label">হোয়াটসঅ্যাপ নাম্বার</div>
                    <div class="velki-featured-phone"><?php echo esc_html($phone_1); ?></div>
                </div>

                <div class="velki-featured-buttons">
                    <a href="<?php echo esc_url($whatsapp_url_1); ?>" target="_blank" class="velki-featured-btn velki-featured-whatsapp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.304-1.654a11.882 11.882 0 005.713 1.456h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        ভেলকি সাব-এ্যাডমিন
                    </a>
                    <button class="velki-featured-btn velki-featured-copy" data-copy="<?php echo esc_attr($phone_1); ?>" title="Copy number">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        নাম্বার কপি করুন
                    </button>
                </div>
            </div>
            <?php
        }
    }

    echo '</div>';

    wp_reset_postdata();

    // Add CSS
    velki_featured_agents_inline_css();

    // Add JavaScript
    velki_featured_agents_inline_js();

    return ob_get_clean();
}
add_shortcode('velki_featured_agents', 'velki_featured_agents_shortcode');


/**
 * Inline CSS for Featured Agents
 */
function velki_featured_agents_inline_css() {
    static $css_output = false;
    if ($css_output) return;
    $css_output = true;
    ?>
    <style>
    .velki-featured-agents-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .velki-featured-card {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
    }

    .velki-featured-admin .velki-featured-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        padding: 40px 24px;
        text-align: center;
    }

    .velki-featured-subadmin .velki-featured-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        padding: 40px 24px;
        text-align: center;
    }

    .velki-featured-icon {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .velki-featured-admin .velki-featured-icon {
        color: #f59e0b;
    }

    .velki-featured-subadmin .velki-featured-icon {
        color: #3b82f6;
    }

    .velki-featured-title-bn {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0 0 8px 0;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-featured-title-en {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    .velki-featured-contact {
        background: #1e293b;
        padding: 24px;
        text-align: center;
    }

    .velki-featured-contact-label {
        font-size: 14px;
        color: #94a3b8;
        margin-bottom: 8px;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-featured-phone {
        font-size: 24px;
        font-weight: 700;
        color: white;
        letter-spacing: 1px;
    }

    .velki-featured-buttons {
        background: #0f172a;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .velki-featured-btn {
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-featured-whatsapp {
        background: #10b981;
        color: white;
    }

    .velki-featured-whatsapp:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .velki-featured-copy {
        background: #334155;
        color: white;
    }

    .velki-featured-copy:hover {
        background: #475569;
        transform: translateY(-2px);
    }

    .velki-featured-copy.copied {
        background: #10b981;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .velki-featured-agents-container {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 16px;
        }

        .velki-featured-title-bn {
            font-size: 24px;
        }

        .velki-featured-phone {
            font-size: 20px;
        }

        .velki-featured-btn {
            font-size: 14px;
            padding: 12px 20px;
        }
    }
    </style>
    <?php
}


/**
 * Inline JavaScript for Featured Agents
 */
function velki_featured_agents_inline_js() {
    static $js_output = false;
    if ($js_output) return;
    $js_output = true;
    ?>
    <script>
    (function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.velki-featured-copy')) {
                e.preventDefault();
                const btn = e.target.closest('.velki-featured-copy');
                const textToCopy = btn.getAttribute('data-copy');

                // Create temporary textarea
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();

                try {
                    document.execCommand('copy');

                    // Visual feedback
                    const originalHTML = btn.innerHTML;
                    btn.classList.add('copied');
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> কপি হয়েছে!';

                    setTimeout(function() {
                        btn.classList.remove('copied');
                        btn.innerHTML = originalHTML;
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                }

                document.body.removeChild(textarea);
            }
        });
    })();
    </script>
    <?php
}


/**
 * WordPress Post Generator
 * Adds admin menu and functionality to generate 20 sample posts
 */

// Add admin menu
add_action('admin_menu', 'velki_post_generator_menu');
function velki_post_generator_menu() {
    add_menu_page(
        'Generate Posts',           // Page title
        'Generate Posts',           // Menu title
        'manage_options',           // Capability
        'velki-post-generator',     // Menu slug
        'velki_post_generator_page', // Callback function
        'dashicons-admin-post',     // Icon
        30                          // Position
    );
}

// Admin page content
function velki_post_generator_page() {
    ?>
    <div class="wrap">
        <h1>Generate Sample Posts</h1>
        <p>Click the button below to generate 20 sample blog posts.</p>

        <?php
        // Handle form submission
        if (isset($_POST['generate_posts']) && check_admin_referer('velki_generate_posts_action', 'velki_generate_posts_nonce')) {
            $result = velki_generate_sample_posts();
            if ($result['success']) {
                echo '<div class="notice notice-success"><p>' . esc_html($result['message']) . '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . esc_html($result['message']) . '</p></div>';
            }
        }
        ?>

        <form method="post" action="">
            <?php wp_nonce_field('velki_generate_posts_action', 'velki_generate_posts_nonce'); ?>
            <p>
                <button type="submit" name="generate_posts" class="button button-primary button-hero">
                    <span class="dashicons dashicons-admin-post" style="margin-top: 4px;"></span>
                    Generate 20 Posts
                </button>
            </p>
        </form>

        <div class="card" style="max-width: 600px; margin-top: 20px;">
            <h2>What will be generated?</h2>
            <ul>
                <li>20 sample blog posts</li>
                <li>Random titles from a predefined list</li>
                <li>Lorem ipsum content for each post</li>
                <li>Published status (immediately visible)</li>
                <li>Current date and time</li>
                <li>Default category assignment</li>
            </ul>
        </div>
    </div>

    <style>
        .button-hero .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
        }
    </style>
    <?php
}

// Generate 20 sample posts
function velki_generate_sample_posts() {
    // Sample post titles
    $titles = array(
        'Getting Started with WordPress',
        'Top 10 Web Design Trends',
        'How to Improve Your Website Speed',
        'Essential SEO Tips for Beginners',
        'The Future of Web Development',
        'Best Practices for Mobile Design',
        'Understanding User Experience',
        'Creating Engaging Content',
        'WordPress Security Best Practices',
        'Building Responsive Websites',
        'Introduction to CSS Grid',
        'JavaScript Fundamentals',
        'Optimizing Images for Web',
        'Email Marketing Strategies',
        'Social Media Marketing Tips',
        'Content Marketing Guide',
        'Web Accessibility Basics',
        'E-commerce Website Design',
        'Digital Marketing Trends',
        'Website Analytics Explained',
    );

    // Sample content paragraphs
    $content_paragraphs = array(
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
        'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.',
    );

    $created_count = 0;
    $errors = array();

    for ($i = 0; $i < 20; $i++) {
        // Get random title (cycle through if needed)
        $title = $titles[$i % count($titles)];
        if ($i >= count($titles)) {
            $title .= ' - Part ' . (floor($i / count($titles)) + 1);
        }

        // Generate random content (3-5 paragraphs)
        $num_paragraphs = rand(3, 5);
        $content = '';
        for ($j = 0; $j < $num_paragraphs; $j++) {
            $content .= '<p>' . $content_paragraphs[array_rand($content_paragraphs)] . '</p>' . "\n\n";
        }

        // Create post
        $post_data = array(
            'post_title'    => $title,
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_type'     => 'post',
            'post_category' => array(1), // Default category
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            $errors[] = $post_id->get_error_message();
        } else {
            $created_count++;
        }
    }

    if ($created_count === 20) {
        return array(
            'success' => true,
            'message' => 'Successfully generated 20 posts!'
        );
    } elseif ($created_count > 0) {
        return array(
            'success' => true,
            'message' => "Generated {$created_count} posts. " . count($errors) . " failed."
        );
    } else {
        return array(
            'success' => false,
            'message' => 'Failed to generate posts. Errors: ' . implode(', ', $errors)
        );
    }
}


/**
 * Velki Blog Listing Shortcode with Load More
 * Usage: [velki_blog_listing posts_per_page="6"]
 */
function velki_blog_listing_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 6,
            'category' => '',
        ),
        $atts,
        'velki_blog_listing'
    );

    ob_start();

    echo '<div class="velki-blog-listing-wrapper">';
    echo '<div class="velki-blog-grid" id="velki-blog-grid" data-page="1" data-posts-per-page="' . esc_attr($atts['posts_per_page']) . '" data-category="' . esc_attr($atts['category']) . '">';

    // Initial posts query
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => intval($atts['posts_per_page']),
        'paged' => 1,
        'post_status' => 'publish',
    );

    if (!empty($atts['category'])) {
        $args['category_name'] = $atts['category'];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            velki_blog_card_template();
        }

        // Show load more button if there are more posts
        if ($query->max_num_pages > 1) {
            echo '</div>'; // Close grid
            echo '<div class="velki-blog-load-more-wrapper">';
            echo '<button class="velki-blog-load-more" data-max-pages="' . esc_attr($query->max_num_pages) . '">';
            echo '<span class="dashicons dashicons-update"></span> আরও ব্লগ দেখুন';
            echo '</button>';
            echo '<div class="velki-blog-loading" style="display: none;">লোড হচ্ছে...</div>';
            echo '</div>';
        } else {
            echo '</div>'; // Close grid
        }
    } else {
        echo '<p>কোন ব্লগ পোস্ট পাওয়া যায়নি।</p>';
        echo '</div>'; // Close grid
    }

    wp_reset_postdata();

    echo '</div>'; // Close wrapper

    // Add CSS and JS
    velki_blog_listing_inline_css();
    velki_blog_listing_inline_js();

    return ob_get_clean();
}
add_shortcode('velki_blog_listing', 'velki_blog_listing_shortcode');


/**
 * Blog card template
 */
function velki_blog_card_template() {
    $categories = get_the_category();
    $first_category = !empty($categories) ? $categories[0] : null;

    // Category colors
    $category_colors = array(
        'yellow' => '#eab308',
        'blue' => '#3b82f6',
        'purple' => '#a855f7',
        'green' => '#10b981',
        'orange' => '#f97316',
        'pink' => '#ec4899',
    );

    $color_keys = array_keys($category_colors);
    $category_color = $first_category ? $category_colors[$color_keys[($first_category->term_id - 1) % count($color_keys)]] : '#eab308';

    ?>
    <article class="velki-blog-card">
        <div class="velki-blog-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large'); ?>
            <?php else : ?>
                <div class="velki-blog-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span>No Image</span>
                </div>
            <?php endif; ?>
            <?php if ($first_category) : ?>
                <span class="velki-blog-category" style="background-color: <?php echo esc_attr($category_color); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                    <?php echo esc_html($first_category->name); ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="velki-blog-content">
            <h3 class="velki-blog-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <div class="velki-blog-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
            </div>

            <div class="velki-blog-meta">
                <div class="velki-blog-meta-left">
                    <span class="velki-blog-date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <?php echo get_the_date('j F, Y'); ?>
                    </span>
                    <span class="velki-blog-author">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <?php echo get_the_author(); ?>
                    </span>
                </div>

                <a href="<?php the_permalink(); ?>" class="velki-blog-read-more">
                    আরও পড়ুন
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>
    </article>
    <?php
}


/**
 * AJAX handler for load more
 */
add_action('wp_ajax_velki_load_more_posts', 'velki_load_more_posts');
add_action('wp_ajax_nopriv_velki_load_more_posts', 'velki_load_more_posts');

function velki_load_more_posts() {
    check_ajax_referer('velki_blog_nonce', 'nonce');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 6;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
    );

    if (!empty($category)) {
        $args['category_name'] = $category;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            velki_blog_card_template();
        }
        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html,
            'max_pages' => $query->max_num_pages,
            'current_page' => $page,
        ));
    } else {
        wp_send_json_error(array('message' => 'No more posts'));
    }

    wp_reset_postdata();
    wp_die();
}


/**
 * Inline CSS for Blog Listing
 */
function velki_blog_listing_inline_css() {
    static $css_output = false;
    if ($css_output) return;
    $css_output = true;
    ?>
    <style>
    .velki-blog-listing-wrapper {
        padding: 40px 20px;
        background: #0f172a;
        min-height: 400px;
    }

    .velki-blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .velki-blog-card {
        background: #1e293b;
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .velki-blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
    }

    .velki-blog-image {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/10;
    }

    .velki-blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .velki-blog-card:hover .velki-blog-image img {
        transform: scale(1.1);
    }

    .velki-blog-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: #64748b;
    }

    .velki-blog-placeholder svg {
        opacity: 0.5;
    }

    .velki-blog-placeholder span {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .velki-blog-category {
        position: absolute;
        top: 16px;
        left: 16px;
        padding: 6px 14px;
        border-radius: 20px;
        color: white;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-blog-content {
        padding: 24px;
    }

    .velki-blog-title {
        margin: 0 0 12px 0;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-blog-title a {
        color: white;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .velki-blog-title a:hover {
        color: #eab308;
    }

    .velki-blog-excerpt {
        color: #94a3b8;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 16px;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-blog-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #334155;
    }

    .velki-blog-meta-left {
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .velki-blog-date,
    .velki-blog-author {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-size: 13px;
    }

    .velki-blog-date svg,
    .velki-blog-author svg {
        opacity: 0.7;
    }

    .velki-blog-read-more {
        color: #eab308;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.3s ease;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-blog-read-more:hover {
        gap: 10px;
    }

    .velki-blog-load-more-wrapper {
        text-align: center;
        margin-top: 40px;
    }

    .velki-blog-load-more {
        background: #eab308;
        color: #0f172a;
        border: none;
        padding: 16px 32px;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    .velki-blog-load-more .dashicons {
        font-size: 20px;
        width: 20px;
        height: 20px;
    }

    .velki-blog-load-more:hover {
        background: #ca8a04;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(234, 179, 8, 0.3);
    }

    .velki-blog-load-more:disabled {
        background: #334155;
        color: #64748b;
        cursor: not-allowed;
        transform: none;
    }

    .velki-blog-loading {
        color: #eab308;
        font-size: 16px;
        margin-top: 16px;
        font-family: 'Noto Sans Bengali', sans-serif;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .velki-blog-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 640px) {
        .velki-blog-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .velki-blog-listing-wrapper {
            padding: 24px 16px;
        }

        .velki-blog-title {
            font-size: 18px;
        }

        .velki-blog-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }
    </style>
    <?php
}


/**
 * Inline JavaScript for Blog Listing
 */
function velki_blog_listing_inline_js() {
    static $js_output = false;
    if ($js_output) return;
    $js_output = true;
    ?>
    <script>
    (function($) {
        $(document).on('click', '.velki-blog-load-more', function() {
            var button = $(this);
            var grid = $('#velki-blog-grid');
            var currentPage = parseInt(grid.attr('data-page'));
            var maxPages = parseInt(button.attr('data-max-pages'));
            var postsPerPage = grid.attr('data-posts-per-page');
            var category = grid.attr('data-category');
            var loadingDiv = $('.velki-blog-loading');

            if (currentPage >= maxPages) {
                return;
            }

            var nextPage = currentPage + 1;

            // Show loading
            button.prop('disabled', true);
            loadingDiv.show();

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'velki_load_more_posts',
                    nonce: '<?php echo wp_create_nonce('velki_blog_nonce'); ?>',
                    page: nextPage,
                    posts_per_page: postsPerPage,
                    category: category
                },
                success: function(response) {
                    if (response.success) {
                        grid.append(response.data.html);
                        grid.attr('data-page', nextPage);

                        // Hide button if no more pages
                        if (nextPage >= response.data.max_pages) {
                            button.fadeOut();
                        }
                    }
                },
                error: function() {
                    alert('একটি ত্রুটি ঘটেছে। আবার চেষ্টা করুন।');
                },
                complete: function() {
                    button.prop('disabled', false);
                    loadingDiv.hide();
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * Enhanced search for velki-agent post type
 *
 * Search Behavior:
 * - Searches the FULL search string (not word-by-word)
 * - Matches if the complete search string appears ANYWHERE in the data
 * - Minimum 3 characters required for search
 *
 * Searchable Fields:
 * - Post Title/Name
 * - Agent ID (_agent_id)
 * - WhatsApp Message URL 1 (_agent_whatsapp_url_1)
 * - WhatsApp Message URL 2 (_agent_whatsapp_url_2)
 * - Messenger Message URL (_agent_messenger_url)
 *
 * Examples:
 * - Search "804" → Finds "18049722549" in WhatsApp URL
 * - Search "velki" → Finds "velkiagents.pro" in Messenger URL
 * - Search "wa.me" → Finds "https://wa.me/..." in WhatsApp URLs
 * - Search "মাস্টার" → Finds "মাস্টার এজেন্ট" in title
 * - Search "180497" → Finds "18049722549" (full string match within data)
 */
function wicket_velki_agent_enhanced_search( $query ) {
    // Only for velki-agent search on frontend
    if ( ! $query->is_search() || is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // Set post type to velki-agent
    $query->set( 'post_type', 'velki-agent' );

    $search_term = $query->get( 's' );

    // Only proceed if search term has 3 or more characters
    if ( empty( $search_term ) || strlen( $search_term ) < 3 ) {
        return;
    }

    // Get all velki-agent posts that match meta fields or title
    global $wpdb;

    $meta_keys = array(
        '_agent_id',
        '_agent_whatsapp_url_1',
        '_agent_whatsapp_url_2',
        '_agent_messenger_url'
    );

    // Build SQL to find posts matching any meta field OR title
    $meta_conditions = array();
    foreach ( $meta_keys as $meta_key ) {
        $meta_conditions[] = $wpdb->prepare(
            "(pm.meta_key = %s AND pm.meta_value LIKE %s)",
            $meta_key,
            '%' . $wpdb->esc_like( $search_term ) . '%'
        );
    }

    // Combined SQL for both title and meta search with relevance scoring
    // Scoring system (highest to lowest priority):
    // - Exact match in WhatsApp/Messenger URL: 150 points (phone numbers)
    // - Exact match in title: 100 points
    // - Exact match in agent ID: 100 points
    // - WhatsApp/Messenger URL contains search term: 90 points
    // - Title starts with search term: 50 points
    // - Agent ID contains search term: 40 points
    // - Title contains search term: 25 points
    $search_sql = $wpdb->prepare(
        "
        SELECT DISTINCT p.ID,
        (
            CASE
                WHEN p.post_title = %s THEN 100
                WHEN p.post_title LIKE %s THEN 50
                WHEN p.post_title LIKE %s THEN 25
                ELSE 0
            END
            +
            CASE
                WHEN (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = p.ID AND meta_key = '_agent_id' LIMIT 1) = %s THEN 100
                WHEN (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = p.ID AND meta_key = '_agent_id' LIMIT 1) LIKE %s THEN 40
                ELSE 0
            END
            +
            CASE
                WHEN (SELECT COUNT(*) FROM {$wpdb->postmeta}
                      WHERE post_id = p.ID
                      AND meta_key IN ('_agent_whatsapp_url_1', '_agent_whatsapp_url_2', '_agent_messenger_url')
                      AND (
                          meta_value LIKE CONCAT('%%/', %s)
                          OR meta_value LIKE CONCAT('%%/', %s, '%%')
                          OR meta_value = %s
                      )
                     ) > 0 THEN 150
                WHEN (SELECT COUNT(*) FROM {$wpdb->postmeta}
                      WHERE post_id = p.ID
                      AND meta_key IN ('_agent_whatsapp_url_1', '_agent_whatsapp_url_2', '_agent_messenger_url')
                      AND meta_value LIKE %s
                     ) > 0 THEN 90
                ELSE 0
            END
        ) AS relevance_score
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'velki-agent'
        AND p.post_status = 'publish'
        AND (
            p.post_title LIKE %s
            OR (" . implode( ' OR ', $meta_conditions ) . ")
        )
        ORDER BY relevance_score DESC, p.post_title ASC
        ",
        $search_term,                                           // Exact title match
        $wpdb->esc_like( $search_term ) . '%',                 // Title starts with
        '%' . $wpdb->esc_like( $search_term ) . '%',          // Title contains
        $search_term,                                           // Exact agent ID
        '%' . $wpdb->esc_like( $search_term ) . '%',          // Agent ID contains
        $search_term,                                           // URL ends with search term (exact phone match)
        $search_term,                                           // URL has /search_term/ pattern
        $search_term,                                           // URL exact match
        '%' . $wpdb->esc_like( $search_term ) . '%',          // URL contains anywhere
        '%' . $wpdb->esc_like( $search_term ) . '%'           // Main search condition
    );

    $results = $wpdb->get_results( $search_sql );
    $post_ids = wp_list_pluck( $results, 'ID' );

    // If we found matching posts, set them as the search results
    if ( ! empty( $post_ids ) ) {
        $query->set( 'post__in', $post_ids );
        // Preserve the order from our custom query (best match first)
        $query->set( 'orderby', 'post__in' );
        $query->set( 'posts_per_page', -1 );
        // Keep search term but clear the search SQL to prevent WordPress from filtering again
        add_filter( 'posts_search', '__return_empty_string', 20, 2 );
    } else {
        // No results found, force empty result
        $query->set( 'post__in', array( 0 ) );
        add_filter( 'posts_search', '__return_empty_string', 20, 2 );
    }
}
add_action( 'pre_get_posts', 'wicket_velki_agent_enhanced_search', 15 );
