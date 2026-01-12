<?php
/**
 * Template for displaying single blog posts
 *
 * @package wicket
 */

get_header();
?>

<?php
// Use the new blog details template
if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        // Load the blog details template with enhanced styling
        get_template_part( 'template-parts/post/content', 'single' );

    endwhile;
else :
    ?>
    <div class="blog-container">
        <p><?php _e('No content found.', 'wicket'); ?></p>
    </div>
    <?php
endif;
?>

<?php get_footer(); ?>