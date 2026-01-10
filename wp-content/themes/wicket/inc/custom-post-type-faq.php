<?php
/**
 * Custom Post Type: Velki FAQ
 *
 * @package wicket
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Custom Post Type: Velki FAQ
 */
function wicket_register_faq_post_type() {
    $labels = array(
        'name'                  => _x( 'FAQs', 'Post Type General Name', 'wicket' ),
        'singular_name'         => _x( 'FAQ', 'Post Type Singular Name', 'wicket' ),
        'menu_name'             => __( 'Velki FAQs', 'wicket' ),
        'name_admin_bar'        => __( 'FAQ', 'wicket' ),
        'archives'              => __( 'FAQ Archives', 'wicket' ),
        'attributes'            => __( 'FAQ Attributes', 'wicket' ),
        'parent_item_colon'     => __( 'Parent FAQ:', 'wicket' ),
        'all_items'             => __( 'All FAQs', 'wicket' ),
        'add_new_item'          => __( 'Add New FAQ', 'wicket' ),
        'add_new'               => __( 'Add New', 'wicket' ),
        'new_item'              => __( 'New FAQ', 'wicket' ),
        'edit_item'             => __( 'Edit FAQ', 'wicket' ),
        'update_item'           => __( 'Update FAQ', 'wicket' ),
        'view_item'             => __( 'View FAQ', 'wicket' ),
        'view_items'            => __( 'View FAQs', 'wicket' ),
        'search_items'          => __( 'Search FAQ', 'wicket' ),
        'not_found'             => __( 'Not found', 'wicket' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'wicket' ),
    );

    $args = array(
        'label'                 => __( 'FAQ', 'wicket' ),
        'description'           => __( 'Frequently Asked Questions', 'wicket' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-editor-help',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type( 'velki-faq', $args );
}
add_action( 'init', 'wicket_register_faq_post_type', 0 );

/**
 * Register Meta Boxes for FAQ
 */
function wicket_faq_meta_boxes() {
    add_meta_box(
        'faq_settings',
        __( 'FAQ Settings', 'wicket' ),
        'wicket_faq_settings_callback',
        'velki-faq',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wicket_faq_meta_boxes' );

/**
 * Meta Box Callback: FAQ Settings
 */
function wicket_faq_settings_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'wicket_faq_settings_nonce', 'wicket_faq_settings_nonce_field' );

    // Retrieve existing value
    $is_featured = get_post_meta( $post->ID, '_faq_featured', true );
    ?>
    <style>
        .faq-meta-field {
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-left: 4px solid #2271b1;
        }
        .faq-meta-field label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        .faq-meta-field input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .faq-field-description {
            font-size: 13px;
            color: #646970;
            font-style: italic;
            margin-top: 8px;
            margin-left: 30px;
        }
    </style>

    <div class="faq-meta-fields-wrapper">
        <div class="faq-meta-field">
            <label for="faq_featured">
                <input
                    type="checkbox"
                    id="faq_featured"
                    name="faq_featured"
                    value="1"
                    <?php checked( $is_featured, '1' ); ?>
                />
                <?php _e( 'Featured FAQ', 'wicket' ); ?>
            </label>
            <p class="faq-field-description">
                <?php _e( 'Check this to display this FAQ in the featured section', 'wicket' ); ?>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function wicket_save_faq_settings( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['wicket_faq_settings_nonce_field'] ) ||
         ! wp_verify_nonce( $_POST['wicket_faq_settings_nonce_field'], 'wicket_faq_settings_nonce' ) ) {
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

    // Save featured status
    if ( isset( $_POST['faq_featured'] ) && $_POST['faq_featured'] === '1' ) {
        update_post_meta( $post_id, '_faq_featured', '1' );
    } else {
        delete_post_meta( $post_id, '_faq_featured' );
    }
}
add_action( 'save_post', 'wicket_save_faq_settings' );

/**
 * Add custom columns to FAQ admin
 */
function wicket_faq_custom_columns( $columns ) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['featured'] = __( 'Featured', 'wicket' );
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter( 'manage_velki-faq_posts_columns', 'wicket_faq_custom_columns' );

/**
 * Display custom column content
 */
function wicket_faq_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'featured':
            $is_featured = get_post_meta( $post_id, '_faq_featured', true );
            if ( $is_featured === '1' ) {
                echo '<span style="color: #d63638; font-weight: 600;">★ ' . __( 'Featured', 'wicket' ) . '</span>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
    }
}
add_action( 'manage_velki-faq_posts_custom_column', 'wicket_faq_custom_column_content', 10, 2 );

/**
 * Shortcode: Display Featured FAQs
 * Usage: [velki_faq_featured]
 */
function wicket_faq_featured_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'limit' => -1,
        ),
        $atts,
        'velki_faq_featured'
    );

    $args = array(
        'post_type'      => 'velki-faq',
        'posts_per_page' => intval( $atts['limit'] ),
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'   => '_faq_featured',
                'value' => '1',
            ),
        ),
    );

    return wicket_render_faq_accordion( $args, 'featured' );
}
add_shortcode( 'velki_faq_featured', 'wicket_faq_featured_shortcode' );

