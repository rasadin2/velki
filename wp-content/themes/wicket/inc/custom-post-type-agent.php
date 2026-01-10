<?php
/**
 * Custom Post Type: Velki Agent List
 *
 * Manages agent profiles with multiple groups, ratings, contact info, and social links
 *
 * @package Wicket
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Custom Post Type: velki-agent
 */
function wicket_register_agent_post_type() {
    $labels = array(
        'name'               => _x( 'Velki Agents', 'post type general name', 'wicket' ),
        'singular_name'      => _x( 'Agent', 'post type singular name', 'wicket' ),
        'menu_name'          => _x( 'Velki Agents', 'admin menu', 'wicket' ),
        'name_admin_bar'     => _x( 'Agent', 'add new on admin bar', 'wicket' ),
        'add_new'            => _x( 'Add New', 'agent', 'wicket' ),
        'add_new_item'       => __( 'Add New Agent', 'wicket' ),
        'new_item'           => __( 'New Agent', 'wicket' ),
        'edit_item'          => __( 'Edit Agent', 'wicket' ),
        'view_item'          => __( 'View Agent', 'wicket' ),
        'all_items'          => __( 'All Agents', 'wicket' ),
        'search_items'       => __( 'Search Agents', 'wicket' ),
        'parent_item_colon'  => __( 'Parent Agents:', 'wicket' ),
        'not_found'          => __( 'No agents found.', 'wicket' ),
        'not_found_in_trash' => __( 'No agents found in Trash.', 'wicket' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'agent' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-businessperson',
        'supports'           => array( 'title', 'thumbnail' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'velki-agent', $args );
}
add_action( 'init', 'wicket_register_agent_post_type' );

/**
 * Register Taxonomy: Agent Groups
 */
function wicket_register_agent_group_taxonomy() {
    $labels = array(
        'name'              => _x( 'Agent Groups', 'taxonomy general name', 'wicket' ),
        'singular_name'     => _x( 'Agent Group', 'taxonomy singular name', 'wicket' ),
        'search_items'      => __( 'Search Agent Groups', 'wicket' ),
        'all_items'         => __( 'All Agent Groups', 'wicket' ),
        'edit_item'         => __( 'Edit Agent Group', 'wicket' ),
        'update_item'       => __( 'Update Agent Group', 'wicket' ),
        'add_new_item'      => __( 'Add New Agent Group', 'wicket' ),
        'new_item_name'     => __( 'New Agent Group Name', 'wicket' ),
        'menu_name'         => __( 'Agent Groups', 'wicket' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'agent-group' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'agent-group', array( 'velki-agent' ), $args );
}
add_action( 'init', 'wicket_register_agent_group_taxonomy' );

/**
 * Create Default Agent Groups
 */
function wicket_create_default_agent_groups() {
    $default_groups = array(
        'মাস্টার এজেন্ট',
        'সুপার এজেন্ট',
        'সাব এ্যাডমিন',
        'এ্যাডমিন',
    );

    foreach ( $default_groups as $group ) {
        if ( ! term_exists( $group, 'agent-group' ) ) {
            wp_insert_term( $group, 'agent-group' );
        }
    }
}
add_action( 'init', 'wicket_create_default_agent_groups', 20 );

/**
 * Add Meta Boxes for Agent Details
 */
function wicket_add_agent_meta_boxes() {
    add_meta_box(
        'agent_details',
        __( 'Agent Details', 'wicket' ),
        'wicket_agent_details_callback',
        'velki-agent',
        'normal',
        'high'
    );

    add_meta_box(
        'agent_contact',
        __( 'Contact Information', 'wicket' ),
        'wicket_agent_contact_callback',
        'velki-agent',
        'normal',
        'high'
    );

    add_meta_box(
        'agent_status',
        __( 'Agent Status', 'wicket' ),
        'wicket_agent_status_callback',
        'velki-agent',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'wicket_add_agent_meta_boxes' );

/**
 * Meta Box Callback: Agent Details
 */
function wicket_agent_details_callback( $post ) {
    wp_nonce_field( 'wicket_agent_details_nonce', 'agent_details_nonce' );

    $agent_id = get_post_meta( $post->ID, '_agent_id', true );
    $rating = get_post_meta( $post->ID, '_agent_rating', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="agent_id"><?php _e( 'Agent ID', 'wicket' ); ?></label></th>
            <td>
                <input type="text" id="agent_id" name="agent_id" value="<?php echo esc_attr( $agent_id ); ?>" class="regular-text" />
                <p class="description"><?php _e( 'Unique agent identification number', 'wicket' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="agent_rating"><?php _e( 'Rating', 'wicket' ); ?></label></th>
            <td>
                <select id="agent_rating" name="agent_rating">
                    <option value=""><?php _e( 'Select Rating', 'wicket' ); ?></option>
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <option value="<?php echo $i; ?>" <?php selected( $rating, $i ); ?>>
                            <?php echo str_repeat( '⭐', $i ); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <p class="description"><?php _e( 'Agent rating (1-5 stars)', 'wicket' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Meta Box Callback: Contact Information
 */
function wicket_agent_contact_callback( $post ) {
    wp_nonce_field( 'wicket_agent_contact_nonce', 'agent_contact_nonce' );

    $whatsapp_url_1 = get_post_meta( $post->ID, '_agent_whatsapp_url_1', true );
    $whatsapp_url_2 = get_post_meta( $post->ID, '_agent_whatsapp_url_2', true );
    $messenger_url = get_post_meta( $post->ID, '_agent_messenger_url', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="agent_whatsapp_url_1"><?php _e( 'WhatsApp Message URL 1', 'wicket' ); ?></label></th>
            <td>
                <input type="url" id="agent_whatsapp_url_1" name="agent_whatsapp_url_1" value="<?php echo esc_url( $whatsapp_url_1 ); ?>" class="regular-text" placeholder="https://wa.me/18049722549" />
                <p class="description"><?php _e( 'Primary WhatsApp message URL (e.g., https://wa.me/18049722549)', 'wicket' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="agent_whatsapp_url_2"><?php _e( 'WhatsApp Message URL 2', 'wicket' ); ?></label></th>
            <td>
                <input type="url" id="agent_whatsapp_url_2" name="agent_whatsapp_url_2" value="<?php echo esc_url( $whatsapp_url_2 ); ?>" class="regular-text" placeholder="https://wa.me/18049722550" />
                <p class="description"><?php _e( 'Secondary WhatsApp message URL (optional)', 'wicket' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="agent_messenger_url"><?php _e( 'Messenger Message URL', 'wicket' ); ?></label></th>
            <td>
                <input type="url" id="agent_messenger_url" name="agent_messenger_url" value="<?php echo esc_url( $messenger_url ); ?>" class="regular-text" placeholder="https://m.me/velkiagents.pro" />
                <p class="description"><?php _e( 'Facebook Messenger message URL (e.g., https://m.me/velkiagents.pro)', 'wicket' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Meta Box Callback: Agent Status
 */
function wicket_agent_status_callback( $post ) {
    wp_nonce_field( 'wicket_agent_status_nonce', 'agent_status_nonce' );

    $is_verified = get_post_meta( $post->ID, '_agent_verified', true );
    $is_premium = get_post_meta( $post->ID, '_agent_premium', true );
    ?>
    <p>
        <label for="agent_verified">
            <input type="checkbox" id="agent_verified" name="agent_verified" value="1" <?php checked( $is_verified, '1' ); ?> />
            <?php _e( 'Verified Agent (Badge)', 'wicket' ); ?>
        </label>
    </p>
    <p>
        <label for="agent_premium">
            <input type="checkbox" id="agent_premium" name="agent_premium" value="1" <?php checked( $is_premium, '1' ); ?> />
            <?php _e( 'Premium Agent (Crown)', 'wicket' ); ?>
        </label>
    </p>
    <?php
}

/**
 * Save Agent Meta Data
 */
function wicket_save_agent_meta( $post_id ) {
    // Check nonces
    $details_nonce = isset( $_POST['agent_details_nonce'] ) ? $_POST['agent_details_nonce'] : '';
    $contact_nonce = isset( $_POST['agent_contact_nonce'] ) ? $_POST['agent_contact_nonce'] : '';
    $status_nonce = isset( $_POST['agent_status_nonce'] ) ? $_POST['agent_status_nonce'] : '';

    if ( ! wp_verify_nonce( $details_nonce, 'wicket_agent_details_nonce' ) &&
         ! wp_verify_nonce( $contact_nonce, 'wicket_agent_contact_nonce' ) &&
         ! wp_verify_nonce( $status_nonce, 'wicket_agent_status_nonce' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save agent details
    if ( isset( $_POST['agent_id'] ) ) {
        update_post_meta( $post_id, '_agent_id', sanitize_text_field( $_POST['agent_id'] ) );
    }

    if ( isset( $_POST['agent_rating'] ) ) {
        update_post_meta( $post_id, '_agent_rating', intval( $_POST['agent_rating'] ) );
    }

    // Save contact information (URLs)
    if ( isset( $_POST['agent_whatsapp_url_1'] ) ) {
        update_post_meta( $post_id, '_agent_whatsapp_url_1', esc_url_raw( $_POST['agent_whatsapp_url_1'] ) );
    }

    if ( isset( $_POST['agent_whatsapp_url_2'] ) ) {
        update_post_meta( $post_id, '_agent_whatsapp_url_2', esc_url_raw( $_POST['agent_whatsapp_url_2'] ) );
    }

    if ( isset( $_POST['agent_messenger_url'] ) ) {
        update_post_meta( $post_id, '_agent_messenger_url', esc_url_raw( $_POST['agent_messenger_url'] ) );
    }

    // Save status
    $is_verified = isset( $_POST['agent_verified'] ) ? '1' : '';
    update_post_meta( $post_id, '_agent_verified', $is_verified );

    $is_premium = isset( $_POST['agent_premium'] ) ? '1' : '';
    update_post_meta( $post_id, '_agent_premium', $is_premium );
}
add_action( 'save_post_velki-agent', 'wicket_save_agent_meta' );

/**
 * Add Agent Generator Submenu
 */
function wicket_agent_generator_submenu() {
    add_submenu_page(
        'edit.php?post_type=velki-agent',
        __( 'Generate Agents', 'wicket' ),
        __( 'Generate Samples', 'wicket' ),
        'manage_options',
        'agent-generator',
        'wicket_agent_generator_page'
    );
}
add_action( 'admin_menu', 'wicket_agent_generator_submenu' );

/**
 * Agent Generator Page
 */
function wicket_agent_generator_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'Generate Sample Agents', 'wicket' ); ?></h1>

        <div class="card" style="max-width: 600px; margin-top: 20px;">
            <h2><?php _e( 'Quick Sample Data Generator', 'wicket' ); ?></h2>
            <p><?php _e( 'This will generate 20 sample agent profiles with:', 'wicket' ); ?></p>
            <ul>
                <li><?php _e( 'Random agent names', 'wicket' ); ?></li>
                <li><?php _e( 'Agent groups (মাস্টার এজেন্ট, সুপার এজেন্ট, etc.)', 'wicket' ); ?></li>
                <li><?php _e( 'Agent IDs (2000-9999)', 'wicket' ); ?></li>
                <li><?php _e( 'Ratings (1-5 stars)', 'wicket' ); ?></li>
                <li><?php _e( 'WhatsApp message URLs (2 numbers) and Messenger message URL', 'wicket' ); ?></li>
                <li><?php _e( 'Featured images from existing uploads', 'wicket' ); ?></li>
            </ul>

            <p style="margin-top: 20px;">
                <button type="button" id="generate-agents-btn" class="button button-primary button-hero">
                    🎯 <?php _e( 'Generate 20 Sample Agents', 'wicket' ); ?>
                </button>
            </p>

            <div id="generation-spinner" style="display: none; margin-top: 20px;">
                <span class="spinner is-active" style="float: none; margin: 0 10px 0 0;"></span>
                <strong><?php _e( 'Generating agents, please wait...', 'wicket' ); ?></strong>
            </div>

            <div id="generation-result" style="margin-top: 20px;"></div>
        </div>
    </div>

    <style>
        #generation-result .notice {
            padding: 15px;
            border-radius: 4px;
            margin: 0;
        }
        #generation-result .notice-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        #generation-result .notice-error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        #generation-spinner {
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        $('#generate-agents-btn').on('click', function() {
            var $btn = $(this);
            var $spinner = $('#generation-spinner');
            var $result = $('#generation-result');

            // Show loading state
            $btn.prop('disabled', true);
            $spinner.show();
            $result.html('');

            // AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'generate_sample_agents',
                    nonce: '<?php echo wp_create_nonce( 'generate_agents_nonce' ); ?>'
                },
                success: function(response) {
                    $spinner.hide();
                    $btn.prop('disabled', false);

                    if (response.success) {
                        $result.html('<div class="notice notice-success"><strong>✅ Success!</strong> ' + response.data.message + '</div>');
                        setTimeout(function() {
                            window.location.href = 'edit.php?post_type=velki-agent';
                        }, 2000);
                    } else {
                        $result.html('<div class="notice notice-error"><strong>❌ Error!</strong> ' + response.data.message + '</div>');
                    }
                },
                error: function() {
                    $spinner.hide();
                    $btn.prop('disabled', false);
                    $result.html('<div class="notice notice-error"><strong>❌ Error!</strong> Failed to generate agents. Please try again.</div>');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * AJAX Handler: Generate Sample Agents
 */
function wicket_ajax_generate_sample_agents() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'generate_agents_nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed.' ) );
    }

    // Check permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Insufficient permissions.' ) );
    }

    $agent_names = array(
        'Habib Khan', 'Arif Hossain', 'Ariyan Islam', 'Emon Khan', 'Mahmudul Hasan',
        'Md Harun', 'Nabil Ahmed', 'Nahid Islam', 'Pavel Hasan', 'Raj Khan',
        'Rajvi Ahmed', 'Robin Islam', 'Rohan Khan', 'Rony Zaman', 'Sadman Islam',
        'Saif Zaman', 'Salman Khan', 'Samir Khan', 'Shahriar Alom', 'Shipon Khan'
    );

    $agent_groups = array( 'মাস্টার এজেন্ট', 'সুপার এজেন্ট', 'সাব এ্যাডমিন', 'এ্যাডমিন' );

    // Get existing images from media library
    $images = get_posts( array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => 20,
        'orderby'        => 'rand',
    ) );

    $generated_count = 0;

    for ( $i = 0; $i < 20; $i++ ) {
        // Create agent post
        $post_id = wp_insert_post( array(
            'post_type'   => 'velki-agent',
            'post_title'  => $agent_names[ $i ],
            'post_status' => 'publish',
        ) );

        if ( is_wp_error( $post_id ) ) {
            continue;
        }

        // Assign random 1-2 groups
        $group_count = rand( 1, 2 );
        $selected_groups = array_rand( array_flip( $agent_groups ), $group_count );
        if ( ! is_array( $selected_groups ) ) {
            $selected_groups = array( $selected_groups );
        }
        wp_set_object_terms( $post_id, $selected_groups, 'agent-group' );

        // Add meta data
        $phone_1 = '+1804972' . rand( 1000, 9999 );
        $phone_2 = '+1804972' . rand( 1000, 9999 );

        update_post_meta( $post_id, '_agent_id', rand( 2000, 9999 ) );
        update_post_meta( $post_id, '_agent_rating', rand( 3, 5 ) );
        update_post_meta( $post_id, '_agent_whatsapp_url_1', 'https://wa.me/' . str_replace( '+', '', $phone_1 ) );
        update_post_meta( $post_id, '_agent_whatsapp_url_2', 'https://wa.me/' . str_replace( '+', '', $phone_2 ) );
        update_post_meta( $post_id, '_agent_messenger_url', 'https://m.me/velkiagents.pro' );

        // 50% verified, 30% premium
        if ( rand( 1, 100 ) <= 50 ) {
            update_post_meta( $post_id, '_agent_verified', '1' );
        }
        if ( rand( 1, 100 ) <= 30 ) {
            update_post_meta( $post_id, '_agent_premium', '1' );
        }

        // Set featured image if available
        if ( ! empty( $images[ $i ] ) ) {
            set_post_thumbnail( $post_id, $images[ $i ]->ID );
        }

        $generated_count++;
    }

    wp_send_json_success( array(
        'message' => sprintf( 'Successfully generated %d agents!', $generated_count )
    ) );
}
add_action( 'wp_ajax_generate_sample_agents', 'wicket_ajax_generate_sample_agents' );

/**
 * Customize Admin Columns
 */
function wicket_agent_custom_columns( $columns ) {
    $new_columns = array(
        'cb'            => $columns['cb'],
        'thumbnail'     => __( 'Photo', 'wicket' ),
        'title'         => __( 'Agent Name', 'wicket' ),
        'agent_id'      => __( 'Agent ID', 'wicket' ),
        'agent_groups'  => __( 'Groups', 'wicket' ),
        'rating'        => __( 'Rating', 'wicket' ),
        'status'        => __( 'Status', 'wicket' ),
        'date'          => __( 'Date', 'wicket' ),
    );
    return $new_columns;
}
add_filter( 'manage_velki-agent_posts_columns', 'wicket_agent_custom_columns' );

/**
 * Populate Custom Columns
 */
function wicket_agent_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ), array( 'style' => 'border-radius: 50%;' ) );
            } else {
                echo '<span class="dashicons dashicons-businessman" style="font-size: 50px; color: #ccc;"></span>';
            }
            break;

        case 'agent_id':
            $agent_id = get_post_meta( $post_id, '_agent_id', true );
            echo $agent_id ? '<strong>' . esc_html( $agent_id ) . '</strong>' : '—';
            break;

        case 'agent_groups':
            $terms = get_the_terms( $post_id, 'agent-group' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $group_names = wp_list_pluck( $terms, 'name' );
                echo implode( ', ', $group_names );
            } else {
                echo '—';
            }
            break;

        case 'rating':
            $rating = get_post_meta( $post_id, '_agent_rating', true );
            if ( $rating ) {
                echo str_repeat( '⭐', intval( $rating ) );
            } else {
                echo '—';
            }
            break;

        case 'status':
            $is_verified = get_post_meta( $post_id, '_agent_verified', true );
            $is_premium = get_post_meta( $post_id, '_agent_premium', true );
            $badges = array();
            if ( $is_verified ) {
                $badges[] = '<span style="color: #10b981;">✓ Verified</span>';
            }
            if ( $is_premium ) {
                $badges[] = '<span style="color: #fbbf24;">👑 Premium</span>';
            }
            echo $badges ? implode( ' ', $badges ) : '—';
            break;
    }
}
add_action( 'manage_velki-agent_posts_custom_column', 'wicket_agent_custom_column_content', 10, 2 );
