<?php
/**
 * Custom Post Type: Offer List
 *
 * @package wicket
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Custom Post Type: Offer List
 */
function wicket_register_offer_list_post_type() {
    $labels = array(
        'name'                  => _x( 'Offer List', 'Post Type General Name', 'wicket' ),
        'singular_name'         => _x( 'Offer', 'Post Type Singular Name', 'wicket' ),
        'menu_name'             => __( 'Offer List', 'wicket' ),
        'name_admin_bar'        => __( 'Offer', 'wicket' ),
        'archives'              => __( 'Offer Archives', 'wicket' ),
        'attributes'            => __( 'Offer Attributes', 'wicket' ),
        'parent_item_colon'     => __( 'Parent Offer:', 'wicket' ),
        'all_items'             => __( 'All Offers', 'wicket' ),
        'add_new_item'          => __( 'Add New Offer', 'wicket' ),
        'add_new'               => __( 'Add New', 'wicket' ),
        'new_item'              => __( 'New Offer', 'wicket' ),
        'edit_item'             => __( 'Edit Offer', 'wicket' ),
        'update_item'           => __( 'Update Offer', 'wicket' ),
        'view_item'             => __( 'View Offer', 'wicket' ),
        'view_items'            => __( 'View Offers', 'wicket' ),
        'search_items'          => __( 'Search Offer', 'wicket' ),
        'not_found'             => __( 'Not found', 'wicket' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'wicket' ),
        'featured_image'        => __( 'Offer Image', 'wicket' ),
        'set_featured_image'    => __( 'Set offer image', 'wicket' ),
        'remove_featured_image' => __( 'Remove offer image', 'wicket' ),
        'use_featured_image'    => __( 'Use as offer image', 'wicket' ),
        'insert_into_item'      => __( 'Insert into offer', 'wicket' ),
        'uploaded_to_this_item' => __( 'Uploaded to this offer', 'wicket' ),
        'items_list'            => __( 'Offers list', 'wicket' ),
        'items_list_navigation' => __( 'Offers list navigation', 'wicket' ),
        'filter_items_list'     => __( 'Filter offers list', 'wicket' ),
    );

    $args = array(
        'label'                 => __( 'Offer', 'wicket' ),
        'description'           => __( 'Offer list items', 'wicket' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'excerpt' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-tickets-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type( 'offer-list', $args );
}
add_action( 'init', 'wicket_register_offer_list_post_type', 0 );

/**
 * Register Meta Boxes for Offer List
 */
function wicket_offer_list_meta_boxes() {
    add_meta_box(
        'offer_details',
        __( 'Offer Details', 'wicket' ),
        'wicket_offer_details_callback',
        'offer-list',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wicket_offer_list_meta_boxes' );

/**
 * Meta Box Callback: Offer Details
 */
function wicket_offer_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'wicket_offer_details_nonce', 'wicket_offer_details_nonce_field' );

    // Retrieve existing values
    $offer_title = get_post_meta( $post->ID, '_offer_title', true );
    $offer_badge = get_post_meta( $post->ID, '_offer_badge', true );
    $offer_icon = get_post_meta( $post->ID, '_offer_icon', true );
    $offer_link = get_post_meta( $post->ID, '_offer_link', true );
    $offer_prize = get_post_meta( $post->ID, '_offer_prize', true );
    $offer_description = get_post_meta( $post->ID, '_offer_description', true );
    $offer_button_text = get_post_meta( $post->ID, '_offer_button_text', true );
    $offer_button_color = get_post_meta( $post->ID, '_offer_button_color', true );
    $offer_icon_bg_color = get_post_meta( $post->ID, '_offer_icon_bg_color', true );

    ?>
    <style>
        .offer-meta-field {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2271b1;
        }
        .offer-meta-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1d2327;
        }
        .offer-meta-field input[type="text"],
        .offer-meta-field input[type="url"],
        .offer-meta-field textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .offer-meta-field textarea {
            min-height: 100px;
            resize: vertical;
        }
        .offer-meta-field input[type="color"] {
            height: 40px;
            width: 100px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        .offer-icon-preview {
            margin-top: 10px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .offer-icon-preview img {
            max-width: 100px;
            max-height: 100px;
            display: block;
            margin: 0 auto;
        }
        .offer-upload-button,
        .offer-remove-button {
            margin-top: 10px;
            padding: 8px 16px;
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .offer-upload-button:hover {
            background: #135e96;
        }
        .offer-remove-button {
            background: #d63638;
            margin-left: 10px;
        }
        .offer-remove-button:hover {
            background: #b02a2c;
        }
        .offer-field-description {
            font-size: 13px;
            color: #646970;
            font-style: italic;
            margin-top: 5px;
        }
        .color-picker-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .color-value {
            font-family: monospace;
            background: #fff;
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
    </style>

    <div class="offer-meta-fields-wrapper">

        <!-- Offer Title (Bengali) -->
        <div class="offer-meta-field">
            <label for="offer_title"><?php _e( 'Offer Title (Bengali)', 'wicket' ); ?></label>
            <input type="text" id="offer_title" name="offer_title" value="<?php echo esc_attr( $offer_title ); ?>" placeholder="দৈনিক বোনাস" />
            <p class="offer-field-description"><?php _e( 'Enter the offer title in Bengali (e.g., দৈনিক বোনাস, সুপার জ্যাকপট)', 'wicket' ); ?></p>
        </div>

        <!-- Offer Badge (Bengali) -->
        <div class="offer-meta-field">
            <label for="offer_badge"><?php _e( 'Offer Badge Text (Bengali)', 'wicket' ); ?></label>
            <input type="text" id="offer_badge" name="offer_badge" value="<?php echo esc_attr( $offer_badge ); ?>" placeholder="আজকের অফার" />
            <p class="offer-field-description"><?php _e( 'Enter the badge text that appears on the card header (e.g., আজকের অফার, নতুন). Leave empty to use offer title.', 'wicket' ); ?></p>
        </div>

        <!-- Offer Icon -->
        <div class="offer-meta-field">
            <label for="offer_icon"><?php _e( 'Offer Icon', 'wicket' ); ?></label>
            <input type="hidden" id="offer_icon" name="offer_icon" value="<?php echo esc_attr( $offer_icon ); ?>" />

            <div class="offer-icon-preview" id="offer-icon-preview">
                <?php if ( $offer_icon ) : ?>
                    <img src="<?php echo esc_url( $offer_icon ); ?>" alt="Offer Icon" />
                <?php else : ?>
                    <p style="color: #646970;"><?php _e( 'No icon selected', 'wicket' ); ?></p>
                <?php endif; ?>
            </div>

            <button type="button" class="offer-upload-button" id="upload-offer-icon">
                <?php _e( 'Upload/Choose Icon', 'wicket' ); ?>
            </button>
            <button type="button" class="offer-remove-button" id="remove-offer-icon" <?php echo $offer_icon ? '' : 'style="display:none;"'; ?>>
                <?php _e( 'Remove Icon', 'wicket' ); ?>
            </button>
            <p class="offer-field-description"><?php _e( 'Upload or choose an icon for the offer (recommended size: 100x100px)', 'wicket' ); ?></p>
        </div>

        <!-- Icon Background Color -->
        <div class="offer-meta-field">
            <label for="offer_icon_bg_color"><?php _e( 'Icon Background Color', 'wicket' ); ?></label>
            <div class="color-picker-wrapper">
                <input type="color" id="offer_icon_bg_color" name="offer_icon_bg_color" value="<?php echo esc_attr( $offer_icon_bg_color ? $offer_icon_bg_color : '#f39c12' ); ?>" />
                <span class="color-value"><?php echo esc_html( $offer_icon_bg_color ? $offer_icon_bg_color : '#f39c12' ); ?></span>
            </div>
            <p class="offer-field-description"><?php _e( 'Choose the background color for the icon container', 'wicket' ); ?></p>
        </div>

        <!-- Offer Description -->
        <div class="offer-meta-field">
            <label for="offer_description"><?php _e( 'Offer Description (Bengali)', 'wicket' ); ?></label>
            <textarea id="offer_description" name="offer_description" placeholder="প্রতিদিনের বিশেষ বোনাস অফার পান"><?php echo esc_textarea( $offer_description ); ?></textarea>
            <p class="offer-field-description"><?php _e( 'Enter a brief description of the offer in Bengali', 'wicket' ); ?></p>
        </div>

        <!-- Offer Prize -->
        <div class="offer-meta-field">
            <label for="offer_prize"><?php _e( 'Prize Amount (Bengali)', 'wicket' ); ?></label>
            <input type="text" id="offer_prize" name="offer_prize" value="<?php echo esc_attr( $offer_prize ); ?>" placeholder="১০,০০০ টাকা" />
            <p class="offer-field-description"><?php _e( 'Enter the prize amount in Bengali with formatting (e.g., ১০,০০০ টাকা)', 'wicket' ); ?></p>
        </div>

        <!-- Offer Link -->
        <div class="offer-meta-field">
            <label for="offer_link"><?php _e( 'Offer Link URL', 'wicket' ); ?></label>
            <input type="url" id="offer_link" name="offer_link" value="<?php echo esc_url( $offer_link ); ?>" placeholder="https://example.com/offer" />
            <p class="offer-field-description"><?php _e( 'Enter the full URL where users will be redirected when clicking the offer', 'wicket' ); ?></p>
        </div>

        <!-- Button Text -->
        <div class="offer-meta-field">
            <label for="offer_button_text"><?php _e( 'Button Text (Bengali)', 'wicket' ); ?></label>
            <input type="text" id="offer_button_text" name="offer_button_text" value="<?php echo esc_attr( $offer_button_text ); ?>" placeholder="এখনই যুক্ত হন" />
            <p class="offer-field-description"><?php _e( 'Enter the button text in Bengali (e.g., এখনই যুক্ত হন)', 'wicket' ); ?></p>
        </div>

        <!-- Button Color -->
        <div class="offer-meta-field">
            <label for="offer_button_color"><?php _e( 'Button Gradient Color', 'wicket' ); ?></label>
            <div class="color-picker-wrapper">
                <input type="color" id="offer_button_color" name="offer_button_color" value="<?php echo esc_attr( $offer_button_color ? $offer_button_color : '#f39c12' ); ?>" />
                <span class="color-value"><?php echo esc_html( $offer_button_color ? $offer_button_color : '#f39c12' ); ?></span>
            </div>
            <p class="offer-field-description"><?php _e( 'Choose the gradient color for the call-to-action button', 'wicket' ); ?></p>
        </div>

    </div>

    <script>
    jQuery(document).ready(function($) {
        // Media uploader for icon
        var iconUploader;

        $('#upload-offer-icon').click(function(e) {
            e.preventDefault();

            if (iconUploader) {
                iconUploader.open();
                return;
            }

            iconUploader = wp.media({
                title: '<?php _e( 'Choose Offer Icon', 'wicket' ); ?>',
                button: {
                    text: '<?php _e( 'Use this icon', 'wicket' ); ?>'
                },
                multiple: false
            });

            iconUploader.on('select', function() {
                var attachment = iconUploader.state().get('selection').first().toJSON();
                $('#offer_icon').val(attachment.url);
                $('#offer-icon-preview').html('<img src="' + attachment.url + '" alt="Offer Icon" />');
                $('#remove-offer-icon').show();
            });

            iconUploader.open();
        });

        // Remove icon
        $('#remove-offer-icon').click(function(e) {
            e.preventDefault();
            $('#offer_icon').val('');
            $('#offer-icon-preview').html('<p style="color: #646970;"><?php _e( 'No icon selected', 'wicket' ); ?></p>');
            $(this).hide();
        });

        // Update color value display
        $('#offer_button_color, #offer_icon_bg_color').on('input', function() {
            $(this).siblings('.color-value').text($(this).val());
        });
    });
    </script>
    <?php
}

/**
 * Save Meta Box Data
 */
function wicket_save_offer_details( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['wicket_offer_details_nonce_field'] ) ||
         ! wp_verify_nonce( $_POST['wicket_offer_details_nonce_field'], 'wicket_offer_details_nonce' ) ) {
        return;
    }

    // Prevent autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save meta fields
    $meta_fields = array(
        'offer_title'          => 'sanitize_text_field',
        'offer_badge'          => 'sanitize_text_field',
        'offer_icon'           => 'esc_url_raw',
        'offer_link'           => 'esc_url_raw',
        'offer_prize'          => 'sanitize_text_field',
        'offer_description'    => 'sanitize_textarea_field',
        'offer_button_text'    => 'sanitize_text_field',
        'offer_button_color'   => 'sanitize_hex_color',
        'offer_icon_bg_color'  => 'sanitize_hex_color',
    );

    foreach ( $meta_fields as $field => $sanitize_callback ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = call_user_func( $sanitize_callback, $_POST[ $field ] );
            update_post_meta( $post_id, '_' . $field, $value );
        } else {
            delete_post_meta( $post_id, '_' . $field );
        }
    }
}
add_action( 'save_post', 'wicket_save_offer_details' );

/**
 * Enqueue Media Uploader
 */
function wicket_offer_list_enqueue_media() {
    global $post_type;
    if ( 'offer-list' === $post_type ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'wicket_offer_list_enqueue_media' );

/**
 * Add custom columns to Offer List admin
 */
function wicket_offer_list_custom_columns( $columns ) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumbnail'] = __( 'Icon', 'wicket' );
    $new_columns['title'] = $columns['title'];
    $new_columns['offer_title'] = __( 'Offer Title', 'wicket' );
    $new_columns['prize'] = __( 'Prize', 'wicket' );
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter( 'manage_offer-list_posts_columns', 'wicket_offer_list_custom_columns' );

/**
 * Display custom column content
 */
function wicket_offer_list_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'thumbnail':
            $icon = get_post_meta( $post_id, '_offer_icon', true );
            if ( $icon ) {
                echo '<img src="' . esc_url( $icon ) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" />';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;

        case 'offer_title':
            $offer_title = get_post_meta( $post_id, '_offer_title', true );
            echo $offer_title ? esc_html( $offer_title ) : '<span style="color: #999;">—</span>';
            break;

        case 'prize':
            $prize = get_post_meta( $post_id, '_offer_prize', true );
            echo $prize ? esc_html( $prize ) : '<span style="color: #999;">—</span>';
            break;
    }
}
add_action( 'manage_offer-list_posts_custom_column', 'wicket_offer_list_custom_column_content', 10, 2 );

/**
 * Shortcode: Display Offer List
 * Usage: [offer_list limit="3"]
 */
function wicket_offer_list_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'limit' => -1,
            'order' => 'DESC',
            'orderby' => 'date',
        ),
        $atts,
        'offer_list'
    );

    $args = array(
        'post_type'      => 'offer-list',
        'posts_per_page' => intval( $atts['limit'] ),
        'order'          => $atts['order'],
        'orderby'        => $atts['orderby'],
        'post_status'    => 'publish',
    );

    $offers = new WP_Query( $args );

    ob_start();

    if ( $offers->have_posts() ) :
        ?>
        <style>
            .offer-list-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                padding: 20px 0;
            }

            .offer-card {
                background: #ffffff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                position: relative;
            }

            .offer-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            }

            .offer-header {
                position: relative;
                height: 200px;
                overflow: hidden;
            }

            .offer-header img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .offer-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #fff;
                padding: 8px 20px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .offer-badge::before {
                content: '🎁';
                font-size: 16px;
            }

            .offer-body {
                padding: 25px;
            }

            .offer-icon-wrapper {
                width: 70px;
                height: 70px;
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 15px;
            }

            .offer-icon-wrapper img {
                width: 40px;
                height: 40px;
                object-fit: contain;
            }

            .offer-title {
                font-size: 24px;
                font-weight: 700;
                color: #1d2327;
                margin-bottom: 10px;
                line-height: 1.3;
            }

            .offer-description {
                font-size: 15px;
                color: #646970;
                margin-bottom: 20px;
                line-height: 1.6;
            }

            .offer-prize-section {
                background: #f0f9ff;
                border-left: 4px solid #3b82f6;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 4px;
            }

            .offer-prize-label {
                font-size: 13px;
                color: #646970;
                margin-bottom: 5px;
            }

            .offer-prize-amount {
                font-size: 28px;
                font-weight: 700;
                color: #16a34a;
            }

            .offer-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 14px 24px;
                background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
                color: #fff;
                text-decoration: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }

            .offer-button:hover {
                transform: scale(1.02);
                box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
                color: #fff;
            }

            .offer-button::after {
                content: '→';
                margin-left: 8px;
                transition: margin-left 0.3s ease;
            }

            .offer-button:hover::after {
                margin-left: 12px;
            }

            @media (max-width: 768px) {
                .offer-list-container {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="offer-list-container">
            <?php
            while ( $offers->have_posts() ) :
                $offers->the_post();

                $offer_title = get_post_meta( get_the_ID(), '_offer_title', true );
                $offer_badge = get_post_meta( get_the_ID(), '_offer_badge', true );
                $offer_icon = get_post_meta( get_the_ID(), '_offer_icon', true );
                $offer_link = get_post_meta( get_the_ID(), '_offer_link', true );
                $offer_prize = get_post_meta( get_the_ID(), '_offer_prize', true );
                $offer_description = get_post_meta( get_the_ID(), '_offer_description', true );
                $offer_button_text = get_post_meta( get_the_ID(), '_offer_button_text', true );
                $offer_button_color = get_post_meta( get_the_ID(), '_offer_button_color', true );
                $offer_icon_bg_color = get_post_meta( get_the_ID(), '_offer_icon_bg_color', true );

                // Get featured image
                $featured_image = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
                if ( ! $featured_image ) {
                    $featured_image = get_template_directory_uri() . '/assets/img/inner-banner.png';
                }

                // Default values
                $offer_button_text = $offer_button_text ? $offer_button_text : 'এখনই যুক্ত হন';
                $offer_button_color = $offer_button_color ? $offer_button_color : '#f39c12';
                $offer_icon_bg_color = $offer_icon_bg_color ? $offer_icon_bg_color : '#f39c12';
                $display_badge = $offer_badge ? $offer_badge : $offer_title; // Use badge if set, otherwise use title
                ?>

                <div class="offer-card">
                    <div class="offer-header">
                        <img src="<?php echo esc_url( $featured_image ); ?>" alt="<?php echo esc_attr( $offer_title ); ?>" />
                        <div class="offer-badge"><?php echo esc_html( $display_badge ); ?></div>
                    </div>

                    <div class="offer-body">
                        <?php if ( $offer_icon ) : ?>
                            <div class="offer-icon-wrapper" style="background-color: <?php echo esc_attr( $offer_icon_bg_color ); ?>;">
                                <img src="<?php echo esc_url( $offer_icon ); ?>" alt="<?php echo esc_attr( $offer_title ); ?>" />
                            </div>
                        <?php endif; ?>

                        <h3 class="offer-title"><?php echo esc_html( $offer_title ); ?></h3>

                        <?php if ( $offer_description ) : ?>
                            <p class="offer-description"><?php echo esc_html( $offer_description ); ?></p>
                        <?php endif; ?>

                        <?php if ( $offer_prize ) : ?>
                            <div class="offer-prize-section">
                                <div class="offer-prize-label">পুরস্কার</div>
                                <div class="offer-prize-amount"><?php echo esc_html( $offer_prize ); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ( $offer_link ) : ?>
                            <a href="<?php echo esc_url( $offer_link ); ?>"
                               class="offer-button"
                               style="background: linear-gradient(135deg, <?php echo esc_attr( $offer_button_color ); ?> 0%, <?php echo esc_attr( $offer_button_color ); ?>dd 100%);"
                               target="_blank"
                               rel="noopener noreferrer">
                                <?php echo esc_html( $offer_button_text ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        <?php
    else :
        echo '<p>' . __( 'No offers found.', 'wicket' ) . '</p>';
    endif;

    return ob_get_clean();
}
add_shortcode( 'offer_list', 'wicket_offer_list_shortcode' );

/**
 * Shortcode: Display Offer List Slider
 * Usage: [offer_list_slider desktop_items="3" mobile_items="1" autoplay="true" autoplay_speed="3000"]
 */
function wicket_offer_list_slider_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'desktop_items' => 3,
            'mobile_items'  => 1,
            'autoplay'      => 'true',
            'autoplay_speed' => 3000,
            'limit'         => -1,
            'order'         => 'DESC',
            'orderby'       => 'date',
        ),
        $atts,
        'offer_list_slider'
    );

    $args = array(
        'post_type'      => 'offer-list',
        'posts_per_page' => intval( $atts['limit'] ),
        'order'          => $atts['order'],
        'orderby'        => $atts['orderby'],
        'post_status'    => 'publish',
    );

    $offers = new WP_Query( $args );

    if ( ! $offers->have_posts() ) {
        return '<p>' . __( 'No offers found.', 'wicket' ) . '</p>';
    }

    // Generate unique ID for this slider instance
    $slider_id = 'offer-slider-' . uniqid();
    $desktop_items = intval( $atts['desktop_items'] );
    $mobile_items = intval( $atts['mobile_items'] );
    $autoplay = $atts['autoplay'] === 'true' ? 'true' : 'false';
    $autoplay_speed = intval( $atts['autoplay_speed'] );

    // Calculate CSS widths
    $desktop_gap_total = 30 * ($desktop_items - 1);
    $mobile_gap_total = 30 * ($mobile_items - 1);

    ob_start();
    ?>

    <style>
        .offer-slider-wrapper {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            box-sizing: border-box;
        }

        .offer-slider-navigation-top {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .offer-slider-nav-text {
            font-size: 18px;
            font-weight: 600;
            color: #1d2327;
            user-select: none;
        }

        .offer-slider-container {
            position: relative;
            overflow: hidden;
            padding: 20px 0;
            width: 100%;
            box-sizing: border-box;
        }

        .offer-slider-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
            gap: 30px;
            width: 100%;
        }

        .offer-slider-slide {
            flex: 0 0 calc((100% - <?php echo $desktop_gap_total; ?>px) / <?php echo $desktop_items; ?>);
            min-width: 0;
            max-width: calc((100% - <?php echo $desktop_gap_total; ?>px) / <?php echo $desktop_items; ?>);
            box-sizing: border-box;
        }

        .offer-slider-card {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
        }

        .offer-slider-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .offer-slider-header-img {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .offer-slider-header-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .offer-slider-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .offer-slider-badge::before {
            content: '🎁';
            font-size: 16px;
        }

        .offer-slider-body {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .offer-slider-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .offer-slider-icon-wrapper img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .offer-slider-card-title {
            font-size: 24px;
            font-weight: 700;
            color: #1d2327;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .offer-slider-description {
            font-size: 15px;
            color: #646970;
            margin-bottom: 20px;
            line-height: 1.6;
            flex: 1;
        }

        .offer-slider-prize-section {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .offer-slider-prize-label {
            font-size: 13px;
            color: #646970;
            margin-bottom: 5px;
        }

        .offer-slider-prize-amount {
            font-size: 28px;
            font-weight: 700;
            color: #16a34a;
        }

        .offer-slider-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .offer-slider-button:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
            color: #fff;
        }

        .offer-slider-button::after {
            content: '→';
            margin-left: 8px;
            transition: margin-left 0.3s ease;
        }

        .offer-slider-button:hover::after {
            margin-left: 12px;
        }

        /* Navigation Arrows */
        .offer-slider-nav {
            width: 50px;
            height: 50px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            border: none;
            flex-shrink: 0;
        }

        .offer-slider-nav:hover {
            background: #f0f0f1;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .offer-slider-nav svg {
            width: 24px;
            height: 24px;
            fill: #1d2327;
        }

        .offer-slider-nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .offer-slider-slide {
                flex: 0 0 calc((100% - <?php echo $mobile_gap_total; ?>px) / <?php echo $mobile_items; ?>);
                max-width: calc((100% - <?php echo $mobile_gap_total; ?>px) / <?php echo $mobile_items; ?>);
            }

            .offer-slider-nav {
                width: 40px;
                height: 40px;
            }

            .offer-slider-nav svg {
                width: 20px;
                height: 20px;
            }

            .offer-slider-nav-text {
                font-size: 16px;
            }
        }
    </style>

    <div class="offer-slider-wrapper">
        <div class="offer-slider-navigation-top">
            <button class="offer-slider-nav prev" data-slider="<?php echo esc_attr( $slider_id ); ?>" data-direction="prev">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>

            <span class="offer-slider-nav-text">স্লাইড করুন</span>

            <button class="offer-slider-nav next" data-slider="<?php echo esc_attr( $slider_id ); ?>" data-direction="next">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                </svg>
            </button>
        </div>

        <div class="offer-slider-container" id="<?php echo esc_attr( $slider_id ); ?>">
            <div class="offer-slider-track" data-track="<?php echo esc_attr( $slider_id ); ?>">
                <?php
                while ( $offers->have_posts() ) :
                    $offers->the_post();

                    $offer_title = get_post_meta( get_the_ID(), '_offer_title', true );
                    $offer_badge = get_post_meta( get_the_ID(), '_offer_badge', true );
                    $offer_icon = get_post_meta( get_the_ID(), '_offer_icon', true );
                    $offer_link = get_post_meta( get_the_ID(), '_offer_link', true );
                    $offer_prize = get_post_meta( get_the_ID(), '_offer_prize', true );
                    $offer_description = get_post_meta( get_the_ID(), '_offer_description', true );
                    $offer_button_text = get_post_meta( get_the_ID(), '_offer_button_text', true );
                    $offer_button_color = get_post_meta( get_the_ID(), '_offer_button_color', true );
                    $offer_icon_bg_color = get_post_meta( get_the_ID(), '_offer_icon_bg_color', true );

                    $featured_image = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
                    if ( ! $featured_image ) {
                        $featured_image = get_template_directory_uri() . '/assets/img/inner-banner.png';
                    }

                    $offer_button_text = $offer_button_text ? $offer_button_text : 'এখনই যুক্ত হন';
                    $offer_button_color = $offer_button_color ? $offer_button_color : '#f39c12';
                    $offer_icon_bg_color = $offer_icon_bg_color ? $offer_icon_bg_color : '#f39c12';
                    $display_badge = $offer_badge ? $offer_badge : $offer_title;
                    ?>

                    <div class="offer-slider-slide">
                        <div class="offer-slider-card">
                            <div class="offer-slider-header-img">
                                <img src="<?php echo esc_url( $featured_image ); ?>" alt="<?php echo esc_attr( $offer_title ); ?>" />
                                <div class="offer-slider-badge"><?php echo esc_html( $display_badge ); ?></div>
                            </div>

                            <div class="offer-slider-body">
                                <?php if ( $offer_icon ) : ?>
                                    <div class="offer-slider-icon-wrapper" style="background-color: <?php echo esc_attr( $offer_icon_bg_color ); ?>;">
                                        <img src="<?php echo esc_url( $offer_icon ); ?>" alt="<?php echo esc_attr( $offer_title ); ?>" />
                                    </div>
                                <?php endif; ?>

                                <h3 class="offer-slider-card-title"><?php echo esc_html( $offer_title ); ?></h3>

                                <?php if ( $offer_description ) : ?>
                                    <p class="offer-slider-description"><?php echo esc_html( $offer_description ); ?></p>
                                <?php endif; ?>

                                <?php if ( $offer_prize ) : ?>
                                    <div class="offer-slider-prize-section">
                                        <div class="offer-slider-prize-label">পুরস্কার</div>
                                        <div class="offer-slider-prize-amount"><?php echo esc_html( $offer_prize ); ?></div>
                                    </div>
                                <?php endif; ?>

                                <?php if ( $offer_link ) : ?>
                                    <a href="<?php echo esc_url( $offer_link ); ?>"
                                       class="offer-slider-button"
                                       style="background: linear-gradient(135deg, <?php echo esc_attr( $offer_button_color ); ?> 0%, <?php echo esc_attr( $offer_button_color ); ?>dd 100%);"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        <?php echo esc_html( $offer_button_text ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const sliderId = '<?php echo esc_js( $slider_id ); ?>';
        const container = document.getElementById(sliderId);
        const track = container.querySelector('[data-track="' + sliderId + '"]');
        const slides = track.querySelectorAll('.offer-slider-slide');
        const prevBtn = document.querySelector('.offer-slider-nav.prev[data-slider="' + sliderId + '"]');
        const nextBtn = document.querySelector('.offer-slider-nav.next[data-slider="' + sliderId + '"]');

        let currentIndex = 0;
        const desktopItems = <?php echo $desktop_items; ?>;
        const mobileItems = <?php echo $mobile_items; ?>;
        const autoplay = <?php echo $autoplay; ?>;
        const autoplaySpeed = <?php echo $autoplay_speed; ?>;
        let autoplayInterval;

        function getItemsPerView() {
            return window.innerWidth <= 768 ? mobileItems : desktopItems;
        }

        function getTotalSlides() {
            return slides.length;
        }

        function getMaxIndex() {
            const itemsPerView = getItemsPerView();
            return Math.max(0, getTotalSlides() - itemsPerView);
        }

        function updateSlider() {
            const itemsPerView = getItemsPerView();
            const gap = 30;
            const containerWidth = container.offsetWidth;

            // Calculate slide width: (100% - total gaps) / items
            const totalGapWidth = gap * (itemsPerView - 1);
            const slideWidthPercent = (containerWidth - totalGapWidth) / itemsPerView / containerWidth * 100;
            const gapPercent = gap / containerWidth * 100;

            // Calculate translation: move by (slideWidth + gap) for each index
            const translateX = -(currentIndex * (slideWidthPercent + gapPercent));

            track.style.transform = 'translateX(' + translateX + '%)';

            // Update button states
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex >= getMaxIndex();
        }

        function nextSlide() {
            if (currentIndex < getMaxIndex()) {
                currentIndex++;
                updateSlider();
            } else if (autoplay) {
                currentIndex = 0;
                updateSlider();
            }
        }

        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        }

        function startAutoplay() {
            if (autoplay && getTotalSlides() > getItemsPerView()) {
                autoplayInterval = setInterval(nextSlide, autoplaySpeed);
            }
        }

        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
            }
        }

        // Event listeners
        prevBtn.addEventListener('click', function() {
            stopAutoplay();
            prevSlide();
            startAutoplay();
        });

        nextBtn.addEventListener('click', function() {
            stopAutoplay();
            nextSlide();
            startAutoplay();
        });

        // Pause on hover
        container.addEventListener('mouseenter', stopAutoplay);
        container.addEventListener('mouseleave', startAutoplay);

        // Handle resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                currentIndex = Math.min(currentIndex, getMaxIndex());
                updateSlider();
            }, 250);
        });

        // Initialize
        updateSlider();
        startAutoplay();
    })();
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode( 'offer_list_slider', 'wicket_offer_list_slider_shortcode' );