/**
 * Shortcode: Display All FAQs
 * Usage: [velki_faq_all]
 */
function wicket_faq_all_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'limit' => -1,
        ),
        $atts,
        'velki_faq_all'
    );

    $args = array(
        'post_type'      => 'velki-faq',
        'posts_per_page' => intval( $atts['limit'] ),
        'post_status'    => 'publish',
    );

    return wicket_render_faq_accordion( $args, 'all' );
}
add_shortcode( 'velki_faq_all', 'wicket_faq_all_shortcode' );

/**
 * Render FAQ Accordion
 */
function wicket_render_faq_accordion( $args, $type = 'all' ) {
    $faqs = new WP_Query( $args );

    if ( ! $faqs->have_posts() ) {
        return '<p>' . __( 'No FAQs found.', 'wicket' ) . '</p>';
    }

    $unique_id = 'faq-' . uniqid();

    ob_start();
    ?>

    <style>
        .velki-faq-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .velki-faq-accordion {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .velki-faq-item {
            background: linear-gradient(135deg, #1a1f35 0%, #252b45 100%);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .velki-faq-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .velki-faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            cursor: pointer;
            user-select: none;
            gap: 20px;
        }

        .velki-faq-question-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .velki-faq-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #fbbf24;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .velki-faq-icon::before {
            content: '?';
            color: #fbbf24;
            font-weight: 700;
            font-size: 16px;
        }

        .velki-faq-question-text {
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.5;
        }

        .velki-faq-toggle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(251, 191, 36, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .velki-faq-toggle::before {
            content: '+';
            color: #fbbf24;
            font-size: 24px;
            font-weight: 300;
            transition: transform 0.3s ease;
        }

        .velki-faq-item.active .velki-faq-toggle::before {
            transform: rotate(45deg);
        }

        .velki-faq-item.active .velki-faq-toggle {
            background: rgba(251, 191, 36, 0.2);
        }

        .velki-faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .velki-faq-answer-content {
            padding: 0 24px 24px 63px;
            color: #ffffff;
            font-size: 15px;
            line-height: 1.7;
        }

        .velki-faq-answer-content p {
            margin: 0 0 15px 0;
        }

        .velki-faq-answer-content p:last-child {
            margin-bottom: 0;
        }

        .velki-faq-answer-content ul,
        .velki-faq-answer-content ol {
            margin: 0 0 15px 20px;
            padding: 0;
        }

        .velki-faq-answer-content li {
            margin-bottom: 8px;
        }

        .velki-faq-answer-content a {
            color: #fbbf24;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .velki-faq-answer-content a:hover {
            color: #fcd34d;
        }

        @media (max-width: 768px) {
            .velki-faq-wrapper {
                padding: 15px;
            }

            .velki-faq-question {
                padding: 16px 18px;
                gap: 12px;
            }

            .velki-faq-question-left {
                gap: 12px;
            }

            .velki-faq-icon {
                width: 20px;
                height: 20px;
            }

            .velki-faq-icon::before {
                font-size: 14px;
            }

            .velki-faq-question-text {
                font-size: 15px;
            }

            .velki-faq-toggle {
                width: 28px;
                height: 28px;
            }

            .velki-faq-toggle::before {
                font-size: 20px;
            }

            .velki-faq-answer-content {
                padding: 0 18px 18px 50px;
                font-size: 14px;
            }
        }
    </style>

    <div class="velki-faq-wrapper" id="<?php echo esc_attr( $unique_id ); ?>">
        <div class="velki-faq-accordion">
            <?php
            while ( $faqs->have_posts() ) :
                $faqs->the_post();
                $faq_id = 'faq-item-' . get_the_ID();
                ?>
                <div class="velki-faq-item" data-faq-id="<?php echo esc_attr( $faq_id ); ?>">
                    <div class="velki-faq-question">
                        <div class="velki-faq-question-left">
                            <div class="velki-faq-icon"></div>
                            <div class="velki-faq-question-text"><?php the_title(); ?></div>
                        </div>
                        <div class="velki-faq-toggle"></div>
                    </div>
                    <div class="velki-faq-answer">
                        <div class="velki-faq-answer-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>

    <script>
    (function() {
        const accordionId = '<?php echo esc_js( $unique_id ); ?>';
        const accordion = document.getElementById(accordionId);
        const items = accordion.querySelectorAll('.velki-faq-item');

        items.forEach(function(item) {
            const question = item.querySelector('.velki-faq-question');
            const answer = item.querySelector('.velki-faq-answer');

            question.addEventListener('click', function() {
                const isActive = item.classList.contains('active');

                // Close all other items (optional: remove to allow multiple open)
                items.forEach(function(otherItem) {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        const otherAnswer = otherItem.querySelector('.velki-faq-answer');
                        otherAnswer.style.maxHeight = null;
                    }
                });

                // Toggle current item
                if (isActive) {
                    item.classList.remove('active');
                    answer.style.maxHeight = null;
                } else {
                    item.classList.add('active');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });
    })();
    </script>

    <?php
    return ob_get_clean();
}

/**
 * Add FAQ Generator Submenu
 */
function wicket_faq_generator_submenu() {
    add_submenu_page(
        'edit.php?post_type=velki-faq',
        __( 'Generate Sample FAQs', 'wicket' ),
        __( 'Generate Samples', 'wicket' ),
        'manage_options',
        'generate-sample-faqs',
        'wicket_faq_generator_page'
    );
}
add_action( 'admin_menu', 'wicket_faq_generator_submenu' );

/**
 * FAQ Generator Admin Page
 */
function wicket_faq_generator_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'Generate Sample FAQs', 'wicket' ); ?></h1>
        <p><?php _e( 'Click the button below to generate 20 sample FAQs with Bengali content.', 'wicket' ); ?></p>

        <style>
            .faq-generator-container {
                background: #fff;
                border: 1px solid #ccd0d4;
                padding: 30px;
                margin: 20px 0;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                max-width: 800px;
            }
            .generate-faqs-button {
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
            .generate-faqs-button:hover {
                background: #135e96;
            }
            .generate-faqs-button:disabled {
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

        <div class="faq-generator-container">
            <div class="generator-info">
                <h3>ℹ️ <?php _e( 'What will be generated:', 'wicket' ); ?></h3>
                <ul>
                    <li><?php _e( '20 new FAQ posts with publish status', 'wicket' ); ?></li>
                    <li><?php _e( 'Bengali questions and answers', 'wicket' ); ?></li>
                    <li><?php _e( 'Mix of featured and non-featured FAQs (50/50)', 'wicket' ); ?></li>
                    <li><?php _e( 'Common gambling/betting related questions', 'wicket' ); ?></li>
                    <li><?php _e( 'Formatted HTML answers with proper structure', 'wicket' ); ?></li>
                </ul>
            </div>

            <button type="button" id="generate-faqs-btn" class="generate-faqs-button">
                <?php _e( '🎯 Generate 20 Sample FAQs', 'wicket' ); ?>
            </button>

            <div id="generator-loading" class="generator-loading">
                <span class="loading-spinner"></span>
                <span style="margin-left: 10px;"><?php _e( 'Generating FAQs... Please wait.', 'wicket' ); ?></span>
            </div>

            <div id="generator-result"></div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#generate-faqs-btn').on('click', function() {
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
                    action: 'generate_sample_faqs',
                    nonce: '<?php echo wp_create_nonce( 'generate_sample_faqs_nonce' ); ?>'
                },
                success: function(response) {
                    $loading.removeClass('active');
                    $button.prop('disabled', false);

                    if (response.success) {
                        $result.html(
                            '<strong>✅ Success!</strong><br>' +
                            response.data.message +
                            '<br><a href="edit.php?post_type=velki-faq" style="margin-top: 10px; display: inline-block;">View All FAQs →</a>'
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
 * AJAX Handler: Generate Sample FAQs
 */
function wicket_ajax_generate_sample_faqs() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'generate_sample_faqs_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wicket' ) ) );
    }

    // Check permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'wicket' ) ) );
    }

    // Sample FAQ data
    $faq_questions = array(
        'Velki কি নিরাপদ?',
        'কীভাবে একটি নতুন অ্যাকাউন্ট তৈরি করবো?',
        'Master Agent এবং Super Agent এর মধ্যে পার্থক্য কী?',
        'পেমেন্ট কতক্ষণ সময় নেয়?',
        'প্রত্যাহার কখন পাবো?',
        'একাউন্টে ডিপোজিট করতে কি আমার নাম দিতে হবে?',
        'বোনাস কীভাবে পাবো?',
        'কত টাকা দিয়ে শুরু করতে পারি?',
        'ইনভোয়েস কিভাবে তৈরি করবো?',
        'কোন পেমেন্ট মেথড ব্যবহার করতে পারি?',
        'রেজিস্ট্রেশন করতে কি খরচ হয়?',
        'আমি কি একাধিক অ্যাকাউন্ট খুলতে পারি?',
        'কাস্টমার সাপোর্ট কিভাবে যোগাযোগ করবো?',
        'বেটিং লিমিট কত?',
        'আমার অ্যাকাউন্ট ব্লক হলে কি করবো?',
        'প্রমোশনাল কোড কোথায় পাবো?',
        'পাসওয়ার্ড ভুলে গেলে কি করবো?',
        'লাইভ বেটিং কি উপলব্ধ?',
        'মোবাইলে কিভাবে ব্যবহার করবো?',
        'আমার জেতা টাকা কখন পাবো?',
    );

    $faq_answers = array(
        '<p>হ্যাঁ, Velki সম্পূর্ণ নিরাপদ এবং বিশ্বস্ত। আমরা সর্বোচ্চ নিরাপত্তা ব্যবস্থা ব্যবহার করি এবং আপনার সমস্ত তথ্য এনক্রিপ্টেড থাকে।</p>',
        '<p>নতুন অ্যাকাউন্ট তৈরি করতে:</p><ol><li>রেজিস্ট্রেশন পেজে যান</li><li>আপনার মোবাইল নম্বর দিন</li><li>OTP ভেরিফাই করুন</li><li>পাসওয়ার্ড সেট করুন</li></ol>',
        '<p>Master Agent এবং Super Agent এর মধ্যে প্রধান পার্থক্য হলো:</p><ul><li><strong>Super Agent:</strong> বেশি কমিশন পায় এবং বেশি লাইন পায়</li><li><strong>Master Agent:</strong> নির্দিষ্ট এরিয়ার জন্য দায়িত্বশীল</li></ul>',
        '<p>পেমেন্ট সাধারণত ১-৩ ঘন্টার মধ্যে প্রক্রিয়া করা হয়। পিক আওয়ারে এটি ৬ ঘন্টা পর্যন্ত সময় নিতে পারে।</p>',
        '<p>প্রত্যাহার রিকোয়েস্টের পর ২৪ ঘন্টার মধ্যে অনুমোদন করা হয় এবং পেমেন্ট ৪৮ ঘন্টার মধ্যে পাবেন।</p>',
        '<p>না, আপনাকে নাম দিতে হবে না। শুধুমাত্র ট্রানজেকশন আইডি এবং পরিমাণ যথেষ্ট।</p>',
        '<p>বোনাস পেতে:</p><ol><li>প্রথম ডিপোজিটে ১০০% ওয়েলকাম বোনাস</li><li>প্রতিদিনের অফার চেক করুন</li><li>রেফার করে বোনাস পান</li></ol>',
        '<p>আপনি যত কম টাকা দিয়ে চান শুরু করতে পারেন। মিনিমাম ডিপোজিট ১০০ টাকা থেকে শুরু।</p>',
        '<p>ইনভোয়েস তৈরি করতে আপনার ড্যাশবোর্ডে যান এবং "Create Invoice" বাটনে ক্লিক করুন।</p>',
        '<p>আমরা নিম্নলিখিত পেমেন্ট মেথড সাপোর্ট করি:</p><ul><li>bKash</li><li>Nagad</li><li>Rocket</li><li>Bank Transfer</li></ul>',
        '<p>না, রেজিস্ট্রেশন সম্পূর্ণ ফ্রি। কোনো রেজিস্ট্রেশন ফি নেই।</p>',
        '<p>না, প্রতি ব্যক্তির জন্য শুধুমাত্র একটি অ্যাকাউন্ট অনুমোদিত। একাধিক অ্যাকাউন্ট পাওয়া গেলে সব অ্যাকাউন্ট ব্লক হতে পারে।</p>',
        '<p>কাস্টমার সাপোর্টের জন্য:</p><ul><li>WhatsApp: +880XXXXXXXXX</li><li>Messenger: fb.com/velki</li><li>Email: support@velki.com</li></ul>',
        '<p>বেটিং লিমিট নির্ভর করে আপনার অ্যাকাউন্ট টাইপের উপর। সাধারণত মিনিমাম ৫০ টাকা এবং ম্যাক্সিমাম ১,০০,০০০ টাকা।</p>',
        '<p>অ্যাকাউন্ট ব্লক হলে অবিলম্বে কাস্টমার সাপোর্টে যোগাযোগ করুন এবং আপনার সমস্যা ব্যাখ্যা করুন।</p>',
        '<p>প্রমোশনাল কোড পেতে:</p><ul><li>আমাদের সোশ্যাল মিডিয়া ফলো করুন</li><li>নিউজলেটার সাবস্ক্রাইব করুন</li><li>এজেন্টদের কাছ থেকে পান</li></ul>',
        '<p>পাসওয়ার্ড রিসেট করতে লগইন পেজে "Forgot Password" লিংকে ক্লিক করুন এবং আপনার মোবাইল নম্বর দিয়ে OTP ভেরিফাই করুন।</p>',
        '<p>হ্যাঁ, আমাদের প্ল্যাটফর্মে লাইভ বেটিং সুবিধা উপলব্ধ রয়েছে সব বড় ম্যাচের জন্য।</p>',
        '<p>মোবাইলে ব্যবহার করতে ব্রাউজার দিয়ে আমাদের ওয়েবসাইট ভিজিট করুন অথবা Android/iOS অ্যাপ ডাউনলোড করুন।</p>',
        '<p>জেতা টাকা উত্তোলন রিকোয়েস্টের ২৪-৪৮ ঘন্টার মধ্যে আপনার একাউন্টে পাবেন।</p>',
    );

    // Generate 20 FAQs
    $generated_count = 0;
    $errors = array();

    for ( $i = 0; $i < 20; $i++ ) {
        // Determine if featured (50% chance)
        $is_featured = ( $i % 2 === 0 ) ? '1' : '';

        // Create post
        $post_data = array(
            'post_type'    => 'velki-faq',
            'post_title'   => $faq_questions[ $i ],
            'post_content' => $faq_answers[ $i ],
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
        );

        $post_id = wp_insert_post( $post_data );

        if ( is_wp_error( $post_id ) ) {
            $errors[] = sprintf( __( 'Failed to create FAQ %d: %s', 'wicket' ), $i + 1, $post_id->get_error_message() );
            continue;
        }

        // Add featured meta if applicable
        if ( $is_featured ) {
            update_post_meta( $post_id, '_faq_featured', '1' );
        }

        $generated_count++;
    }

    // Send response
    if ( $generated_count === 20 ) {
        wp_send_json_success( array(
            'message' => sprintf(
                __( 'Successfully generated %d sample FAQs! (%d featured, %d regular)', 'wicket' ),
                $generated_count,
                10,
                10
            )
        ) );
    } else {
        wp_send_json_error( array(
            'message' => sprintf(
                __( 'Generated %d FAQs, but encountered some errors. Please check and try again.', 'wicket' ),
                $generated_count
            )
        ) );
    }
}
add_action( 'wp_ajax_generate_sample_faqs', 'wicket_ajax_generate_sample_faqs' );
