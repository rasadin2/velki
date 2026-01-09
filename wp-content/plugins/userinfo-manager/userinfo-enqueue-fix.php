<?php
/**
 * UserInfo Manager - Asset Enqueue Fix
 *
 * This file contains the proper enqueue functions to prevent conflicts
 * Copy these functions into your main plugin file (userinfo-manager.php)
 *
 * Version: 1.4.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue frontend styles and scripts
 * Add this function to your plugin and hook it to wp_enqueue_scripts
 */
function userinfo_enqueue_frontend_assets() {
    // Get the plugin version for cache busting
    $plugin_version = '1.4.2';

    // Enqueue CSS
    wp_enqueue_style(
        'userinfo-frontend-styles',
        plugin_dir_url(__FILE__) . 'assets/css/userinfo-frontend.css',
        array(),
        $plugin_version,
        'all'
    );

    // Enqueue jQuery (WordPress core)
    wp_enqueue_script('jquery');

    // Enqueue custom JavaScript
    wp_enqueue_script(
        'userinfo-frontend-script',
        plugin_dir_url(__FILE__) . 'assets/js/userinfo-frontend.js',
        array('jquery'),
        $plugin_version,
        true // Load in footer
    );

    // Localize script for translations and AJAX
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
add_action('wp_enqueue_scripts', 'userinfo_enqueue_frontend_assets');

/**
 * INSTRUCTIONS FOR IMPLEMENTATION:
 *
 * 1. Add the above function to your userinfo-manager.php file (around line 60, after the CPT registration)
 *
 * 2. Remove ALL <style> blocks from the shortcode functions:
 *    - userinfo_form_shortcode() - Remove the <style> block starting around line 806
 *    - userinfo_check_shortcode() - Remove the <style> block starting around line 2638
 *    - userinfo_tabs_shortcode() - Remove any <style> blocks
 *
 * 3. Remove ALL inline JavaScript from shortcodes:
 *    - In userinfo_check_shortcode(), remove the wp_add_inline_script() call around line 2576
 *    - Remove the static $check_script_added variable and inline script
 *
 * 4. In userinfo_form_shortcode(), add this JavaScript snippet at the end (before ob_get_clean):
 *    ?>
 *    <script type="text/javascript">
 *    // Trigger initialization after shortcode loads
 *    jQuery(document).ready(function($) {
 *        if (window.UserinfoManager) {
 *            window.UserinfoManager.init();
 *        }
 *    });
 *    </script>
 *    <?php
 *
 * 5. Update the plugin version in the main file header to 1.4.2
 *
 * 6. Test thoroughly in a different theme to ensure no conflicts
 */

/**
 * Additional security: Add nonce verification to AJAX handlers
 * This is already implemented but ensure it's present
 */

/**
 * Optional: Add admin notice to guide users after update
 */
function userinfo_admin_notice_assets_update() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'edit-userinfo') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong><?php _e('UserInfo Manager Updated!', 'userinfo-manager'); ?></strong>
                <?php _e('Assets are now properly isolated to prevent theme/plugin conflicts.', 'userinfo-manager'); ?>
            </p>
        </div>
        <?php
    }
}
// Uncomment to show admin notice
// add_action('admin_notices', 'userinfo_admin_notice_assets_update');
