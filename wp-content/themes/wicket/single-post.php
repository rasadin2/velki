<?php get_header(); ?>

<div class="blog-container">
    <div class="blog-row">
        <div class="blog-col-8">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="f-image">
                     <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>

            <?php
                if ( have_posts() ) :
                     while ( have_posts() ) :
                            the_post();
                                ?>
                                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                        <div class="b-content">
                                            <h1 class="b-heading"><?php the_title(); ?></h1>
                                            <div class="entry-content">
                                                <?php the_content(); ?>
                                            </div>
                                        </div>
                                    </article>
                                <?php
                    endwhile;
                else :
                    ?>
                        <p>No content found.</p>
                    <?php endif; ?>
        </div>
        <div class="blog-col-4 bl-sitebar">
            <?php if ( is_active_sidebar( 'single-blog-page-widget' ) ) : ?>
                 <?php dynamic_sidebar( 'single-blog-page-widget' ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>