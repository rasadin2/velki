<?php
/**
 * COMPLETE REPLACEMENT FOR userinfo_tabs_shortcode()
 *
 * INSTRUCTIONS:
 * 1. Find function userinfo_tabs_shortcode() in your production file (line ~3130)
 * 2. DELETE the entire existing function
 * 3. PASTE this complete function in its place
 * 4. Save and test
 *
 * This version includes proper asset loading!
 */

function userinfo_tabs_shortcode($atts) {
    // ============================================
    // CRITICAL FIX: Load external CSS/JS assets
    // ============================================
    $plugin_version = '1.4.2';

    // Enqueue CSS
    if (!wp_style_is('userinfo-frontend-styles', 'enqueued')) {
        wp_enqueue_style(
            'userinfo-frontend-styles',
            plugin_dir_url(__FILE__) . 'assets/css/userinfo-frontend.css',
            array(),
            $plugin_version,
            'all'
        );
    }

    // Ensure jQuery is loaded
    wp_enqueue_script('jquery');

    // Enqueue JavaScript
    if (!wp_script_is('userinfo-frontend-script', 'enqueued')) {
        wp_enqueue_script(
            'userinfo-frontend-script',
            plugin_dir_url(__FILE__) . 'assets/js/userinfo-frontend.js',
            array('jquery'),
            $plugin_version,
            true
        );

        // Localize script
        wp_localize_script('userinfo-frontend-script', 'userinfo_l10n', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'verifying' => __('Verifying...', 'userinfo-manager'),
            'verify_user' => __('Verify User', 'userinfo-manager'),
            'user_found' => __('User Found', 'userinfo-manager'),
            'full_name' => __('Full Name', 'userinfo-manager'),
            'username' => __('Username', 'userinfo-manager'),
            'agent_id' => __('Agent ID', 'userinfo-manager'),
            'phone' => __('Phone', 'userinfo-manager'),
            'nid' => __('NID', 'userinfo-manager'),
            'status' => __('Status', 'userinfo-manager'),
            'nid_image' => __('NID Image', 'userinfo-manager'),
            'error_occurred' => __('An error occurred. Please try again.', 'userinfo-manager'),
        ));
    }
    // ============================================
    // END CRITICAL FIX
    // ============================================

    // Add inline script only once using static variable
    static $script_added = false;
    if (!$script_added) {
        $inline_script = "
        jQuery(document).ready(function($) {
            // Tab switching with advanced animations
            $('.userinfo-tab-btn').on('click', function() {
                var \$clickedBtn = $(this);
                var tabName = \$clickedBtn.data('tab');
                var \$currentContent = $('.userinfo-tab-content.active');
                var \$targetContent = $('#' + tabName + '-tab');

                // Don't do anything if clicking on already active tab
                if (\$clickedBtn.hasClass('active')) {
                    return;
                }

                // Add slide-out animation to current content
                \$currentContent.addClass('slide-out');

                // Wait for slide-out animation to complete
                setTimeout(function() {
                    // Remove active and slide-out class from all content
                    $('.userinfo-tab-content').removeClass('active slide-out');

                    // Remove active class from all buttons
                    $('.userinfo-tab-btn').removeClass('active');

                    // Add active class to clicked button
                    \$clickedBtn.addClass('active');

                    // Show target content with slide-in animation
                    \$targetContent.addClass('active');

                    // Scroll to top of tabs on mobile
                    if ($(window).width() <= 768) {
                        $('html, body').animate({
                            scrollTop: $('.userinfo-tabs-container').offset().top - 20
                        }, 300);
                    }
                }, 400); // Match the slideOutScale animation duration
            });

            // Add ripple effect on tab click
            $('.userinfo-tab-btn').on('mousedown', function(e) {
                var \$btn = $(this);
                var x = e.pageX - \$btn.offset().left;
                var y = e.pageY - \$btn.offset().top;

                var \$ripple = $('<span class=\"ripple\"></span>');
                \$ripple.css({
                    left: x + 'px',
                    top: y + 'px'
                });

                \$btn.append(\$ripple);

                setTimeout(function() {
                    \$ripple.remove();
                }, 600);
            });
        });
        ";

        wp_add_inline_script('jquery', $inline_script);
        $script_added = true;
    }

    ob_start();

    // Get plugin URL for logo
    $plugin_url = plugin_dir_url(__FILE__);
    $logo_url = $plugin_url . 'assets/logo.webp';

    // Include the rest of the tabs shortcode HTML output
    // (Copy the rest from your existing function starting from line 3202)
    // This is just the function signature and asset loading fix
    // The HTML output code should remain exactly the same as before

    ?>
    <div class="userinfo-page-wrapper">
        <!-- Company Logo - Outside Form -->
        <div class="userinfo-company-logo">
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php esc_attr_e('Company Logo', 'userinfo-manager'); ?>">
        </div>

        <div class="userinfo-tabs-container">
            <!-- Tab Navigation -->
            <div class="userinfo-tab-navigation">
            <button class="userinfo-tab-btn active" data-tab="registration">
                <span class="tab-icon">📝</span>
                <?php _e('Registration', 'userinfo-manager'); ?>
            </button>
            <button class="userinfo-tab-btn" data-tab="status-check">
                <span class="tab-icon">✓</span>
                <?php _e('Status Check', 'userinfo-manager'); ?>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="userinfo-tab-content-wrapper">
            <!-- Registration Tab Content -->
            <div id="registration-tab" class="userinfo-tab-content active">
                <?php
                // Get dynamic title and welcome message from settings
                $registration_title = get_option('userinfo_registration_title');
                $welcome_message = get_option('userinfo_welcome_message');

                // Set defaults if not found in database
                if (empty($registration_title)) {
                    $registration_title = 'লটারি রেজিস্ট্রেশন';
                }
                if (empty($welcome_message)) {
                    $welcome_message = 'আমাদের প্ল্যাটফর্মে আপনাকে স্বাগতম। অনুগ্রহ করে নিচের ফর্মটি পূরণ করে আপনার লটারি রেজিস্ট্রেশন সম্পন্ন করুন। আপনার সকল তথ্য সম্পূর্ণ নিরাপদ এবং গোপনীয় থাকবে।';
                }
                ?>

                <!-- Dynamic Title -->
                <div class="userinfo-registration-title" style="margin-top: 0; padding-top: 0;">
                    <h2 style="color: #2c3e50; font-size: 28px; font-weight: bold; text-align: center; margin: 0 0 15px 0; padding-top: 0; text-transform: none;">
                        <?php echo esc_html($registration_title); ?>
                    </h2>
                </div>

                <!-- Dynamic Welcome Message -->
                <div class="userinfo-welcome-message" style="margin-top: 0;">
                    <p style="color: #34495e; font-size: 16px; line-height: 1.6; text-align: center; margin: 0 0 20px 0;">
                        <?php echo esc_html($welcome_message); ?>
                    </p>
                </div>

                <style>
                    /* Responsive Design for Title and Welcome Message */
                    @media (max-width: 768px) {
                        .userinfo-registration-title h2 {
                            font-size: 22px !important;
                            margin-bottom: 12px !important;
                        }

                        .userinfo-welcome-message p {
                            font-size: 14px !important;
                            margin-bottom: 15px !important;
                            padding: 0 10px !important;
                        }
                    }

                    @media (max-width: 480px) {
                        .userinfo-registration-title h2 {
                            font-size: 20px !important;
                            margin-bottom: 10px !important;
                        }

                        .userinfo-welcome-message p {
                            font-size: 13px !important;
                            margin-bottom: 15px !important;
                            padding: 0 5px !important;
                            line-height: 1.5 !important;
                        }
                    }
                </style>

                <?php
                // Include the registration form shortcode
                echo userinfo_form_shortcode($atts);
                ?>
            </div>

            <!-- Status Check Tab Content -->
            <div id="status-check-tab" class="userinfo-tab-content">
                <?php
                // Include the check shortcode
                echo userinfo_check_shortcode($atts);
                ?>
            </div>
        </div>
    </div>

    <!-- IMPORTANT: Remove the <style> block if it exists below this point -->
    <!-- The styles are now in external CSS file -->

    <?php
    return ob_get_clean();
}

/**
 * NOTE: This file only shows the BEGINNING of the function with the FIX added.
 *
 * To implement:
 * 1. Copy lines 10-54 (the asset loading code)
 * 2. Paste at the very TOP of your existing userinfo_tabs_shortcode() function
 * 3. That's it! The rest of the function stays the same.
 *
 * VERIFICATION after deployment:
 * - Visit https://agent9w.com/lottery/
 * - F12 → Network tab
 * - Should see: userinfo-frontend.css (200 OK)
 * - Should see: userinfo-frontend.js (200 OK)
 * - Form should look beautiful with glassmorphism design
 */
