<?php
/**
 * PRODUCTION FIX for UserInfo Manager
 *
 * URGENT: Add this code to your production userinfo-manager.php file
 * Location: After line 59 (after add_action('init', 'userinfo_register_post_type');)
 *
 * This will activate the external CSS/JS files that are already uploaded to your server
 */

/**
 * Enqueue frontend styles and scripts
 * Version: 1.4.2 - Conflict-free implementation
 */
function userinfo_enqueue_frontend_assets() {
    $plugin_version = '1.4.2';

    // Enqueue CSS
    wp_enqueue_style(
        'userinfo-frontend-styles',
        plugin_dir_url(__FILE__) . 'assets/css/userinfo-frontend.css',
        array(),
        $plugin_version,
        'all'
    );

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue JavaScript
    wp_enqueue_script(
        'userinfo-frontend-script',
        plugin_dir_url(__FILE__) . 'assets/js/userinfo-frontend.js',
        array('jquery'),
        $plugin_version,
        true
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
 * INSTALLATION INSTRUCTIONS:
 *
 * 1. Connect to your production server via FTP/cPanel File Manager
 *
 * 2. Navigate to: /wp-content/plugins/userinfo-manager/
 *
 * 3. Download a backup of userinfo-manager.php first!
 *
 * 4. Edit userinfo-manager.php
 *
 * 5. Find line 59: add_action('init', 'userinfo_register_post_type');
 *
 * 6. Paste the ENTIRE function above (lines 14-55) right AFTER line 59
 *
 * 7. Change version from 1.4.1 to 1.4.2 in the plugin header (line 6)
 *
 * 8. Save the file
 *
 * 9. Go to WordPress Admin → Plugins → Deactivate UserInfo Manager
 *
 * 10. Activate UserInfo Manager again
 *
 * 11. Clear all caches (WordPress cache + browser cache)
 *
 * 12. Test the page: https://agent9w.com/lottery/
 *
 * VERIFICATION:
 * After implementing, check browser DevTools (F12) → Network tab:
 * ✅ Should see: userinfo-frontend.css (Status: 200)
 * ✅ Should see: userinfo-frontend.js (Status: 200)
 * ✅ Console: Type "UserinfoManager" - should return object
 *
 * The form will instantly look better with proper glassmorphism design!
 */