/**
 * Add Sample Offers Generator Submenu
 */
function wicket_offer_generator_submenu() {
    add_submenu_page(
        'edit.php?post_type=offer-list',
        __( 'Generate Sample Offers', 'wicket' ),
        __( 'Generate Samples', 'wicket' ),
        'manage_options',
        'generate-sample-offers',
        'wicket_offer_generator_page'
    );
}
add_action( 'admin_menu', 'wicket_offer_generator_submenu' );

/**
 * Offer Generator Admin Page
 */
function wicket_offer_generator_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'Generate Sample Offers', 'wicket' ); ?></h1>
        <p><?php _e( 'Click the button below to generate 20 sample offers with random Bengali data.', 'wicket' ); ?></p>

        <style>
            .offer-generator-container {
                background: #fff;
                border: 1px solid #ccd0d4;
                padding: 30px;
                margin: 20px 0;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                max-width: 800px;
            }
            .generate-offers-button {
                background: #2271b1;
                color: #fff;
                border: none;
                padding: 15px 30px;
                font-size: 16px;
                font-weight: 600;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .generate-offers-button:hover {
                background: #135e96;
            }
            .generate-offers-button:disabled {
                background: #dcdcde;
                cursor: not-allowed;
            }
            .generator-loading {
                display: none;
                margin-top: 20px;
                padding: 15px;
                background: #f0f6fc;
                border-left: 4px solid #2271b1;
            }
            .generator-loading.active {
                display: block;
            }
            .loading-spinner {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid rgba(34, 113, 177, 0.3);
                border-radius: 50%;
                border-top-color: #2271b1;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            .generator-result {
                margin-top: 20px;
                padding: 15px;
                border-radius: 4px;
            }
            .generator-result.success {
                background: #d7f9e9;
                border-left: 4px solid #00a32a;
                color: #1e4620;
            }
            .generator-result.error {
                background: #fcf0f1;
                border-left: 4px solid #d63638;
                color: #3c1518;
            }
            .generator-info {
                background: #fff9e5;
                border-left: 4px solid #dba617;
                padding: 15px;
                margin-bottom: 20px;
            }
            .generator-info h3 {
                margin-top: 0;
            }
        </style>

        <div class="offer-generator-container">
            <div class="generator-info">
                <h3>ℹ️ <?php _e( 'What will be generated:', 'wicket' ); ?></h3>
                <ul>
                    <li><?php _e( '20 new offer posts with publish status', 'wicket' ); ?></li>
                    <li><?php _e( 'Random Bengali titles (Daily Bonus, Super Jackpot, Cricket Championship, etc.)', 'wicket' ); ?></li>
                    <li><?php _e( 'Random badge text (আজকের অফার, নতুন, বিশেষ অফার, etc.)', 'wicket' ); ?></li>
                    <li><?php _e( 'Random prize amounts (১০,০০০ টাকা to ১,০০,০০০ টাকা)', 'wicket' ); ?></li>
                    <li><?php _e( 'Random color schemes for icons and buttons', 'wicket' ); ?></li>
                    <li><?php _e( 'Bengali descriptions and button text', 'wicket' ); ?></li>
                    <li><?php _e( 'Sample offer links and placeholder images', 'wicket' ); ?></li>
                </ul>
            </div>

            <button type="button" id="generate-offers-btn" class="generate-offers-button">
                <?php _e( '🎁 Generate 20 Sample Offers', 'wicket' ); ?>
            </button>

            <div id="generator-loading" class="generator-loading">
                <span class="loading-spinner"></span>
                <span style="margin-left: 10px;"><?php _e( 'Generating offers... Please wait.', 'wicket' ); ?></span>
            </div>

            <div id="generator-result"></div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#generate-offers-btn').on('click', function() {
            var $button = $(this);
            var $loading = $('#generator-loading');
            var $result = $('#generator-result');

            // Disable button and show loading
            $button.prop('disabled', true);
            $loading.addClass('active');
            $result.html('').removeClass('success error');

            // Send AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'generate_sample_offers',
                    nonce: '<?php echo wp_create_nonce( 'generate_sample_offers_nonce' ); ?>'
                },
                success: function(response) {
                    $loading.removeClass('active');
                    $button.prop('disabled', false);

                    if (response.success) {
                        $result.html(
                            '<strong>✅ Success!</strong><br>' +
                            response.data.message +
                            '<br><a href="edit.php?post_type=offer-list" style="margin-top: 10px; display: inline-block;">View All Offers →</a>'
                        ).addClass('success generator-result');
                    } else {
                        $result.html(
                            '<strong>❌ Error!</strong><br>' +
                            response.data.message
                        ).addClass('error generator-result');
                    }
                },
                error: function() {
                    $loading.removeClass('active');
                    $button.prop('disabled', false);
                    $result.html(
                        '<strong>❌ Error!</strong><br>' +
                        'An unexpected error occurred. Please try again.'
                    ).addClass('error generator-result');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * AJAX Handler: Generate Sample Offers
 */
function wicket_ajax_generate_sample_offers() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'generate_sample_offers_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wicket' ) ) );
    }

    // Check permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'wicket' ) ) );
    }

    // Sample data arrays
    $offer_titles = array(
        'দৈনিক বোনাস',
        'সুপার জ্যাকপট',
        'ক্রিকেট চ্যাম্পিয়নশিপ',
        'সাপ্তাহিক পুরস্কার',
        'মেগা বোনাস',
        'স্পেশাল অফার',
        'গোল্ডেন সুযোগ',
        'প্রিমিয়াম রিওয়ার্ড',
        'লাকি ড্র',
        'ক্যাশব্যাক অফার',
        'উইকেন্ড বোনাস',
        'নতুন সদস্য অফার',
        'রেফারেল বোনাস',
        'হট ডিল',
        'ফ্ল্যাশ অফার',
        'স্পেশাল ইভেন্ট',
        'ভিআইপি বোনাস',
        'টুর্নামেন্ট পুরস্কার',
        'ফেস্টিভাল অফার',
        'এক্সক্লুসিভ ডিল',
    );

    $descriptions = array(
        'প্রতিদিনের বিশেষ বোনাস অফার পান',
        'সপ্তাহের মধ্য পুরস্কার জিতে নিন',
        'আজকের বিশেষ ক্রিকেট ম্যাচে অংশ নিন এবং জিতুন বড় পুরস্কার',
        'বিশেষ সুযোগে বড় পুরস্কার জিতুন',
        'অংশগ্রহণ করুন এবং পুরস্কার নিয়ে যান',
        'সীমিত সময়ের জন্য বিশেষ অফার',
        'আপনার ভাগ্য পরীক্ষা করুন এবং জিতুন',
        'এক্সক্লুসিভ সদস্যদের জন্য বিশেষ পুরস্কার',
        'প্রতিদিন নতুন সুযোগ জিততে',
        'ক্যাশব্যাক পান প্রতিটি বাজিতে',
    );

    $prizes = array(
        '১০,০০০ টাকা',
        '২০,০০০ টাকা',
        '৩০,০০০ টাকা',
        '৫০,০০০ টাকা',
        '৭৫,০০০ টাকা',
        '১,০০,০০০ টাকা',
        '১,৫০,০০০ টাকা',
        '২,০০,০০০ টাকা',
        '৫,০০,০০০ টাকা',
        '১০,০০,০০০ টাকা',
    );

    $button_texts = array(
        'এখনই যুক্ত হন',
        'অফার নিন',
        'এখনই খেলুন',
        'বিস্তারিত দেখুন',
        'শুরু করুন',
    );

    $badge_texts = array(
        'আজকের অফার',
        'নতুন',
        'বিশেষ অফার',
        'সীমিত সময়',
        'হট ডিল',
        'সেরা অফার',
        'এক্সক্লুসিভ',
        'ট্রেন্ডিং',
        'জনপ্রিয়',
        'প্রিমিয়াম',
    );

    $colors = array(
        '#f39c12', // Orange
        '#e74c3c', // Red
        '#3498db', // Blue
        '#2ecc71', // Green
        '#9b59b6', // Purple
        '#e67e22', // Dark Orange
        '#1abc9c', // Turquoise
        '#f1c40f', // Yellow
        '#8b5cf6', // Violet
        '#f97316', // Orange-Red
    );

    $sample_links = array(
        'https://example.com/daily-bonus',
        'https://example.com/jackpot',
        'https://example.com/cricket-championship',
        'https://example.com/weekly-prize',
        'https://example.com/mega-bonus',
    );

    // Generate 20 offers
    $generated_count = 0;
    $errors = array();

    for ( $i = 0; $i < 20; $i++ ) {
        // Create post
        $post_data = array(
            'post_type'    => 'offer-list',
            'post_title'   => $offer_titles[ $i ] . ' #' . ( $i + 1 ),
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
        );

        $post_id = wp_insert_post( $post_data );

        if ( is_wp_error( $post_id ) ) {
            $errors[] = sprintf( __( 'Failed to create offer %d: %s', 'wicket' ), $i + 1, $post_id->get_error_message() );
            continue;
        }

        // Add meta data with random values
        update_post_meta( $post_id, '_offer_title', $offer_titles[ $i ] );
        update_post_meta( $post_id, '_offer_badge', $badge_texts[ array_rand( $badge_texts ) ] );
        update_post_meta( $post_id, '_offer_icon', get_template_directory_uri() . '/assets/img/a.png' );
        update_post_meta( $post_id, '_offer_icon_bg_color', $colors[ array_rand( $colors ) ] );
        update_post_meta( $post_id, '_offer_description', $descriptions[ array_rand( $descriptions ) ] );
        update_post_meta( $post_id, '_offer_prize', $prizes[ array_rand( $prizes ) ] );
        update_post_meta( $post_id, '_offer_link', $sample_links[ array_rand( $sample_links ) ] );
        update_post_meta( $post_id, '_offer_button_text', $button_texts[ array_rand( $button_texts ) ] );
        update_post_meta( $post_id, '_offer_button_color', $colors[ array_rand( $colors ) ] );

        $generated_count++;
    }

    // Send response
    if ( $generated_count === 20 ) {
        wp_send_json_success( array(
            'message' => sprintf(
                __( 'Successfully generated %d sample offers! You can now view and edit them.', 'wicket' ),
                $generated_count
            )
        ) );
    } else {
        wp_send_json_error( array(
            'message' => sprintf(
                __( 'Generated %d offers, but encountered some errors. Please check and try again.', 'wicket' ),
                $generated_count
            )
        ) );
    }
}
add_action( 'wp_ajax_generate_sample_offers', 'wicket_ajax_generate_sample_offers' );
