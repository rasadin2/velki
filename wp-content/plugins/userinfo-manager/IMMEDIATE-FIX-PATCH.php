<?php
/**
 * IMMEDIATE FIX - Self-Contained Asset Loading
 *
 * SOLUTION: Modify the shortcode function to load assets directly
 * This works WITHOUT needing to add the separate enqueue function
 *
 * LOCATION: Find the userinfo_tabs_shortcode() function in userinfo-manager.php
 * LINE: Around 3130
 *
 * REPLACE the existing function with this version:
 */

function userinfo_tabs_shortcode($atts) {
    // IMMEDIATE FIX: Enqueue assets directly in shortcode
    $plugin_version = '1.4.2';

    // Enqueue CSS if not already enqueued
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

    // Enqueue JavaScript if not already enqueued
    if (!wp_script_is('userinfo-frontend-script', 'enqueued')) {
        wp_enqueue_script(
            'userinfo-frontend-script',
            plugin_dir_url(__FILE__) . 'assets/js/userinfo-frontend.js',
            array('jquery'),
            $plugin_version,
            true
        );

        // Localize script for translations
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

    // Add inline script only once using static variable
    static $script_added = false;
    if (!$script_added) {
        // ... (keep the existing inline script here for tab switching)
        // Don't remove this part - it handles tab switching
    }

    // ... rest of the function remains the same ...
}

/**
 * ALTERNATIVE APPROACH: Do the same for userinfo_form_shortcode() and userinfo_check_shortcode()
 *
 * Add these same lines at the beginning of EACH shortcode function:
 */

// In userinfo_form_shortcode() - Add after line 629
// In userinfo_check_shortcode() - Add after line 2475

/**
 * DEPLOYMENT STEPS:
 *
 * 1. Connect to production server (FTP/cPanel)
 * 2. Navigate to /wp-content/plugins/userinfo-manager/
 * 3. Backup userinfo-manager.php
 * 4. Edit userinfo-manager.php
 * 5. Find function userinfo_tabs_shortcode() (around line 3130)
 * 6. Add the asset enqueuing code at the TOP of the function (first 30 lines above)
 * 7. Repeat for userinfo_form_shortcode() (line ~629)
 * 8. Repeat for userinfo_check_shortcode() (line ~2475)
 * 9. Save file
 * 10. Deactivate/Activate plugin
 * 11. Clear all caches
 * 12. Test site
 *
 * WHY THIS WORKS:
 * - Loads assets when shortcode is actually used
 * - No separate enqueue function needed
 * - Works immediately
 * - Prevents duplicate loading with wp_style_is() check
 *
 * VERIFICATION:
 * After deploying, check:
 * - F12 → Network tab: userinfo-frontend.css (200 OK)
 * - F12 → Network tab: userinfo-frontend.js (200 OK)
 * - F12 → Console: No errors
 * - Form looks perfect with glassmorphism design
 */
