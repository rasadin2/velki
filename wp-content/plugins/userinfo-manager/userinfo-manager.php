<?php
/**
 * Plugin Name: UserInfo Manager
 * Plugin URI: https://example.com
 * Description: Custom post type for managing user information with frontend form submission, including image upload/remove/replace, submission date tracking, month filtering, and CSV export
 * Version: 1.5.3
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: userinfo-manager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get next sequential registration ID starting from 9000
 * Uses atomic database operation to prevent race conditions and duplicate IDs
 *
 * @return string Registration ID (e.g., "9000", "9001", "9002")
 */
function userinfo_get_next_registration_id() {
    global $wpdb;

    $option_name = 'userinfo_registration_counter';

    // Try to get current value
    $current_value = get_option($option_name, false);

    // If option doesn't exist, initialize it to 8999 (next will be 9000)
    if ($current_value === false) {
        add_option($option_name, 8999, '', 'no');
        $current_value = 8999;
    }

    // Atomic increment using direct SQL UPDATE
    // This prevents race conditions even with simultaneous submissions
    $updated = $wpdb->query($wpdb->prepare(
        "UPDATE {$wpdb->options}
         SET option_value = option_value + 1
         WHERE option_name = %s
         AND option_value = %d",
        $option_name,
        $current_value
    ));

    // If update succeeded, get the new value
    if ($updated) {
        $new_id = get_option($option_name);
    } else {
        // Race condition occurred, retry recursively
        // This happens when another request updated the value between our read and write
        return userinfo_get_next_registration_id();
    }

    return strval($new_id);
}

/**
 * Register UserInfo Custom Post Type
 */
function userinfo_register_post_type() {
    $labels = array(
        'name'                  => _x('User Info', 'Post type general name', 'userinfo-manager'),
        'singular_name'         => _x('User Info', 'Post type singular name', 'userinfo-manager'),
        'menu_name'             => _x('User Info', 'Admin Menu text', 'userinfo-manager'),
        'name_admin_bar'        => _x('User Info', 'Add New on Toolbar', 'userinfo-manager'),
        'add_new'               => __('Add New', 'userinfo-manager'),
        'add_new_item'          => __('Add New User Info', 'userinfo-manager'),
        'new_item'              => __('New User Info', 'userinfo-manager'),
        'edit_item'             => __('Edit User Info', 'userinfo-manager'),
        'view_item'             => __('View User Info', 'userinfo-manager'),
        'all_items'             => __('All User Info', 'userinfo-manager'),
        'search_items'          => __('Search User Info', 'userinfo-manager'),
        'not_found'             => __('No user info found.', 'userinfo-manager'),
        'not_found_in_trash'    => __('No user info found in Trash.', 'userinfo-manager'),
    );

    $args = array(
        'labels'                => $labels,
        'description'           => __('User information submitted via frontend form', 'userinfo-manager'),
        'public'                => false,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'userinfo'),
        'capability_type'       => 'post',
        'has_archive'           => false,
        'hierarchical'          => false,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-id-alt',
        'supports'              => array('title'),
        'show_in_rest'          => false,
    );

    register_post_type('userinfo', $args);
}
add_action('init', 'userinfo_register_post_type');

/**
 * Register Result Generate Custom Post Type
 */
function userinfo_register_result_generate_post_type() {
    $labels = array(
        'name'                  => _x('Result Generate', 'Post type general name', 'userinfo-manager'),
        'singular_name'         => _x('Result', 'Post type singular name', 'userinfo-manager'),
        'menu_name'             => _x('Result Generate', 'Admin Menu text', 'userinfo-manager'),
        'add_new'               => __('Add New Result', 'userinfo-manager'),
        'add_new_item'          => __('Add New Result', 'userinfo-manager'),
        'new_item'              => __('New Result', 'userinfo-manager'),
        'edit_item'             => __('Edit Result', 'userinfo-manager'),
        'view_item'             => __('View Result', 'userinfo-manager'),
        'all_items'             => __('Result Generate', 'userinfo-manager'),
        'search_items'          => __('Search Results', 'userinfo-manager'),
        'not_found'             => __('No results found.', 'userinfo-manager'),
        'not_found_in_trash'    => __('No results found in Trash.', 'userinfo-manager'),
    );

    $args = array(
        'labels'                => $labels,
        'description'           => __('Generate and manage lottery results', 'userinfo-manager'),
        'public'                => false,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'show_in_menu'          => 'edit.php?post_type=userinfo',
        'query_var'             => true,
        'rewrite'               => array('slug' => 'result-generate'),
        'capability_type'       => 'post',
        'has_archive'           => false,
        'hierarchical'          => false,
        'supports'              => array('title'),
        'show_in_rest'          => false,
    );

    register_post_type('result_generate', $args);
}
add_action('init', 'userinfo_register_result_generate_post_type');

/**
 * Add Selected Users submenu
 */
function userinfo_add_selected_users_menu() {
    add_submenu_page(
        'edit.php?post_type=userinfo',
        __('Selected Users', 'userinfo-manager'),
        __('Selected Users', 'userinfo-manager'),
        'edit_posts',
        'userinfo-selected',
        'userinfo_selected_users_page'
    );
}
add_action('admin_menu', 'userinfo_add_selected_users_menu');

/**
 * Add Prize Management submenu
 */
function userinfo_add_prize_management_menu() {
    add_submenu_page(
        'edit.php?post_type=userinfo',
        __('Prize Management', 'userinfo-manager'),
        __('Prize Management', 'userinfo-manager'),
        'manage_options',
        'userinfo-prize-management',
        'userinfo_prize_management_page'
    );
}
add_action('admin_menu', 'userinfo_add_prize_management_menu');

/**
 * Add Settings submenu
 */
function userinfo_add_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=userinfo',
        __('Settings', 'userinfo-manager'),
        __('Settings', 'userinfo-manager'),
        'manage_options',
        'userinfo-settings',
        'userinfo_settings_page'
    );
}
add_action('admin_menu', 'userinfo_add_settings_menu');

/**
 * Settings page display
 */
function userinfo_settings_page() {
    // Handle form submission
    if (isset($_POST['save_settings']) && check_admin_referer('userinfo_save_settings', 'userinfo_settings_nonce')) {
        $title = sanitize_text_field($_POST['registration_title']);
        $welcome_message = sanitize_textarea_field($_POST['welcome_message']);
        $countdown_enabled = isset($_POST['countdown_enabled']) ? 1 : 0;
        $custom_countdown_enabled = isset($_POST['custom_countdown_enabled']) ? 1 : 0;
        $custom_countdown_date = sanitize_text_field($_POST['custom_countdown_date']);

        // Mutual exclusion: if one is enabled, disable the other
        if ($countdown_enabled && $custom_countdown_enabled) {
            // If both checked, prefer the one that was just changed (custom has priority in this case)
            $countdown_enabled = 0;
        }

        // Validate custom date (must be today or future date)
        if ($custom_countdown_enabled && !empty($custom_countdown_date)) {
            $selected_date = strtotime($custom_countdown_date . ' 23:59:59');
            $current_date = current_time('timestamp');
            if ($selected_date < $current_date) {
                $custom_countdown_enabled = 0;
                $custom_countdown_date = '';
                echo '<div class="notice notice-error"><p>' . __('Custom countdown date cannot be in the past!', 'userinfo-manager') . '</p></div>';
            }
        }

        // Process terms and conditions
        $terms_conditions = array();
        if (isset($_POST['terms_item']) && is_array($_POST['terms_item'])) {
            foreach ($_POST['terms_item'] as $item) {
                $item_text = sanitize_textarea_field($item);
                if (!empty($item_text)) {
                    $terms_conditions[] = $item_text;
                }
            }
        }

        update_option('userinfo_registration_title', $title);
        update_option('userinfo_welcome_message', $welcome_message);
        update_option('userinfo_countdown_enabled', $countdown_enabled);
        update_option('userinfo_custom_countdown_enabled', $custom_countdown_enabled);
        update_option('userinfo_custom_countdown_date', $custom_countdown_date);
        update_option('userinfo_terms_conditions', $terms_conditions);

        echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'userinfo-manager') . '</p></div>';
    }

    // Get existing data or set defaults
    $registration_title = get_option('userinfo_registration_title', 'লটারি রেজিস্ট্রেশন');
    $welcome_message = get_option('userinfo_welcome_message', 'আমাদের প্ল্যাটফর্মে আপনাকে স্বাগতম। অনুগ্রহ করে নিচের ফর্মটি পূরণ করে আপনার লটারি রেজিস্ট্রেশন সম্পন্ন করুন। আপনার সকল তথ্য সম্পূর্ণ নিরাপদ এবং গোপনীয় থাকবে।');
    $countdown_enabled = get_option('userinfo_countdown_enabled', 0);
    $custom_countdown_enabled = get_option('userinfo_custom_countdown_enabled', 0);
    $custom_countdown_date = get_option('userinfo_custom_countdown_date', '');
    $terms_conditions = get_option('userinfo_terms_conditions', array(
        'আপনার সকল তথ্য সম্পূর্ণ গোপনীয় রাখা হবে।',
        'রেজিস্ট্রেশন ফি ফেরতযোগ্য নয়।',
        'বিজয়ীদের তালিকা প্রতি মাসের ৫ তারিখে ঘোষণা করা হবে।',
        'পুরস্কার প্রাপ্তিতে বৈধ পরিচয়পত্র প্রয়োজন হবে।'
    ));

    ?>
    <div class="wrap">
        <h1><?php _e('User Info Settings', 'userinfo-manager'); ?></h1>
        <p><?php _e('Manage registration form title and welcome message.', 'userinfo-manager'); ?></p>

        <form method="post" action="">
            <?php wp_nonce_field('userinfo_save_settings', 'userinfo_settings_nonce'); ?>

            <div class="settings-container">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="registration_title"><?php _e('Registration Form Title', 'userinfo-manager'); ?></label>
                            </th>
                            <td>
                                <input type="text"
                                       id="registration_title"
                                       name="registration_title"
                                       value="<?php echo esc_attr($registration_title); ?>"
                                       class="large-text"
                                       placeholder="লটারি রেজিস্ট্রেশন" />
                                <p class="description"><?php _e('This title will appear above the welcome message in the registration form.', 'userinfo-manager'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="welcome_message"><?php _e('Welcome Message', 'userinfo-manager'); ?></label>
                            </th>
                            <td>
                                <textarea id="welcome_message"
                                          name="welcome_message"
                                          rows="5"
                                          class="large-text"><?php echo esc_textarea($welcome_message); ?></textarea>
                                <p class="description"><?php _e('This message will appear below the title in the registration form.', 'userinfo-manager'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="countdown_enabled"><?php _e('Enable Monthly Countdown', 'userinfo-manager'); ?></label>
                            </th>
                            <td>
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox"
                                           id="countdown_enabled"
                                           name="countdown_enabled"
                                           value="1"
                                           <?php checked($countdown_enabled, 1); ?> />
                                    <span><?php _e('Show countdown timer until month end', 'userinfo-manager'); ?></span>
                                </label>
                                <p class="description">
                                    <?php _e('When enabled, displays a countdown timer showing time remaining until the end of the current month. When disabled and month ends, registration form will be hidden.', 'userinfo-manager'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="custom_countdown_enabled"><?php _e('Enable Custom Date Countdown', 'userinfo-manager'); ?></label>
                            </th>
                            <td>
                                <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                                    <input type="checkbox"
                                           id="custom_countdown_enabled"
                                           name="custom_countdown_enabled"
                                           value="1"
                                           <?php checked($custom_countdown_enabled, 1); ?> />
                                    <span><?php _e('Show countdown timer until custom date', 'userinfo-manager'); ?></span>
                                </label>
                                <div style="margin-top: 10px;">
                                    <label for="custom_countdown_date" style="display: block; margin-bottom: 5px; font-weight: 600;">
                                        <?php _e('Select End Date:', 'userinfo-manager'); ?>
                                    </label>
                                    <input type="date"
                                           id="custom_countdown_date"
                                           name="custom_countdown_date"
                                           value="<?php echo esc_attr($custom_countdown_date); ?>"
                                           min="<?php echo date('Y-m-d'); ?>"
                                           style="padding: 6px 10px; font-size: 14px; border: 1px solid #ddd; border-radius: 4px;" />
                                </div>
                                <p class="description">
                                    <?php _e('When enabled, displays a countdown timer until the selected date. Note: Enabling this will disable the monthly countdown option. You can select today or any future date.', 'userinfo-manager'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Terms and Conditions Section -->
                <h2 style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0;"><?php _e('Terms and Conditions', 'userinfo-manager'); ?></h2>
                <p class="description"><?php _e('Add terms and conditions items that will appear below the registration form.', 'userinfo-manager'); ?></p>

                <div id="terms-conditions-container">
                    <?php if (!empty($terms_conditions)): ?>
                        <?php foreach ($terms_conditions as $index => $term): ?>
                            <div class="terms-item" style="margin-bottom: 15px; padding: 15px; background: #f9f9f9; border-radius: 5px; border: 1px solid #ddd;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 15px;">
                                    <div style="flex: 1;">
                                        <label style="font-weight: 600; display: block; margin-bottom: 8px;">
                                            <?php printf(__('Term #%d', 'userinfo-manager'), $index + 1); ?>
                                        </label>
                                        <textarea name="terms_item[]"
                                                  rows="2"
                                                  class="large-text"
                                                  placeholder="<?php _e('Enter term or condition...', 'userinfo-manager'); ?>"><?php echo esc_textarea($term); ?></textarea>
                                    </div>
                                    <button type="button" class="button remove-term-btn" style="background: #dc3232; color: white; border-color: #dc3232; margin-top: 28px;">
                                        <?php _e('Remove', 'userinfo-manager'); ?>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <p style="margin-top: 15px;">
                    <button type="button" id="add-term-btn" class="button button-secondary">
                        <span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span>
                        <?php _e('Add New Term', 'userinfo-manager'); ?>
                    </button>
                </p>

                <p class="submit">
                    <input type="submit"
                           name="save_settings"
                           class="button button-primary button-large"
                           value="<?php _e('Save Settings', 'userinfo-manager'); ?>" />
                </p>
            </div>
        </form>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Mutual exclusion between monthly and custom countdown
            $('#countdown_enabled').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#custom_countdown_enabled').prop('checked', false);
                }
            });

            $('#custom_countdown_enabled').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#countdown_enabled').prop('checked', false);
                }
            });

            // Add new term
            $('#add-term-btn').on('click', function() {
                var index = $('#terms-conditions-container .terms-item').length + 1;
                var termHtml = '<div class="terms-item" style="margin-bottom: 15px; padding: 15px; background: #f9f9f9; border-radius: 5px; border: 1px solid #ddd;">' +
                    '<div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 15px;">' +
                    '<div style="flex: 1;">' +
                    '<label style="font-weight: 600; display: block; margin-bottom: 8px;"><?php _e('Term', 'userinfo-manager'); ?> #' + index + '</label>' +
                    '<textarea name="terms_item[]" rows="2" class="large-text" placeholder="<?php _e('Enter term or condition...', 'userinfo-manager'); ?>"></textarea>' +
                    '</div>' +
                    '<button type="button" class="button remove-term-btn" style="background: #dc3232; color: white; border-color: #dc3232; margin-top: 28px;"><?php _e('Remove', 'userinfo-manager'); ?></button>' +
                    '</div>' +
                    '</div>';
                $('#terms-conditions-container').append(termHtml);
            });

            // Remove term
            $(document).on('click', '.remove-term-btn', function() {
                if (confirm('<?php _e('Are you sure you want to remove this term?', 'userinfo-manager'); ?>')) {
                    $(this).closest('.terms-item').fadeOut(300, function() {
                        $(this).remove();
                        // Renumber remaining terms
                        $('#terms-conditions-container .terms-item').each(function(i) {
                            $(this).find('label').text('<?php _e('Term', 'userinfo-manager'); ?> #' + (i + 1));
                        });
                    });
                }
            });
        });
        </script>

        <style>
            .settings-container {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                margin-top: 20px;
            }

            .settings-container .form-table th {
                width: 250px;
                font-weight: 600;
            }

            .settings-container textarea,
            .settings-container input[type="text"] {
                font-size: 14px;
            }

            .settings-container .description {
                color: #666;
                font-style: italic;
                margin-top: 8px;
            }
        </style>
    </div>
    <?php
}

/**
 * Selected Users page display
 */
function userinfo_selected_users_page() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e('Selected Users', 'userinfo-manager'); ?></h1>
        <hr class="wp-header-end">

        <?php
        // Date filters
        global $wpdb;

        // Get all unique shortlist months for date range
        $months = $wpdb->get_results("
            SELECT DISTINCT YEAR(meta_value) as year, MONTH(meta_value) as month
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_userinfo_submitted_date'
            AND post_id IN (
                SELECT post_id FROM {$wpdb->postmeta}
                WHERE meta_key = '_userinfo_shortlisted' AND meta_value = '1'
            )
            AND meta_value != ''
            ORDER BY meta_value DESC
        ");

        $selected_month = isset($_GET['shortlist_month']) ? sanitize_text_field($_GET['shortlist_month']) : '';
        $selected_date = isset($_GET['shortlist_date']) ? sanitize_text_field($_GET['shortlist_date']) : '';
        $selected_date_from = isset($_GET['shortlist_date_from']) ? sanitize_text_field($_GET['shortlist_date_from']) : '';
        $selected_date_to = isset($_GET['shortlist_date_to']) ? sanitize_text_field($_GET['shortlist_date_to']) : '';

        // Get min and max dates for calendar range
        $min_month = '';
        $max_month = '';
        $min_date = '';
        $max_date = '';
        if (!empty($months)) {
            $last_month = end($months);
            $first_month = reset($months);
            $min_month = sprintf('%04d-%02d', $last_month->year, $last_month->month);
            $max_month = sprintf('%04d-%02d', $first_month->year, $first_month->month);
            $min_date = sprintf('%04d-%02d-01', $last_month->year, $last_month->month);
            $max_date = date('Y-m-d');
        }

        // Check if any filter is active
        $has_active_filter = !empty($selected_month) || !empty($selected_date) || !empty($selected_date_from) || !empty($selected_date_to);

        if (!empty($months)) {
            ?>
            <div class="tablenav top" style="margin: 20px 0;">
                <div class="alignleft actions">
                    <!-- Month Filter -->
                    <div style="display: inline-block; vertical-align: middle; margin-right: 15px;">
                        <label for="shortlist_month" style="margin-right: 5px; font-weight: normal;">
                            <?php _e('Month:', 'userinfo-manager'); ?>
                        </label>
                        <input
                            type="month"
                            name="shortlist_month"
                            id="shortlist_month"
                            value="<?php echo esc_attr($selected_month); ?>"
                            min="<?php echo esc_attr($min_month); ?>"
                            max="<?php echo esc_attr($max_month); ?>"
                            style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle;"
                        />
                    </div>

                    <!-- Single Date Filter -->
                    <div style="display: inline-block; vertical-align: middle; margin-right: 15px;">
                        <label for="shortlist_date" style="margin-right: 5px; font-weight: normal;">
                            <?php _e('Date:', 'userinfo-manager'); ?>
                        </label>
                        <input
                            type="date"
                            name="shortlist_date"
                            id="shortlist_date"
                            value="<?php echo esc_attr($selected_date); ?>"
                            min="<?php echo esc_attr($min_date); ?>"
                            max="<?php echo esc_attr($max_date); ?>"
                            style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle;"
                        />
                    </div>

                    <!-- Date Range Filter -->
                    <div style="display: inline-block; vertical-align: middle; margin-right: 15px;">
                        <label for="shortlist_date_from" style="margin-right: 5px; font-weight: normal;">
                            <?php _e('From:', 'userinfo-manager'); ?>
                        </label>
                        <input
                            type="date"
                            name="shortlist_date_from"
                            id="shortlist_date_from"
                            value="<?php echo esc_attr($selected_date_from); ?>"
                            min="<?php echo esc_attr($min_date); ?>"
                            max="<?php echo esc_attr($max_date); ?>"
                            style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle; width: 140px;"
                        />
                        <span style="margin: 0 5px;">—</span>
                        <label for="shortlist_date_to" style="margin-right: 5px; font-weight: normal;">
                            <?php _e('To:', 'userinfo-manager'); ?>
                        </label>
                        <input
                            type="date"
                            name="shortlist_date_to"
                            id="shortlist_date_to"
                            value="<?php echo esc_attr($selected_date_to); ?>"
                            min="<?php echo esc_attr($min_date); ?>"
                            max="<?php echo esc_attr($max_date); ?>"
                            style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle; width: 140px;"
                        />
                    </div>

                    <!-- Clear All Filters Button -->
                    <?php if ($has_active_filter): ?>
                    <button type="button" id="clear_shortlist_filters" class="button" style="height: 32px; vertical-align: middle;">
                        <?php _e('Clear All Filters', 'userinfo-manager'); ?>
                    </button>
                    <?php endif; ?>

                    <!-- Export CSV Button -->
                    <button type="button" id="export_shortlist_csv" class="button button-primary" style="height: 32px; vertical-align: middle; margin-left: 10px;">
                        <?php _e('Export to CSV', 'userinfo-manager'); ?>
                    </button>
                </div>
            </div>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                function buildFilterUrl() {
                    var url = '<?php echo admin_url('edit.php'); ?>';
                    url += '?post_type=userinfo&page=userinfo-selected';
                    return url;
                }

                function applyFilter(params) {
                    var url = buildFilterUrl();
                    if (params) {
                        url += '&' + $.param(params);
                    }
                    window.location.href = url;
                }

                // Auto-submit when month is selected
                $('#shortlist_month').on('change', function() {
                    if ($(this).val()) {
                        // Clear other filters
                        $('#shortlist_date').val('');
                        $('#shortlist_date_from').val('');
                        $('#shortlist_date_to').val('');
                        applyFilter({ shortlist_month: $(this).val() });
                    }
                });

                // Auto-submit when single date is selected
                $('#shortlist_date').on('change', function() {
                    if ($(this).val()) {
                        // Clear other filters
                        $('#shortlist_month').val('');
                        $('#shortlist_date_from').val('');
                        $('#shortlist_date_to').val('');
                        applyFilter({ shortlist_date: $(this).val() });
                    }
                });

                // Auto-submit when date range is completed
                $('#shortlist_date_from, #shortlist_date_to').on('change', function() {
                    var dateFrom = $('#shortlist_date_from').val();
                    var dateTo = $('#shortlist_date_to').val();

                    // Only submit if both dates are selected
                    if (dateFrom && dateTo) {
                        // Clear other filters
                        $('#shortlist_month').val('');
                        $('#shortlist_date').val('');
                        applyFilter({
                            shortlist_date_from: dateFrom,
                            shortlist_date_to: dateTo
                        });
                    }
                });

                // Clear all filters
                $('#clear_shortlist_filters').on('click', function() {
                    applyFilter();
                });

                // Export CSV
                $('#export_shortlist_csv').on('click', function() {
                    var url = '<?php echo admin_url('admin-post.php'); ?>';
                    var params = {
                        action: 'userinfo_export_shortlist_csv',
                        nonce: '<?php echo wp_create_nonce('userinfo_export_shortlist_csv'); ?>'
                    };

                    // Add active filter to export
                    var month = $('#shortlist_month').val();
                    var date = $('#shortlist_date').val();
                    var dateFrom = $('#shortlist_date_from').val();
                    var dateTo = $('#shortlist_date_to').val();

                    if (month) {
                        params.shortlist_month = month;
                    } else if (date) {
                        params.shortlist_date = date;
                    } else if (dateFrom && dateTo) {
                        params.shortlist_date_from = dateFrom;
                        params.shortlist_date_to = dateTo;
                    }

                    window.location.href = url + '?' + $.param(params);
                });
            });
            </script>
            <?php
        }

        // Build query for shortlisted users
        $args = array(
            'post_type'      => 'userinfo',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_userinfo_shortlisted',
                    'value'   => '1',
                    'compare' => '='
                )
            ),
            'orderby'        => 'meta_value',
            'meta_key'       => '_userinfo_submitted_date',
            'order'          => 'DESC'
        );

        // Add date filters (Priority: Date Range > Single Date > Month)
        // Priority 1: Date Range Filter (if both from and to are set)
        if (!empty($selected_date_from) && !empty($selected_date_to) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date_from) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date_to)) {

            $args['meta_query'][] = array(
                'key'     => '_userinfo_submitted_date',
                'value'   => array(
                    $selected_date_from . ' 00:00:00',
                    $selected_date_to . ' 23:59:59'
                ),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            );
        }
        // Priority 2: Single Date Filter
        elseif (!empty($selected_date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
            $args['meta_query'][] = array(
                'key'     => '_userinfo_submitted_date',
                'value'   => array(
                    $selected_date . ' 00:00:00',
                    $selected_date . ' 23:59:59'
                ),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            );
        }
        // Priority 3: Month Filter (original functionality)
        elseif (!empty($selected_month) && preg_match('/^\d{4}-\d{2}$/', $selected_month)) {
            list($year, $month) = explode('-', $selected_month);

            $args['meta_query'][] = array(
                'key'     => '_userinfo_submitted_date',
                'value'   => array(
                    sprintf('%04d-%02d-01 00:00:00', $year, $month),
                    sprintf('%04d-%02d-31 23:59:59', $year, $month)
                ),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            );
        }

        $shortlisted_users = new WP_Query($args);

        if ($shortlisted_users->have_posts()):
            ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Full Name', 'userinfo-manager'); ?></th>
                        <th><?php _e('Username', 'userinfo-manager'); ?></th>
                        <th><?php _e('Registration ID', 'userinfo-manager'); ?></th>
                        <th><?php _e('Agent ID', 'userinfo-manager'); ?></th>
                        <th><?php _e('Phone', 'userinfo-manager'); ?></th>
                        <th><?php _e('Email', 'userinfo-manager'); ?></th>
                        <th><?php _e('Valid', 'userinfo-manager'); ?></th>
                        <th><?php _e('Position', 'userinfo-manager'); ?></th>
                        <th><?php _e('Prize', 'userinfo-manager'); ?></th>
                        <th><?php _e('Selected Month', 'userinfo-manager'); ?></th>
                        <th><?php _e('Submitted Date', 'userinfo-manager'); ?></th>
                        <th><?php _e('Actions', 'userinfo-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($shortlisted_users->have_posts()): $shortlisted_users->the_post(); ?>
                        <?php
                        $post_id = get_the_ID();
                        $full_name = get_post_meta($post_id, '_userinfo_full_name', true);
                        $username = get_post_meta($post_id, '_userinfo_username', true);
                        $registration_id = get_post_meta($post_id, '_userinfo_registration_id', true);
                        $agent_id = get_post_meta($post_id, '_userinfo_agent_id', true);
                        $phone_number = get_post_meta($post_id, '_userinfo_phone_number', true);
                        $email = get_post_meta($post_id, '_userinfo_email', true);
                        $is_valid = get_post_meta($post_id, '_userinfo_is_valid', true);
                        $position = get_post_meta($post_id, '_userinfo_position', true);
                        $prize = get_post_meta($post_id, '_userinfo_prize', true);
                        $shortlist_month = get_post_meta($post_id, '_userinfo_shortlist_month', true);
                        $submitted_date = get_post_meta($post_id, '_userinfo_submitted_date', true);

                        if ($is_valid === '') {
                            $is_valid = '1';
                        }
                        $valid_text = ($is_valid == '1') ? __('Valid', 'userinfo-manager') : __('Invalid', 'userinfo-manager');
                        $valid_class = ($is_valid == '1') ? 'valid' : 'invalid';
                        ?>
                        <tr>
                            <td><?php echo esc_html($full_name); ?></td>
                            <td><?php echo esc_html($username); ?></td>
                            <td><?php echo esc_html($registration_id); ?></td>
                            <td><?php echo esc_html($agent_id); ?></td>
                            <td><?php echo esc_html($phone_number); ?></td>
                            <td><?php echo esc_html($email); ?></td>
                            <td><span class="userinfo-toggle-label <?php echo esc_attr($valid_class); ?>"><?php echo esc_html($valid_text); ?></span></td>
                            <td>
                                <?php if ($position): ?>
                                    <strong style="color: #0073aa;"><?php echo esc_html($position); ?></strong>
                                <?php else: ?>
                                    <span style="color: #999;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($prize): ?>
                                    <span style="color: #46b450; font-weight: 600;"><?php echo esc_html($prize); ?></span>
                                <?php else: ?>
                                    <span style="color: #999;">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $shortlist_month ? esc_html(date('F Y', strtotime($shortlist_month . '-01'))) : '—'; ?></td>
                            <td><?php echo $submitted_date ? esc_html(date_i18n(get_option('date_format'), strtotime($submitted_date))) : '—'; ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('post.php?post=' . $post_id . '&action=edit')); ?>" class="button button-small">
                                    <?php _e('Edit', 'userinfo-manager'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <p style="margin-top: 20px;">
                <strong><?php printf(__('Total Selected Users: %d', 'userinfo-manager'), $shortlisted_users->found_posts); ?></strong>
            </p>
            <?php
        else:
            ?>
            <p><?php _e('No selected users found.', 'userinfo-manager'); ?></p>
            <?php
        endif;

        wp_reset_postdata();
        ?>
    </div>
    <?php
}

/**
 * Prize Management page display with dynamic prize system
 */
function userinfo_prize_management_page() {
    // Define available icons
    $available_icons = array(
        '🥇' => 'Gold Medal',
        '🥈' => 'Silver Medal',
        '🥉' => 'Bronze Medal',
        '🏆' => 'Trophy',
        '⭐' => 'Star',
        '🎁' => 'Gift Box',
        '🎖️' => 'Military Medal',
        '💎' => 'Diamond',
        '👑' => 'Crown',
        '🏅' => 'Sports Medal',
        '💰' => 'Money Bag',
        '💵' => 'Dollar Bill',
        '🎉' => 'Party Popper',
        '✨' => 'Sparkles',
        '🌟' => 'Glowing Star'
    );

    // Handle form submission
    if (isset($_POST['save_prizes']) && check_admin_referer('userinfo_save_prizes', 'userinfo_prizes_nonce')) {
        $prizes_list = array();

        // Process dynamic prizes
        if (isset($_POST['prize_title']) && is_array($_POST['prize_title'])) {
            foreach ($_POST['prize_title'] as $index => $title) {
                // Skip empty prizes (from template or deleted items)
                $rank = sanitize_text_field($_POST['prize_rank'][$index]);
                $amount = sanitize_text_field($_POST['prize_amount'][$index]);

                // Only add prize if it has at least title OR rank OR amount
                if (!empty($title) || !empty($rank) || !empty($amount)) {
                    $prizes_list[] = array(
                        'title' => sanitize_text_field($title),
                        'rank' => $rank,
                        'icon' => sanitize_text_field($_POST['prize_icon'][$index]),
                        'amount' => $amount,
                        'details' => sanitize_text_field($_POST['prize_details'][$index]),
                        'color' => sanitize_text_field($_POST['prize_color'][$index])
                    );
                }
            }
        }

        $prize_data = array(
            'prizes' => $prizes_list,
            'modal_title' => sanitize_text_field($_POST['modal_title']),
            'important_note' => sanitize_textarea_field($_POST['important_note'])
        );

        update_option('userinfo_prize_data', $prize_data);
        echo '<div class="notice notice-success"><p>' . __('Prize data saved successfully!', 'userinfo-manager') . '</p></div>';
    }

    // Get existing data or set defaults
    $prize_data = get_option('userinfo_prize_data', array(
        'prizes' => array(
            array(
                'title' => 'First Prize',
                'rank' => '১ম পুরস্কার',
                'icon' => '🥇',
                'amount' => '৳ ১,০০,০০০',
                'details' => 'স্বর্ণপদক + ট্রফি + সার্টিফিকেট',
                'color' => 'gold'
            ),
            array(
                'title' => 'Second Prize',
                'rank' => '২য় পুরস্কার',
                'icon' => '🥈',
                'amount' => '৳ ৫০,০০০',
                'details' => 'রৌপ্যপদক + ট্রফি + সার্টিফিকেট',
                'color' => 'silver'
            ),
            array(
                'title' => 'Third Prize',
                'rank' => '৩য় পুরস্কার',
                'icon' => '🥉',
                'amount' => '৳ ২৫,০০০',
                'details' => 'ব্রোঞ্জপদক + ট্রফি + সার্টিফিকেট',
                'color' => 'bronze'
            ),
            array(
                'title' => '4th - 10th Prize',
                'rank' => '৪র্থ - ১০ম পুরস্কার',
                'icon' => '🎁',
                'amount' => '৳ ১০,০০০',
                'details' => 'সার্টিফিকেট + উপহার',
                'color' => 'standard'
            ),
            array(
                'title' => 'Consolation Prize (11-20)',
                'rank' => 'সান্ত্বনা পুরস্কার (১১-২০)',
                'icon' => '🎖️',
                'amount' => '৳ ৫,০০০',
                'details' => 'সার্টিফিকেট',
                'color' => 'consolation'
            )
        ),
        'modal_title' => '🏆 পুরস্কারের তালিকা',
        'important_note' => 'সকল পুরস্কার বিজয়ীদের নাম প্রতি মাসে ঘোষণা করা হবে। পুরস্কার প্রদান কার্যক্রম ১৫ দিনের মধ্যে সম্পন্ন করা হবে।'
    ));

    // Ensure prizes array exists
    if (!isset($prize_data['prizes']) || !is_array($prize_data['prizes'])) {
        $prize_data['prizes'] = array();
    }

    ?>
    <div class="wrap">
        <h1><?php _e('Prize Management', 'userinfo-manager'); ?></h1>
        <p><?php _e('Manage the prize list that appears in the frontend modal popup.', 'userinfo-manager'); ?></p>

        <form method="post" action="" id="prize-management-form">
            <?php wp_nonce_field('userinfo_save_prizes', 'userinfo_prizes_nonce'); ?>

            <div class="prize-management-container">
                <!-- Modal Settings -->
                <table class="form-table widefat">
                    <thead>
                        <tr>
                            <th colspan="2">
                                <h2><?php _e('Modal Settings', 'userinfo-manager'); ?></h2>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><label for="modal_title"><?php _e('Modal Title', 'userinfo-manager'); ?></label></th>
                            <td>
                                <input type="text" id="modal_title" name="modal_title" value="<?php echo esc_attr($prize_data['modal_title']); ?>" class="regular-text" />
                                <p class="description"><?php _e('Title displayed at the top of the modal (emoji + text)', 'userinfo-manager'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="important_note"><?php _e('Important Note', 'userinfo-manager'); ?></label></th>
                            <td>
                                <textarea id="important_note" name="important_note" rows="3" class="large-text"><?php echo esc_textarea($prize_data['important_note']); ?></textarea>
                                <p class="description"><?php _e('Important notice displayed at the bottom of the modal', 'userinfo-manager'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Dynamic Prizes Section -->
                <div style="margin: 30px 0;">
                    <h2><?php _e('Prize List', 'userinfo-manager'); ?></h2>
                    <p class="description"><?php _e('Add, edit, or remove prizes. You can add unlimited prizes.', 'userinfo-manager'); ?></p>
                </div>

                <div id="prizes-container"><?php
                    // Display existing prizes
                    foreach ($prize_data['prizes'] as $index => $prize) {
                        $color_bg = '';
                        switch($prize['color']) {
                            case 'gold': $color_bg = '#fff9e6'; break;
                            case 'silver': $color_bg = '#f5f5f5'; break;
                            case 'bronze': $color_bg = '#fff4e6'; break;
                            case 'standard': $color_bg = '#f0f4ff'; break;
                            case 'consolation': $color_bg = '#f0fff4'; break;
                            default: $color_bg = '#ffffff';
                        }
                        ?>
                        <div class="prize-item" data-index="<?php echo $index; ?>">
                            <table class="form-table widefat">
                                <thead>
                                    <tr style="background: <?php echo esc_attr($color_bg); ?>;">
                                        <th colspan="2">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <span><input type="text" name="prize_title[]" value="<?php echo esc_attr($prize['title']); ?>" class="regular-text prize-title-input" placeholder="Prize Title" /></span>
                                                <button type="button" class="button delete-prize" style="background: #dc3232; color: white; border-color: #dc3232;"><?php _e('Delete Prize', 'userinfo-manager'); ?></button>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row"><label><?php _e('Rank/Title (Display)', 'userinfo-manager'); ?></label></th>
                                        <td>
                                            <input type="text" name="prize_rank[]" value="<?php echo esc_attr($prize['rank']); ?>" class="regular-text" placeholder="১ম পুরস্কার" />
                                            <input type="hidden" name="prize_icon[]" value="<?php echo esc_attr($prize['icon']); ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e('Prize Amount', 'userinfo-manager'); ?></label></th>
                                        <td><input type="text" name="prize_amount[]" value="<?php echo esc_attr($prize['amount']); ?>" class="regular-text" placeholder="৳ ১,০০,০০০" /></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e('Prize Details', 'userinfo-manager'); ?></label></th>
                                        <td><input type="text" name="prize_details[]" value="<?php echo esc_attr($prize['details']); ?>" class="large-text" placeholder="স্বর্ণপদক + ট্রফি + সার্টিফিকেট" /></td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label><?php _e('Color Theme', 'userinfo-manager'); ?></label></th>
                                        <td>
                                            <select name="prize_color[]" class="prize-color-select">
                                                <option value="gold" <?php selected($prize['color'], 'gold'); ?>>Gold</option>
                                                <option value="silver" <?php selected($prize['color'], 'silver'); ?>>Silver</option>
                                                <option value="bronze" <?php selected($prize['color'], 'bronze'); ?>>Bronze</option>
                                                <option value="standard" <?php selected($prize['color'], 'standard'); ?>>Standard (Blue)</option>
                                                <option value="consolation" <?php selected($prize['color'], 'consolation'); ?>>Consolation (Green)</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>

                <p style="margin: 20px 0;">
                    <button type="button" id="add-prize-btn" class="button button-secondary button-large">
                        <span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span> <?php _e('Add More Prize', 'userinfo-manager'); ?>
                    </button>
                </p>

                <!-- Prize Template (Hidden) -->
                <div id="prize-template" style="display: none;">
                    <div class="prize-item">
                        <table class="form-table widefat">
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span><span class="template-icon">🎁</span> <input type="text" name="prize_title[]" value="" class="regular-text prize-title-input" placeholder="Prize Title" style="margin-left: 10px;" /></span>
                                            <button type="button" class="button delete-prize" style="background: #dc3232; color: white; border-color: #dc3232;"><?php _e('Delete Prize', 'userinfo-manager'); ?></button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row"><label><?php _e('Rank/Title (Display)', 'userinfo-manager'); ?></label></th>
                                    <td><input type="text" name="prize_rank[]" value="" class="regular-text" placeholder="১ম পুরস্কার" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label><?php _e('Icon', 'userinfo-manager'); ?></label></th>
                                    <td>
                                        <select name="prize_icon[]" class="prize-icon-select">
                                            <?php foreach ($available_icons as $icon => $label): ?>
                                                <option value="<?php echo esc_attr($icon); ?>"><?php echo esc_html($icon . ' - ' . $label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label><?php _e('Prize Amount', 'userinfo-manager'); ?></label></th>
                                    <td><input type="text" name="prize_amount[]" value="" class="regular-text" placeholder="৳ ১,০০,০০০" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label><?php _e('Prize Details', 'userinfo-manager'); ?></label></th>
                                    <td><input type="text" name="prize_details[]" value="" class="large-text" placeholder="স্বর্ণপদক + ট্রফি + সার্টিফিকেট" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label><?php _e('Color Theme', 'userinfo-manager'); ?></label></th>
                                    <td>
                                        <select name="prize_color[]" class="prize-color-select">
                                            <option value="gold">Gold</option>
                                            <option value="silver">Silver</option>
                                            <option value="bronze">Bronze</option>
                                            <option value="standard" selected>Standard (Blue)</option>
                                            <option value="consolation">Consolation (Green)</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <p class="submit">
                <input type="submit" name="save_prizes" class="button button-primary button-large" value="<?php _e('Save Prize Data', 'userinfo-manager'); ?>" />
            </p>
        </form>

        <style>
            .prize-management-container {
                margin-top: 20px;
            }
            .prize-management-container table {
                margin-bottom: 30px;
                background: white;
                border: 1px solid #ccc;
            }
            .prize-management-container thead th {
                padding: 15px;
            }
            .prize-management-container tbody th {
                width: 200px;
                font-weight: 600;
            }
            .prize-item {
                margin-bottom: 25px;
                border: 2px solid #ddd;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            .prize-item:hover {
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .prize-title-input {
                font-weight: bold;
                font-size: 16px;
            }
            .prize-icon-select {
                font-size: 18px;
                padding: 5px 10px;
            }
            .delete-prize:hover {
                background: #a00 !important;
                border-color: #a00 !important;
            }
            #add-prize-btn {
                font-size: 16px;
                padding: 10px 20px;
            }
            #prizes-container:empty::after {
                content: "No prizes added yet. Click 'Add More Prize' button to add prizes.";
                display: block;
                padding: 30px;
                text-align: center;
                color: #666;
                font-style: italic;
                background: #f9f9f9;
                border: 1px dashed #ccc;
                border-radius: 5px;
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Color scheme background mapping
            var colorBgs = {
                'gold': '#fff9e6',
                'silver': '#f5f5f5',
                'bronze': '#fff4e6',
                'standard': '#f0f4ff',
                'consolation': '#f0fff4'
            };

            // Function to update header background based on color selection
            function updateHeaderColor(prizeItem) {
                var selectedColor = prizeItem.find('.prize-color-select').val();
                var bgColor = colorBgs[selectedColor] || '#ffffff';
                prizeItem.find('thead tr').css('background', bgColor);
            }

            // Function to update icon display in header
            function updateHeaderIcon(prizeItem) {
                var selectedIcon = prizeItem.find('.prize-icon-select').val();
                prizeItem.find('.template-icon, thead th span:first').html(selectedIcon);
            }

            // Add prize button
            $('#add-prize-btn').on('click', function() {
                var template = $('#prize-template .prize-item').clone();
                var index = $('#prizes-container .prize-item').length;

                template.attr('data-index', index);
                $('#prizes-container').append(template);

                // Initialize color for new prize
                updateHeaderColor(template);
                updateHeaderIcon(template);
            });

            // Delete prize button (using event delegation)
            $('#prizes-container').on('click', '.delete-prize', function() {
                if (confirm('<?php _e('Are you sure you want to delete this prize?', 'userinfo-manager'); ?>')) {
                    $(this).closest('.prize-item').fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });

            // Color change event (using event delegation)
            $('#prizes-container').on('change', '.prize-color-select', function() {
                var prizeItem = $(this).closest('.prize-item');
                updateHeaderColor(prizeItem);
            });

            // Icon change event (using event delegation)
            $('#prizes-container').on('change', '.prize-icon-select', function() {
                var prizeItem = $(this).closest('.prize-item');
                updateHeaderIcon(prizeItem);
            });

            // Initialize existing prizes
            $('#prizes-container .prize-item').each(function() {
                updateHeaderColor($(this));
            });
        });
        </script>
    </div>
    <?php
}

/**
 * Enqueue frontend styles and scripts
 * Version: 1.5.3 - Complete browse button removal
 */
function userinfo_enqueue_frontend_assets() {
    $plugin_version = '1.5.3';

    // Enqueue CSS with high priority to ensure it loads
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
        'registration_id' => __('লটারি নাম্বার', 'userinfo-manager'),
        'agent_id' => __('Agent ID', 'userinfo-manager'),
        'phone' => __('Phone', 'userinfo-manager'),
        'email' => __('Email', 'userinfo-manager'),
        'status' => __('Status', 'userinfo-manager'),
        'error_occurred' => __('An error occurred. Please try again.', 'userinfo-manager'),
        'submitting' => __('Submitting...', 'userinfo-manager'),
        'success_title' => __('সফল হয়েছে!', 'userinfo-manager'),
        'error_title' => __('Error', 'userinfo-manager'),
        'save_id_message' => __('অভিনন্দন! আপনার রেজিস্ট্রেশন সম্পন্ন হয়েছে। অনুগ্রহ করে এই লটারি নাম্বার ভবিষ্যতের জন্য সংরক্ষণ করুন।', 'userinfo-manager'),
    ));
}
add_action('wp_enqueue_scripts', 'userinfo_enqueue_frontend_assets', 10);

/**
 * Add enctype to post form for file uploads
 */
function userinfo_add_enctype_to_post_form() {
    global $post;
    if ($post && $post->post_type == 'userinfo') {
        echo ' enctype="multipart/form-data"';
    }
}
add_action('post_edit_form_tag', 'userinfo_add_enctype_to_post_form');

/**
 * Add custom meta boxes for name and email
 */
function userinfo_add_meta_boxes() {
    add_meta_box(
        'userinfo_details',
        __('User Details', 'userinfo-manager'),
        'userinfo_meta_box_callback',
        'userinfo',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'userinfo_add_meta_boxes');

/**
 * Add meta boxes for Result Generate post type
 */
function result_generate_add_meta_boxes() {
    add_meta_box(
        'result_generate_daterange',
        __('Result Date Range', 'userinfo-manager'),
        'result_generate_daterange_callback',
        'result_generate',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'result_generate_add_meta_boxes');

/**
 * Date Range meta box display callback
 */
function result_generate_daterange_callback($post) {
    // Add nonce for security
    wp_nonce_field('result_generate_save_meta_box_data', 'result_generate_meta_box_nonce');

    // Retrieve existing values
    $start_date = get_post_meta($post->ID, '_result_start_date', true);
    $end_date = get_post_meta($post->ID, '_result_end_date', true);

    ?>
    <table class="form-table">
        <tr>
            <th><label for="result_start_date"><?php _e('Start Date', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="date" id="result_start_date" name="result_start_date" value="<?php echo esc_attr($start_date); ?>" class="regular-text" required />
                <p class="description"><?php _e('Select the start date for this result period', 'userinfo-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="result_end_date"><?php _e('End Date', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="date" id="result_end_date" name="result_end_date" value="<?php echo esc_attr($end_date); ?>" class="regular-text" required />
                <p class="description"><?php _e('Select the end date for this result period', 'userinfo-manager'); ?></p>
            </td>
        </tr>
    </table>
    <style>
        input[type="date"] {
            padding: 6px 10px;
            font-size: 14px;
            line-height: 1.5;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="date"]:focus {
            border-color: #2271b1;
            outline: none;
            box-shadow: 0 0 0 1px #2271b1;
        }
    </style>
    <?php
}

/**
 * Save Result Generate meta box data
 */
function result_generate_save_meta_box_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['result_generate_meta_box_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['result_generate_meta_box_nonce'], 'result_generate_save_meta_box_data')) {
        return;
    }

    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save start date
    if (isset($_POST['result_start_date'])) {
        update_post_meta($post_id, '_result_start_date', sanitize_text_field($_POST['result_start_date']));
    }

    // Save end date
    if (isset($_POST['result_end_date'])) {
        update_post_meta($post_id, '_result_end_date', sanitize_text_field($_POST['result_end_date']));
    }
}
add_action('save_post_result_generate', 'result_generate_save_meta_box_data');

/**
 * Add custom columns to Result Generate list view
 */
function result_generate_custom_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => __('Result Title', 'userinfo-manager'),
        'date_range' => __('Date Range', 'userinfo-manager'),
        'winner_count' => __('Winners', 'userinfo-manager'),
        'date' => $columns['date']
    );
    return $new_columns;
}
add_filter('manage_result_generate_posts_columns', 'result_generate_custom_columns');

/**
 * Populate custom columns for Result Generate
 */
function result_generate_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'date_range':
            $start_date = get_post_meta($post_id, '_result_start_date', true);
            $end_date = get_post_meta($post_id, '_result_end_date', true);

            if ($start_date && $end_date) {
                $start_formatted = date('d M Y', strtotime($start_date));
                $end_formatted = date('d M Y', strtotime($end_date));
                echo '<strong>' . esc_html($start_formatted) . '</strong>';
                echo '<br><span style="color: #666;">to</span><br>';
                echo '<strong>' . esc_html($end_formatted) . '</strong>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;

        case 'winner_count':
            $start_date = get_post_meta($post_id, '_result_start_date', true);
            $end_date = get_post_meta($post_id, '_result_end_date', true);

            if ($start_date && $end_date) {
                // Query shortlisted users within this date range
                $args = array(
                    'post_type' => 'userinfo',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => '_userinfo_shortlisted',
                            'value' => '1',
                            'compare' => '='
                        ),
                        array(
                            'key' => '_userinfo_submitted_date',
                            'value' => array($start_date, $end_date),
                            'compare' => 'BETWEEN',
                            'type' => 'DATE'
                        )
                    ),
                );

                $query = new WP_Query($args);
                $count = $query->found_posts;

                if ($count > 0) {
                    echo '<span style="background: #46b450; color: white; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 13px;">';
                    echo esc_html($count) . ' ' . _n('Winner', 'Winners', $count, 'userinfo-manager');
                    echo '</span>';
                } else {
                    echo '<span style="color: #999;">0 Winners</span>';
                }

                wp_reset_postdata();
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
    }
}
add_action('manage_result_generate_posts_custom_column', 'result_generate_custom_column_content', 10, 2);

/**
 * Make date range column sortable
 */
function result_generate_sortable_columns($columns) {
    $columns['date_range'] = 'result_start_date';
    return $columns;
}
add_filter('manage_edit-result_generate_sortable_columns', 'result_generate_sortable_columns');

/**
 * Handle custom column sorting
 */
function result_generate_column_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') !== 'result_generate') {
        return;
    }

    $orderby = $query->get('orderby');

    if ('result_start_date' === $orderby) {
        $query->set('meta_key', '_result_start_date');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'result_generate_column_orderby');

/**
 * Meta box display callback
 */
function userinfo_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('userinfo_save_meta_box_data', 'userinfo_meta_box_nonce');

    // Retrieve existing values
    $full_name = get_post_meta($post->ID, '_userinfo_full_name', true);
    $username = get_post_meta($post->ID, '_userinfo_username', true);
    $registration_id = get_post_meta($post->ID, '_userinfo_registration_id', true);
    $agent_id = get_post_meta($post->ID, '_userinfo_agent_id', true);
    $phone_number = get_post_meta($post->ID, '_userinfo_phone_number', true);
    $submitted_date = get_post_meta($post->ID, '_userinfo_submitted_date', true);
    $is_valid = get_post_meta($post->ID, '_userinfo_is_valid', true);
    $position = get_post_meta($post->ID, '_userinfo_position', true);
    $prize = get_post_meta($post->ID, '_userinfo_prize', true);

    // Default to valid (1) if not set
    if ($is_valid === '') {
        $is_valid = '1';
    }

    ?>
    <table class="form-table">
        <tr>
            <th><label for="userinfo_full_name"><?php _e('Full Name', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="text" id="userinfo_full_name" name="userinfo_full_name" value="<?php echo esc_attr($full_name); ?>" class="regular-text" required />
            </td>
        </tr>
        <tr>
            <th><label for="userinfo_username"><?php _e('Username', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="text" id="userinfo_username" name="userinfo_username" value="<?php echo esc_attr($username); ?>" class="regular-text" required />
            </td>
        </tr>
        <tr>
            <th><label for="userinfo_registration_id"><?php _e('Registration ID', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="text" id="userinfo_registration_id" name="userinfo_registration_id" value="<?php echo esc_attr($registration_id); ?>" class="regular-text" readonly style="background-color: #f0f0f0;" />
                <p class="description"><?php _e('Auto-generated on registration (MM + Serial Number). Example: 1101, 1102. Read-only.', 'userinfo-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="userinfo_agent_id"><?php _e('Agent ID', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="text" id="userinfo_agent_id" name="userinfo_agent_id" value="<?php echo esc_attr($agent_id); ?>" class="regular-text" required />
            </td>
        </tr>
        <tr>
            <th><label for="userinfo_phone_number"><?php _e('Phone Number', 'userinfo-manager'); ?></label></th>
            <td>
                <input
                    type="text"
                    id="userinfo_phone_number"
                    name="userinfo_phone_number"
                    value="<?php echo esc_attr($phone_number); ?>"
                    class="regular-text"
                    required
                    pattern="[0-9]{10,50}"
                    minlength="10"
                    maxlength="50"
                    title="<?php esc_attr_e('Phone number must be 10-50 digits only', 'userinfo-manager'); ?>"
                />
                <p class="description"><?php _e('Numbers only, 10-50 characters', 'userinfo-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label><?php _e('Valid Member', 'userinfo-manager'); ?></label></th>
            <td>
                <div class="userinfo-details-toggle">
                    <label class="userinfo-toggle-switch" data-post-id="<?php echo esc_attr($post->ID); ?>">
                        <input type="checkbox" <?php checked($is_valid, '1'); ?>>
                        <span class="userinfo-toggle-slider"></span>
                    </label>
                    <span class="userinfo-toggle-label <?php echo ($is_valid == '1') ? 'valid' : 'invalid'; ?>">
                        <?php echo ($is_valid == '1') ? __('Valid', 'userinfo-manager') : __('Invalid', 'userinfo-manager'); ?>
                    </span>
                    <p class="description" style="margin: 0 0 0 10px;">
                        <?php _e('Toggle to change member validity status', 'userinfo-manager'); ?>
                    </p>
                </div>
                <!-- Hidden field for form submission fallback -->
                <input type="hidden" id="userinfo_is_valid" name="userinfo_is_valid" value="<?php echo esc_attr($is_valid); ?>" />
            </td>
        </tr>
        <tr>
            <th><label><?php _e('Submitted Date', 'userinfo-manager'); ?></label></th>
            <td>
                <strong><?php echo $submitted_date ? esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submitted_date))) : __('N/A', 'userinfo-manager'); ?></strong>
            </td>
        </tr>
        <tr>
            <th colspan="2" style="background: #f0f0f0; padding: 10px; font-weight: bold; border-top: 2px solid #ddd;">
                <?php _e('Shortlist Information', 'userinfo-manager'); ?>
            </th>
        </tr>
        <tr>
            <th><label for="userinfo_position"><?php _e('Position', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="text" id="userinfo_position" name="userinfo_position" value="<?php echo esc_attr($position); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., 1st, 2nd, 3rd, Winner, Runner-up', 'userinfo-manager'); ?>" />
                <p class="description"><?php _e('Enter the position/rank for this shortlisted user (e.g., 1st, 2nd, Winner)', 'userinfo-manager'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="userinfo_prize"><?php _e('Prize', 'userinfo-manager'); ?></label></th>
            <td>
                <input type="text" id="userinfo_prize" name="userinfo_prize" value="<?php echo esc_attr($prize); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., $1000, Gold Medal, Trophy', 'userinfo-manager'); ?>" />
                <p class="description"><?php _e('Enter the prize/award for this shortlisted user', 'userinfo-manager'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data
 */
function userinfo_save_meta_box_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['userinfo_meta_box_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['userinfo_meta_box_nonce'], 'userinfo_save_meta_box_data')) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save full name field
    if (isset($_POST['userinfo_full_name'])) {
        $full_name = sanitize_text_field($_POST['userinfo_full_name']);
        update_post_meta($post_id, '_userinfo_full_name', $full_name);
    }

    // Save username field
    if (isset($_POST['userinfo_username'])) {
        $username = sanitize_text_field($_POST['userinfo_username']);
        update_post_meta($post_id, '_userinfo_username', $username);
    }

    // Save agent ID field
    if (isset($_POST['userinfo_agent_id'])) {
        $agent_id = sanitize_text_field($_POST['userinfo_agent_id']);
        update_post_meta($post_id, '_userinfo_agent_id', $agent_id);
    }

    // Save phone number field with validation
    if (isset($_POST['userinfo_phone_number'])) {
        $phone_number = sanitize_text_field($_POST['userinfo_phone_number']);

        // Validate phone number: must be numeric and 10-50 characters
        if (!empty($phone_number) && (!ctype_digit($phone_number) || strlen($phone_number) < 10 || strlen($phone_number) > 50)) {
            add_settings_error(
                'userinfo_messages',
                'userinfo_message',
                __('Phone number must be 10-50 digits only.', 'userinfo-manager'),
                'error'
            );
        } else {
            update_post_meta($post_id, '_userinfo_phone_number', $phone_number);
        }
    }

    // Save valid member toggle (checkbox)
    // Checkboxes are only sent when checked, so we need to handle both cases
    $is_valid = isset($_POST['userinfo_is_valid']) ? '1' : '0';
    update_post_meta($post_id, '_userinfo_is_valid', $is_valid);

    // Save position field
    if (isset($_POST['userinfo_position'])) {
        $position = sanitize_text_field($_POST['userinfo_position']);
        update_post_meta($post_id, '_userinfo_position', $position);
    }

    // Save prize field
    if (isset($_POST['userinfo_prize'])) {
        $prize = sanitize_text_field($_POST['userinfo_prize']);
        update_post_meta($post_id, '_userinfo_prize', $prize);
    }
}

/**
 * Display admin notices for image upload
 */
function userinfo_display_admin_notices() {
    global $post;

    if ($post && $post->post_type == 'userinfo') {
        // Display transient notices (for image upload success/errors)
        $notice = get_transient('userinfo_admin_notice_' . $post->ID);

        if ($notice && is_array($notice)) {
            $class = $notice['type'] == 'success' ? 'notice-success' : 'notice-error';
            ?>
            <div class="notice <?php echo esc_attr($class); ?> is-dismissible">
                <p><?php echo esc_html($notice['message']); ?></p>
            </div>
            <?php

            // Delete transient after displaying
            delete_transient('userinfo_admin_notice_' . $post->ID);
        }

        // Display settings errors (for validation errors)
        settings_errors('userinfo_messages');
    }
}
add_action('admin_notices', 'userinfo_display_admin_notices');
add_action('save_post', 'userinfo_save_meta_box_data');

/**
 * Frontend form handler
 */
function userinfo_handle_form_submission() {
    // Check if AJAX or regular form submission
    $is_ajax = (defined('DOING_AJAX') && DOING_AJAX) || wp_doing_ajax();

    // For AJAX, we only need the nonce, for regular form we need submit button
    if (!$is_ajax && !isset($_POST['userinfo_submit'])) {
        return;
    }

    // Check if nonce exists
    if (!isset($_POST['userinfo_nonce'])) {
        if ($is_ajax) {
            wp_send_json_error(array('message' => __('Security check failed. Please try again.', 'userinfo-manager')));
        }
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['userinfo_nonce'], 'userinfo_form_submit')) {
        if ($is_ajax) {
            wp_send_json_error(array('message' => __('Security check failed. Please try again.', 'userinfo-manager')));
        } else {
            wp_die(__('Security check failed. Please try again.', 'userinfo-manager'));
        }
    }

    // Validate and sanitize inputs
    $errors = array();

    $full_name = isset($_POST['userinfo_full_name']) ? sanitize_text_field($_POST['userinfo_full_name']) : '';
    $username = isset($_POST['userinfo_username']) ? sanitize_text_field($_POST['userinfo_username']) : '';
    $agent_id = isset($_POST['userinfo_agent_id']) ? sanitize_text_field($_POST['userinfo_agent_id']) : '';
    $phone_number = isset($_POST['userinfo_phone_number']) ? sanitize_text_field($_POST['userinfo_phone_number']) : '';
    $email = isset($_POST['userinfo_email']) ? sanitize_email($_POST['userinfo_email']) : '';

    // Validation
    if (empty($full_name)) {
        $errors[] = __('Full Name is required.', 'userinfo-manager');
    }

    if (empty($username)) {
        $errors[] = __('Username is required.', 'userinfo-manager');
    }

    if (empty($agent_id)) {
        $errors[] = __('Agent ID is required.', 'userinfo-manager');
    }

    if (empty($phone_number)) {
        $errors[] = __('Phone Number is required.', 'userinfo-manager');
    } elseif (!ctype_digit($phone_number) || strlen($phone_number) < 10 || strlen($phone_number) > 50) {
        $errors[] = __('Phone Number must be 10-50 digits only.', 'userinfo-manager');
    }

    if (empty($email)) {
        $errors[] = __('Email Address is required.', 'userinfo-manager');
    } elseif (!is_email($email)) {
        $errors[] = __('Please enter a valid email address.', 'userinfo-manager');
    }

    // Check if phone number or username already submitted in current period
    // Period depends on countdown mode: monthly or custom date
    $countdown_enabled = get_option('userinfo_countdown_enabled', 0);
    $custom_countdown_enabled = get_option('userinfo_custom_countdown_enabled', 0);
    $custom_countdown_date = get_option('userinfo_custom_countdown_date', '');

    // Determine period start and end based on countdown mode
    if ($custom_countdown_enabled && !empty($custom_countdown_date)) {
        // Custom countdown mode - period is from last deadline to current deadline
        $period_end = date('Y-m-d 23:59:59', strtotime($custom_countdown_date));

        // Calculate period start (assuming monthly intervals, adjust as needed)
        // For now, using the first day of the month containing the custom date
        $custom_date_obj = new DateTime($custom_countdown_date);
        $period_start = $custom_date_obj->format('Y-m-01 00:00:00');

        $period_type = 'custom';
        $blocked_until_date = date('F j, Y', strtotime($custom_countdown_date));
        $blocked_until_date_bangla = date('j F, Y', strtotime($custom_countdown_date));
    } else {
        // Monthly countdown mode or no countdown - period is current calendar month
        $period_start = date('Y-m-01 00:00:00');
        $period_end = date('Y-m-t 23:59:59');

        $period_type = 'monthly';
        $last_day = date('t'); // Last day of current month
        $blocked_until_date = date('F') . ' ' . $last_day . ', ' . date('Y');
        $blocked_until_date_bangla = $last_day . ' ' . date('F') . ', ' . date('Y');
    }

    $existing_submission = new WP_Query(array(
        'post_type' => 'userinfo',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_userinfo_phone_number',
                'value' => $phone_number,
                'compare' => '='
            ),
            array(
                'key' => '_userinfo_username',
                'value' => $username,
                'compare' => '='
            )
        ),
        'date_query' => array(
            array(
                'after' => $period_start,
                'before' => $period_end,
                'inclusive' => true
            )
        ),
        'posts_per_page' => 1
    ));

    if ($existing_submission->have_posts()) {
        // Get the submission date to show in error message
        $existing_post = $existing_submission->posts[0];
        $submission_date = date('F j, Y', strtotime($existing_post->post_date));

        // Create error message with specific blocked date
        if ($period_type === 'custom') {
            $errors[] = sprintf(
                __('You have already submitted on %s. Each phone number or username can only submit once per period. You can submit again after %s.', 'userinfo-manager'),
                $submission_date,
                $blocked_until_date
            );
        } else {
            $errors[] = sprintf(
                __('You have already submitted on %s. Each phone number or username can only submit once per month. You can submit again after %s.', 'userinfo-manager'),
                $submission_date,
                $blocked_until_date
            );
        }

        wp_reset_postdata();
    }

    // If there are errors, return them
    if (!empty($errors)) {
        if ($is_ajax) {
            wp_send_json_error(array(
                'message' => implode(' ', $errors),
                'errors' => $errors
            ));
        } else {
            set_transient('userinfo_errors_' . session_id(), $errors, 45);
            wp_safe_redirect(wp_get_referer());
            exit;
        }
    }

    // Create new UserInfo post
    $post_data = array(
        'post_title'    => $full_name . ' - ' . $agent_id,
        'post_type'     => 'userinfo',
        'post_status'   => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if ($post_id && !is_wp_error($post_id)) {
        // Generate unique sequential Registration ID starting from 9000
        // Uses atomic database operation to prevent duplicates
        $registration_id = userinfo_get_next_registration_id();

        // Save meta data
        update_post_meta($post_id, '_userinfo_full_name', $full_name);
        update_post_meta($post_id, '_userinfo_username', $username);
        update_post_meta($post_id, '_userinfo_agent_id', $agent_id);
        update_post_meta($post_id, '_userinfo_registration_id', $registration_id);
        update_post_meta($post_id, '_userinfo_phone_number', $phone_number);
        update_post_meta($post_id, '_userinfo_email', $email);
        update_post_meta($post_id, '_userinfo_submitted_date', current_time('mysql'));
        update_post_meta($post_id, '_userinfo_is_valid', '1'); // Default to valid

        // Return success response
        if ($is_ajax) {
            wp_send_json_success(array(
                'message' => __('Your information has been submitted successfully!', 'userinfo-manager'),
                'registration_id' => $registration_id
            ));
        } else {
            // Set success message with registration ID
            set_transient('userinfo_success_' . session_id(), array(
                'message' => __('Your information has been submitted successfully!', 'userinfo-manager'),
                'registration_id' => $registration_id
            ), 45);

            // Redirect back
            wp_safe_redirect(wp_get_referer());
            exit;
        }
    } else {
        // Return error response
        if ($is_ajax) {
            wp_send_json_error(array(
                'message' => __('Failed to submit information. Please try again.', 'userinfo-manager')
            ));
        } else {
            set_transient('userinfo_errors_' . session_id(), array(__('Failed to submit information. Please try again.', 'userinfo-manager')), 45);
            wp_safe_redirect(wp_get_referer());
            exit;
        }
    }
}
add_action('admin_post_nopriv_userinfo_submit', 'userinfo_handle_form_submission');
add_action('admin_post_userinfo_submit', 'userinfo_handle_form_submission');

/**
 * AJAX handler for form submission
 */
add_action('wp_ajax_nopriv_userinfo_ajax_submit', 'userinfo_handle_form_submission');
add_action('wp_ajax_userinfo_ajax_submit', 'userinfo_handle_form_submission');

/**
 * Initialize session for messages
 */
function userinfo_init_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'userinfo_init_session');

/**
 * Mask middle 4 digits of phone number with asterisks
 * Example: 01712345678 becomes 0171****678
 */
function userinfo_mask_phone_number($phone) {
    if (empty($phone)) {
        return '';
    }

    $phone = trim($phone);
    $length = strlen($phone);

    // If phone is too short to mask, return as is
    if ($length < 8) {
        return $phone;
    }

    // Calculate positions for masking middle 4 digits
    $start_visible = floor(($length - 4) / 2);
    $end_visible = $length - $start_visible - 4;

    // Create masked version: first part + **** + last part
    $masked = substr($phone, 0, $start_visible) . '****' . substr($phone, -$end_visible);

    return $masked;
}

/**
 * Frontend form shortcode
 */
function userinfo_form_shortcode($atts) {
    ob_start();

    // Get messages from transients
    $errors = get_transient('userinfo_errors_' . session_id());
    $success = get_transient('userinfo_success_' . session_id());

    // Delete transients after retrieving
    if ($errors) {
        delete_transient('userinfo_errors_' . session_id());
    }
    if ($success) {
        delete_transient('userinfo_success_' . session_id());
    }

    // Get dynamic title and welcome message from settings
    $registration_title = get_option('userinfo_registration_title');
    $welcome_message = get_option('userinfo_welcome_message');
    $countdown_enabled = get_option('userinfo_countdown_enabled', 0);
    $custom_countdown_enabled = get_option('userinfo_custom_countdown_enabled', 0);
    $custom_countdown_date = get_option('userinfo_custom_countdown_date', '');

    // Set defaults if not found in database
    if (empty($registration_title)) {
        $registration_title = 'লটারি রেজিস্ট্রেশন';
    }
    if (empty($welcome_message)) {
        $welcome_message = 'আমাদের প্ল্যাটফর্মে আপনাকে স্বাগতম। অনুগ্রহ করে নিচের ফর্মটি পূরণ করে আপনার লটারি রেজিস্ট্রেশন সম্পন্ন করুন। আপনার সকল তথ্য সম্পূর্ণ নিরাপদ এবং গোপনীয় থাকবে।';
    }

    // Check if ANY countdown is enabled (monthly OR custom)
    $countdown_active = ($countdown_enabled || ($custom_countdown_enabled && !empty($custom_countdown_date)));

    // Check if countdown has expired
    $form_blocked = false;

    if (!$countdown_active) {
        // No countdown enabled - block the form completely
        $form_blocked = true;
    } elseif ($countdown_enabled) {
        // Monthly countdown - check if month ended
        $current_date = current_time('j');
        $last_day_of_month = date('t', current_time('timestamp'));
        $is_month_ended = ($current_date > $last_day_of_month);
        $form_blocked = $is_month_ended;
    } elseif ($custom_countdown_enabled && !empty($custom_countdown_date)) {
        // Custom countdown - check if date has passed
        $selected_timestamp = strtotime($custom_countdown_date . ' 23:59:59');
        $current_timestamp = current_time('timestamp');
        $form_blocked = ($current_timestamp > $selected_timestamp);
    }

    // Determine which countdown is active
    $show_countdown = $countdown_active;

    ?>
    <!-- Title and Countdown Wrapper -->
    <div class="userinfo-title-countdown-wrapper">
        <!-- Dynamic Title -->
        <div class="userinfo-registration-title" style="margin-top: 0; padding-top: 0;">
            <h2 style="color: #2c3e50; font-size: 28px; font-weight: bold; text-align: center; margin: 0; padding-top: 0; text-transform: none;">
                <?php echo esc_html($registration_title); ?>
            </h2>
        </div>

        <?php if ($show_countdown && !$form_blocked): ?>
        <!-- Single Countdown with Grouped Components -->
        <div class="userinfo-countdown-header"
             id="userinfo-countdown-inline"
             data-countdown-type="<?php echo $countdown_enabled ? 'monthly' : 'custom'; ?>"
             data-custom-date="<?php echo esc_attr($custom_countdown_date); ?>">
            <span class="countdown-text">সময় বাকি</span>
            <div class="countdown-timer-row">
                <!-- Days Group -->
                <div class="countdown-group" id="countdown-group-days">
                    <span class="countdown-value" id="countdown-days">00</span>
                    <span class="countdown-label">দিন</span>
                </div>
                <span class="countdown-separator" id="separator-1">:</span>
                <!-- Hours Group -->
                <div class="countdown-group" id="countdown-group-hours">
                    <span class="countdown-value" id="countdown-hours">00</span>
                    <span class="countdown-label">ঘণ্টা</span>
                </div>
                <span class="countdown-separator" id="separator-2">:</span>
                <!-- Minutes Group -->
                <div class="countdown-group" id="countdown-group-minutes">
                    <span class="countdown-value" id="countdown-minutes">00</span>
                    <span class="countdown-label">মিনিট</span>
                </div>
                <span class="countdown-separator" id="separator-3">:</span>
                <!-- Seconds Group -->
                <div class="countdown-group" id="countdown-group-seconds">
                    <span class="countdown-value" id="countdown-seconds">00</span>
                    <span class="countdown-label">সেকেন্ড</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Dynamic Welcome Message -->
    <div class="userinfo-welcome-message" style="margin-top: 0;">
        <p style="color: #34495e; font-size: 16px; line-height: 1.6; text-align: center; margin: 0 0 20px 0;">
            <?php echo esc_html($welcome_message); ?>
        </p>
    </div>


    <?php if ($form_blocked): ?>
    <!-- Registration Closed Message -->
    <div class="userinfo-form-closed">
        <div class="closed-icon">🔒</div>
        <?php if (!$countdown_active): ?>
            <!-- No countdown enabled message -->
            <h3 class="closed-title">দুঃখিত!!</h3>
            <p class="closed-message">
                দুঃখিত লটারি রেজিস্ট্রেশনের সময়সীমা শেষ
            </p>
        <?php else: ?>
            <!-- Countdown expired message -->
            <h3 class="closed-title">রেজিস্ট্রেশন বন্ধ</h3>
            <p class="closed-message">
                এই মাসের রেজিস্ট্রেশন সময় শেষ হয়ে গেছে।<br>
                পরবর্তী মাসে পুনরায় রেজিস্ট্রেশন খোলা হবে।<br>
                ধন্যবাদ।
            </p>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="userinfo-form-container">
        <?php if ($success): ?>
            <div class="userinfo-success">
                <?php echo esc_html($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($errors && is_array($errors)): ?>
            <div class="userinfo-errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo esc_html($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="userinfo-form" class="userinfo-form" enctype="multipart/form-data">
            <?php wp_nonce_field('userinfo_form_submit', 'userinfo_nonce'); ?>
            <input type="hidden" name="action" value="userinfo_submit">

            <div class="form-group">
                <label for="userinfo_full_name">
                    <?php _e('Full Name', 'userinfo-manager'); ?> <span style="color: red;">*</span>
                </label>
                <input
                    type="text"
                    id="userinfo_full_name"
                    name="userinfo_full_name"
                    placeholder="আপনার নাম লিখুন"
                    required
                    value="<?php echo isset($_POST['userinfo_full_name']) ? esc_attr($_POST['userinfo_full_name']) : ''; ?>"
                />
                <span class="field-error" data-error-for="userinfo_full_name"></span>
            </div>

            <div class="form-group">
                <label for="userinfo_username">
                    <?php _e('Username', 'userinfo-manager'); ?> <span style="color: red;">*</span>
                </label>
                <input
                    type="text"
                    id="userinfo_username"
                    name="userinfo_username"
                    placeholder="আপনার ইউজার নেম লিখুন"
                    required
                    value="<?php echo isset($_POST['userinfo_username']) ? esc_attr($_POST['userinfo_username']) : ''; ?>"
                />
                <span class="field-error" data-error-for="userinfo_username"></span>
            </div>

            <div class="form-group">
                <label for="userinfo_agent_id">
                    <?php _e('Agent ID or Name', 'userinfo-manager'); ?> <span style="color: red;">*</span>
                </label>
                <input
                    type="text"
                    id="userinfo_agent_id"
                    name="userinfo_agent_id"
                    placeholder="এজেন্ট আইডি অথবা নাম লিখুন"
                    required
                    value="<?php echo isset($_POST['userinfo_agent_id']) ? esc_attr($_POST['userinfo_agent_id']) : ''; ?>"
                />
                <span class="field-error" data-error-for="userinfo_agent_id"></span>
            </div>

            <div class="form-group">
                <label for="userinfo_phone_number">
                    <?php _e('Phone Number', 'userinfo-manager'); ?> <span style="color: red;">*</span>
                </label>
                <input
                    type="text"
                    id="userinfo_phone_number"
                    name="userinfo_phone_number"
                    placeholder="আপনার মোবাইল নাম্বার লিখুন"
                    required
                    pattern="[0-9]{10,50}"
                    minlength="10"
                    maxlength="50"
                    value="<?php echo isset($_POST['userinfo_phone_number']) ? esc_attr($_POST['userinfo_phone_number']) : ''; ?>"
                    title="<?php esc_attr_e('Phone number must be 10-50 digits only', 'userinfo-manager'); ?>"
                />
                <span class="field-error" data-error-for="userinfo_phone_number"></span>
            </div>

            <div class="form-group full-width">
                <label for="userinfo_email">
                    <?php _e('Email Address', 'userinfo-manager'); ?> <span style="color: red;">*</span>
                </label>
                <input
                    type="email"
                    id="userinfo_email"
                    name="userinfo_email"
                    placeholder="আপনার ইমেইল লিখুন"
                    required
                    value="<?php echo isset($_POST['userinfo_email']) ? esc_attr($_POST['userinfo_email']) : ''; ?>"
                />
                <span class="field-error" data-error-for="userinfo_email"></span>
            </div>

            <div class="form-group full-width">
                <button type="submit" name="userinfo_submit">
                    <span><?php _e('Submit', 'userinfo-manager'); ?></span>
                </button>
            </div>

            <?php
            // Get terms and conditions from settings
            $terms_conditions = get_option('userinfo_terms_conditions', array());
            if (!empty($terms_conditions)):
            ?>
            <div class="form-group full-width userinfo-terms-section">
                <h3 class="userinfo-terms-title"><?php _e('Terms and Conditions', 'userinfo-manager'); ?></h3>
                <div class="userinfo-terms-list">
                    <?php foreach ($terms_conditions as $index => $term): ?>
                        <?php if (!empty(trim($term))): ?>
                            <div class="userinfo-term-item">
                                <span class="term-number"><?php echo ($index + 1); ?>.</span>
                                <p class="term-text"><?php echo esc_html($term); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; // End form_blocked check ?>

    <?php
    return ob_get_clean();
}
add_shortcode('userinfo_form', 'userinfo_form_shortcode');

/**
 * Add custom columns to admin list
 */
function userinfo_custom_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => __('Full Name', 'userinfo-manager'),
        'username' => __('Username', 'userinfo-manager'),
        'registration_id' => __('Registration ID', 'userinfo-manager'),
        'agent_id' => __('Agent ID', 'userinfo-manager'),
        'phone_number' => __('Phone', 'userinfo-manager'),
        'email' => __('Email', 'userinfo-manager'),
        'is_valid' => __('Valid', 'userinfo-manager'),
        'shortlist' => __('Shortlist', 'userinfo-manager'),
        'submitted_date' => __('Submitted Date', 'userinfo-manager'),
        'date' => $columns['date']
    );
    return $new_columns;
}
add_filter('manage_userinfo_posts_columns', 'userinfo_custom_columns');

/**
 * Populate custom columns
 */
function userinfo_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'username':
            $username = get_post_meta($post_id, '_userinfo_username', true);
            echo esc_html($username ? $username : '—');
            break;
        case 'registration_id':
            $registration_id = get_post_meta($post_id, '_userinfo_registration_id', true);
            echo esc_html($registration_id ? $registration_id : '—');
            break;
        case 'agent_id':
            $agent_id = get_post_meta($post_id, '_userinfo_agent_id', true);
            echo esc_html($agent_id ? $agent_id : '—');
            break;
        case 'phone_number':
            $phone_number = get_post_meta($post_id, '_userinfo_phone_number', true);
            echo esc_html($phone_number ? $phone_number : '—');
            break;
        case 'email':
            $email = get_post_meta($post_id, '_userinfo_email', true);
            echo esc_html($email ? $email : '—');
            break;
        case 'is_valid':
            $is_valid = get_post_meta($post_id, '_userinfo_is_valid', true);
            // Default to valid if not set
            if ($is_valid === '') {
                $is_valid = '1';
            }

            $checked = ($is_valid == '1') ? 'checked' : '';
            $label_class = ($is_valid == '1') ? 'valid' : 'invalid';
            $label_text = ($is_valid == '1') ? __('Valid', 'userinfo-manager') : __('Invalid', 'userinfo-manager');

            echo '<label class="userinfo-toggle-switch" data-post-id="' . esc_attr($post_id) . '">';
            echo '<input type="checkbox" ' . $checked . '>';
            echo '<span class="userinfo-toggle-slider"></span>';
            echo '</label>';
            echo '<span class="userinfo-toggle-label ' . $label_class . '">' . $label_text . '</span>';
            break;
        case 'shortlist':
            $is_shortlisted = get_post_meta($post_id, '_userinfo_shortlisted', true);
            $shortlist_month = get_post_meta($post_id, '_userinfo_shortlist_month', true);

            $checked = ($is_shortlisted == '1') ? 'checked' : '';
            $label_class = ($is_shortlisted == '1') ? 'shortlisted' : 'not-shortlisted';
            $label_text = ($is_shortlisted == '1') ? __('Selected', 'userinfo-manager') : __('Not Selected', 'userinfo-manager');

            echo '<label class="userinfo-shortlist-toggle" data-post-id="' . esc_attr($post_id) . '">';
            echo '<input type="checkbox" ' . $checked . '>';
            echo '<span class="userinfo-toggle-slider"></span>';
            echo '</label>';
            echo '<span class="userinfo-shortlist-label ' . $label_class . '">' . $label_text . '</span>';
            if ($shortlist_month) {
                echo '<br><small style="color: #666;">' . esc_html(date('F Y', strtotime($shortlist_month . '-01'))) . '</small>';
            }
            break;
        case 'submitted_date':
            $submitted_date = get_post_meta($post_id, '_userinfo_submitted_date', true);
            if ($submitted_date) {
                echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submitted_date)));
            } else {
                echo __('N/A', 'userinfo-manager');
            }
            break;
    }
}
add_action('manage_userinfo_posts_custom_column', 'userinfo_custom_column_content', 10, 2);

/**
 * Make email column sortable
 */
function userinfo_sortable_columns($columns) {
    $columns['username'] = 'username';
    $columns['email'] = 'email';
    $columns['submitted_date'] = 'submitted_date';
    return $columns;
}
add_filter('manage_edit-userinfo_sortable_columns', 'userinfo_sortable_columns');

/**
 * Add month filter dropdown to admin
 */
function userinfo_add_month_filter() {
    global $typenow;

    if ($typenow !== 'userinfo') {
        return;
    }

    global $wpdb;

    // Get all unique months with submissions
    $months = $wpdb->get_results("
        SELECT DISTINCT YEAR(meta_value) as year, MONTH(meta_value) as month
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_userinfo_submitted_date'
        AND meta_value != ''
        ORDER BY meta_value DESC
    ");

    if (empty($months)) {
        return;
    }

    $selected_month = isset($_GET['userinfo_month']) ? sanitize_text_field($_GET['userinfo_month']) : '';
    $selected_date = isset($_GET['userinfo_date']) ? sanitize_text_field($_GET['userinfo_date']) : '';
    $selected_date_from = isset($_GET['userinfo_date_from']) ? sanitize_text_field($_GET['userinfo_date_from']) : '';
    $selected_date_to = isset($_GET['userinfo_date_to']) ? sanitize_text_field($_GET['userinfo_date_to']) : '';

    // Get min and max months for calendar range
    $min_month = '';
    $max_month = '';
    $min_date = '';
    $max_date = '';
    if (!empty($months)) {
        $last_month = end($months);
        $first_month = reset($months);
        $min_month = sprintf('%04d-%02d', $last_month->year, $last_month->month);
        $max_month = sprintf('%04d-%02d', $first_month->year, $first_month->month);
        $min_date = sprintf('%04d-%02d-01', $last_month->year, $last_month->month);
        $max_date = date('Y-m-d');
    }

    // Check if any filter is active
    $has_active_filter = !empty($selected_month) || !empty($selected_date) || !empty($selected_date_from) || !empty($selected_date_to);

    ?>
    <div class="userinfo-filters-wrapper" style="display: inline-block; vertical-align: middle; margin-right: 15px;">
        <!-- Month Filter -->
        <div style="display: inline-block; vertical-align: middle; margin-right: 15px;">
            <label for="userinfo_month" style="margin-right: 5px; font-weight: normal;">
                <?php _e('Month:', 'userinfo-manager'); ?>
            </label>
            <input
                type="month"
                name="userinfo_month"
                id="userinfo_month"
                value="<?php echo esc_attr($selected_month); ?>"
                min="<?php echo esc_attr($min_month); ?>"
                max="<?php echo esc_attr($max_month); ?>"
                style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle;"
                placeholder="<?php esc_attr_e('Select month...', 'userinfo-manager'); ?>"
            />
        </div>

        <!-- Single Date Filter -->
        <div style="display: inline-block; vertical-align: middle; margin-right: 15px;">
            <label for="userinfo_date" style="margin-right: 5px; font-weight: normal;">
                <?php _e('Date:', 'userinfo-manager'); ?>
            </label>
            <input
                type="date"
                name="userinfo_date"
                id="userinfo_date"
                value="<?php echo esc_attr($selected_date); ?>"
                min="<?php echo esc_attr($min_date); ?>"
                max="<?php echo esc_attr($max_date); ?>"
                style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle;"
                placeholder="<?php esc_attr_e('Select date...', 'userinfo-manager'); ?>"
            />
        </div>

        <!-- Date Range Filter -->
        <div style="display: inline-block; vertical-align: middle; margin-right: 15px;">
            <label for="userinfo_date_from" style="margin-right: 5px; font-weight: normal;">
                <?php _e('From:', 'userinfo-manager'); ?>
            </label>
            <input
                type="date"
                name="userinfo_date_from"
                id="userinfo_date_from"
                value="<?php echo esc_attr($selected_date_from); ?>"
                min="<?php echo esc_attr($min_date); ?>"
                max="<?php echo esc_attr($max_date); ?>"
                style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle; width: 140px;"
                placeholder="<?php esc_attr_e('Start date...', 'userinfo-manager'); ?>"
            />
            <span style="margin: 0 5px;">—</span>
            <label for="userinfo_date_to" style="margin-right: 5px; font-weight: normal;">
                <?php _e('To:', 'userinfo-manager'); ?>
            </label>
            <input
                type="date"
                name="userinfo_date_to"
                id="userinfo_date_to"
                value="<?php echo esc_attr($selected_date_to); ?>"
                min="<?php echo esc_attr($min_date); ?>"
                max="<?php echo esc_attr($max_date); ?>"
                style="height: 32px; padding: 0 8px; border: 1px solid #8c8f94; border-radius: 4px; vertical-align: middle; width: 140px;"
                placeholder="<?php esc_attr_e('End date...', 'userinfo-manager'); ?>"
            />
        </div>

        <!-- Clear All Filters Button -->
        <?php if ($has_active_filter): ?>
        <button
            type="button"
            id="userinfo_clear_all_filters"
            class="button"
            style="height: 32px; vertical-align: middle;"
        >
            <?php _e('Clear All Filters', 'userinfo-manager'); ?>
        </button>
        <?php endif; ?>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Auto-submit when month is selected
        $('#userinfo_month').on('change', function() {
            if ($(this).val()) {
                // Clear other filters when month is selected
                $('#userinfo_date').val('');
                $('#userinfo_date_from').val('');
                $('#userinfo_date_to').val('');
                $(this).closest('form').submit();
            }
        });

        // Auto-submit when single date is selected
        $('#userinfo_date').on('change', function() {
            if ($(this).val()) {
                // Clear other filters when date is selected
                $('#userinfo_month').val('');
                $('#userinfo_date_from').val('');
                $('#userinfo_date_to').val('');
                $(this).closest('form').submit();
            }
        });

        // Auto-submit when date range is completed
        $('#userinfo_date_from, #userinfo_date_to').on('change', function() {
            var dateFrom = $('#userinfo_date_from').val();
            var dateTo = $('#userinfo_date_to').val();

            // Only submit if both dates are selected
            if (dateFrom && dateTo) {
                // Clear other filters when date range is selected
                $('#userinfo_month').val('');
                $('#userinfo_date').val('');
                $(this).closest('form').submit();
            }
        });

        // Clear all filters button
        $('#userinfo_clear_all_filters').on('click', function() {
            $('#userinfo_month').val('');
            $('#userinfo_date').val('');
            $('#userinfo_date_from').val('');
            $('#userinfo_date_to').val('');
            $(this).closest('form').submit();
        });
    });
    </script>
    <?php
}
add_action('restrict_manage_posts', 'userinfo_add_month_filter');

/**
 * Remove default WordPress date filter dropdown
 */
function userinfo_remove_date_filter($months, $post_type) {
    if ($post_type === 'userinfo') {
        return array();
    }
    return $months;
}
add_filter('months_dropdown_results', 'userinfo_remove_date_filter', 10, 2);

/**
 * Filter posts by selected month, date, or date range
 */
function userinfo_filter_by_month($query) {
    global $pagenow, $typenow;

    if ($pagenow !== 'edit.php' || $typenow !== 'userinfo' || !is_admin()) {
        return;
    }

    $meta_query = array();

    // Priority 1: Date Range Filter (if both from and to are set)
    if (isset($_GET['userinfo_date_from']) && !empty($_GET['userinfo_date_from']) &&
        isset($_GET['userinfo_date_to']) && !empty($_GET['userinfo_date_to'])) {

        $date_from = sanitize_text_field($_GET['userinfo_date_from']);
        $date_to = sanitize_text_field($_GET['userinfo_date_to']);

        // Validate format YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
            $meta_query = array(
                array(
                    'key'     => '_userinfo_submitted_date',
                    'value'   => array(
                        $date_from . ' 00:00:00',
                        $date_to . ' 23:59:59'
                    ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATETIME'
                )
            );

            $query->set('meta_query', $meta_query);
            return;
        }
    }

    // Priority 2: Single Date Filter
    if (isset($_GET['userinfo_date']) && !empty($_GET['userinfo_date'])) {
        $selected_date = sanitize_text_field($_GET['userinfo_date']);

        // Validate format YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
            $meta_query = array(
                array(
                    'key'     => '_userinfo_submitted_date',
                    'value'   => array(
                        $selected_date . ' 00:00:00',
                        $selected_date . ' 23:59:59'
                    ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATETIME'
                )
            );

            $query->set('meta_query', $meta_query);
            return;
        }
    }

    // Priority 3: Month Filter (original functionality)
    if (isset($_GET['userinfo_month']) && !empty($_GET['userinfo_month'])) {
        $selected_month = sanitize_text_field($_GET['userinfo_month']);

        // Validate format YYYY-MM
        if (preg_match('/^\d{4}-\d{2}$/', $selected_month)) {
            list($year, $month) = explode('-', $selected_month);

            $meta_query = array(
                array(
                    'key'     => '_userinfo_submitted_date',
                    'value'   => array(
                        sprintf('%04d-%02d-01 00:00:00', $year, $month),
                        sprintf('%04d-%02d-31 23:59:59', $year, $month)
                    ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATETIME'
                )
            );

            $query->set('meta_query', $meta_query);
            return;
        }
    }
}
add_action('pre_get_posts', 'userinfo_filter_by_month');

/**
 * Handle sortable submitted_date column
 */
function userinfo_submitted_date_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('submitted_date' === $orderby) {
        $query->set('meta_key', '_userinfo_submitted_date');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'userinfo_submitted_date_orderby');

/**
 * Add export button to admin toolbar
 */
function userinfo_add_export_button() {
    global $typenow;

    if ($typenow !== 'userinfo') {
        return;
    }

    $selected_month = isset($_GET['userinfo_month']) ? sanitize_text_field($_GET['userinfo_month']) : '';
    $nonce = wp_create_nonce('userinfo_export_csv');

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var exportButton = $('<input type="button" class="button button-primary" value="<?php _e('Export to CSV', 'userinfo-manager'); ?>" style="margin-left: 10px;">');

            exportButton.on('click', function() {
                var month = '<?php echo esc_js($selected_month); ?>';
                var url = '<?php echo admin_url('admin-post.php'); ?>';
                var params = {
                    action: 'userinfo_export_csv',
                    nonce: '<?php echo $nonce; ?>'
                };

                if (month) {
                    params.month = month;
                }

                window.location.href = url + '?' + $.param(params);
            });

            $('.tablenav.bottom .alignleft.actions').first().append(exportButton);
        });
    </script>
    <?php
}
add_action('admin_footer-edit.php', 'userinfo_add_export_button');

/**
 * Handle CSV export
 */
function userinfo_export_csv() {
    // Check if user has permission
    if (!current_user_can('edit_posts')) {
        wp_die(__('You do not have permission to export data.', 'userinfo-manager'));
    }

    // Verify nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'userinfo_export_csv')) {
        wp_die(__('Security check failed.', 'userinfo-manager'));
    }

    // Build query arguments
    $args = array(
        'post_type'      => 'userinfo',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    // Check if filtering by month
    if (isset($_GET['month']) && !empty($_GET['month'])) {
        $selected_month = sanitize_text_field($_GET['month']);

        // Validate format YYYY-MM
        if (preg_match('/^\d{4}-\d{2}$/', $selected_month)) {
            list($year, $month) = explode('-', $selected_month);

            $args['meta_query'] = array(
                array(
                    'key'     => '_userinfo_submitted_date',
                    'value'   => array(
                        sprintf('%04d-%02d-01 00:00:00', $year, $month),
                        sprintf('%04d-%02d-31 23:59:59', $year, $month)
                    ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATETIME'
                )
            );
        }
    }

    // Get posts
    $posts = get_posts($args);

    // Generate filename
    $filename = 'userinfo-export-' . date('Y-m-d-His') . '.csv';

    if (isset($_GET['month']) && !empty($_GET['month'])) {
        $filename = 'userinfo-export-' . sanitize_file_name($_GET['month']) . '-' . date('Y-m-d-His') . '.csv';
    }

    // Generate and download CSV using helper function
    userinfo_generate_csv_download($posts, $filename);
    exit;
}
add_action('admin_post_userinfo_export_csv', 'userinfo_export_csv');

/**
 * AJAX handler to toggle valid member status
 */
function userinfo_ajax_toggle_valid_status() {
    // Check nonce
    check_ajax_referer('userinfo_toggle_valid', 'nonce');

    // Check permissions
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => __('Permission denied', 'userinfo-manager')));
    }

    // Get post ID
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$post_id) {
        wp_send_json_error(array('message' => __('Invalid post ID', 'userinfo-manager')));
    }

    // Get current status
    $current_status = get_post_meta($post_id, '_userinfo_is_valid', true);

    // Default to valid if not set
    if ($current_status === '') {
        $current_status = '1';
    }

    // Toggle status
    $new_status = ($current_status == '1') ? '0' : '1';

    // Update meta
    update_post_meta($post_id, '_userinfo_is_valid', $new_status);

    // Return success with new status
    wp_send_json_success(array(
        'status' => $new_status,
        'message' => ($new_status == '1') ? __('Member marked as valid', 'userinfo-manager') : __('Member marked as invalid', 'userinfo-manager')
    ));
}
add_action('wp_ajax_userinfo_toggle_valid_status', 'userinfo_ajax_toggle_valid_status');

/**
 * AJAX handler to toggle shortlist status
 */
function userinfo_ajax_toggle_shortlist_status() {
    // Check nonce
    check_ajax_referer('userinfo_toggle_shortlist', 'nonce');

    // Check permissions
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => __('Permission denied', 'userinfo-manager')));
    }

    // Get post ID
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$post_id) {
        wp_send_json_error(array('message' => __('Invalid post ID', 'userinfo-manager')));
    }

    // Get current status
    $current_status = get_post_meta($post_id, '_userinfo_shortlisted', true);

    // Toggle status
    $new_status = ($current_status == '1') ? '0' : '1';

    // Update meta
    update_post_meta($post_id, '_userinfo_shortlisted', $new_status);

    // If adding to shortlist, also save the current month
    if ($new_status == '1') {
        $current_month = date('Y-m');
        update_post_meta($post_id, '_userinfo_shortlist_month', $current_month);
    } else {
        // If removing from shortlist, clear the month
        delete_post_meta($post_id, '_userinfo_shortlist_month');
    }

    // Return success with new status
    wp_send_json_success(array(
        'status' => $new_status,
        'month' => ($new_status == '1') ? date('F Y') : '',
        'message' => ($new_status == '1') ? __('User added to shortlist', 'userinfo-manager') : __('User removed from shortlist', 'userinfo-manager')
    ));
}
add_action('wp_ajax_userinfo_toggle_shortlist_status', 'userinfo_ajax_toggle_shortlist_status');


/**
 * Extend admin search to include custom meta fields
 */
function userinfo_extend_admin_search($search, $query) {
    global $wpdb;

    // Only modify search for userinfo post type in admin
    if (!is_admin() || !$query->is_search() || $query->get('post_type') !== 'userinfo') {
        return $search;
    }

    $search_term = $query->get('s');
    if (empty($search_term)) {
        return $search;
    }

    // Meta keys to search
    $meta_keys = array(
        '_userinfo_registration_id',
        '_userinfo_agent_id',
        '_userinfo_phone_number'
    );

    // Build meta query conditions
    $meta_conditions = array();
    foreach ($meta_keys as $meta_key) {
        $meta_conditions[] = $wpdb->prepare(
            "({$wpdb->postmeta}.meta_key = %s AND {$wpdb->postmeta}.meta_value LIKE %s)",
            $meta_key,
            '%' . $wpdb->esc_like($search_term) . '%'
        );
    }

    // Modify the search query to include meta fields
    if (!empty($meta_conditions)) {
        $search = preg_replace(
            "/\(\({$wpdb->posts}.post_title LIKE/",
            "(" . implode(' OR ', $meta_conditions) . ") OR (({$wpdb->posts}.post_title LIKE",
            $search
        );
    }

    return $search;
}
add_filter('posts_search', 'userinfo_extend_admin_search', 10, 2);

/**
 * Join postmeta table for custom field search
 */
function userinfo_search_join($join, $query) {
    global $wpdb;

    // Only modify join for userinfo post type in admin search
    if (!is_admin() || !$query->is_search() || $query->get('post_type') !== 'userinfo') {
        return $join;
    }

    $search_term = $query->get('s');
    if (empty($search_term)) {
        return $join;
    }

    // Join postmeta table if not already joined
    if (strpos($join, $wpdb->postmeta) === false) {
        $join .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id";
    }

    return $join;
}
add_filter('posts_join', 'userinfo_search_join', 10, 2);

/**
 * Ensure distinct results when searching meta fields
 */
function userinfo_search_distinct($distinct, $query) {
    // Only modify for userinfo post type in admin search
    if (!is_admin() || !$query->is_search() || $query->get('post_type') !== 'userinfo') {
        return $distinct;
    }

    $search_term = $query->get('s');
    if (empty($search_term)) {
        return $distinct;
    }

    return 'DISTINCT';
}
add_filter('posts_distinct', 'userinfo_search_distinct', 10, 2);




/**
 * Handle CSV export for shortlisted users
 */
function userinfo_export_shortlist_csv() {
    // Check if user has permission
    if (!current_user_can('edit_posts')) {
        wp_die(__('You do not have permission to export data.', 'userinfo-manager'));
    }

    // Verify nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'userinfo_export_shortlist_csv')) {
        wp_die(__('Security check failed.', 'userinfo-manager'));
    }

    // Build query arguments
    $args = array(
        'post_type'      => 'userinfo',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => '_userinfo_shortlisted',
                'value'   => '1',
                'compare' => '='
            )
        ),
        'orderby'        => 'meta_value',
        'meta_key'       => '_userinfo_submitted_date',
        'order'          => 'DESC'
    );

    // Add date filters (Priority: Date Range > Single Date > Month)
    $filter_label = '';

    // Priority 1: Date Range Filter
    if (isset($_GET['shortlist_date_from']) && !empty($_GET['shortlist_date_from']) &&
        isset($_GET['shortlist_date_to']) && !empty($_GET['shortlist_date_to'])) {

        $date_from = sanitize_text_field($_GET['shortlist_date_from']);
        $date_to = sanitize_text_field($_GET['shortlist_date_to']);

        // Validate format YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {

            $args['meta_query'][] = array(
                'key'     => '_userinfo_submitted_date',
                'value'   => array(
                    $date_from . ' 00:00:00',
                    $date_to . ' 23:59:59'
                ),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            );

            $filter_label = $date_from . '-to-' . $date_to;
        }
    }
    // Priority 2: Single Date Filter
    elseif (isset($_GET['shortlist_date']) && !empty($_GET['shortlist_date'])) {
        $selected_date = sanitize_text_field($_GET['shortlist_date']);

        // Validate format YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
            $args['meta_query'][] = array(
                'key'     => '_userinfo_submitted_date',
                'value'   => array(
                    $selected_date . ' 00:00:00',
                    $selected_date . ' 23:59:59'
                ),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            );

            $filter_label = $selected_date;
        }
    }
    // Priority 3: Month Filter
    elseif (isset($_GET['shortlist_month']) && !empty($_GET['shortlist_month'])) {
        $selected_month = sanitize_text_field($_GET['shortlist_month']);

        // Validate format YYYY-MM
        if (preg_match('/^\d{4}-\d{2}$/', $selected_month)) {
            list($year, $month) = explode('-', $selected_month);

            $args['meta_query'][] = array(
                'key'     => '_userinfo_submitted_date',
                'value'   => array(
                    sprintf('%04d-%02d-01 00:00:00', $year, $month),
                    sprintf('%04d-%02d-31 23:59:59', $year, $month)
                ),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            );

            $filter_label = $selected_month;
        }
    }

    // Get posts
    $posts = get_posts($args);

    // Generate filename
    $filename = 'userinfo-shortlist-export-' . date('Y-m-d-His') . '.csv';

    if (!empty($filter_label)) {
        $filename = 'userinfo-shortlist-' . sanitize_file_name($filter_label) . '-' . date('Y-m-d-His') . '.csv';
    }

    // Generate and download CSV using helper function
    userinfo_generate_csv_download($posts, $filename);
    exit;
}
add_action('admin_post_userinfo_export_shortlist_csv', 'userinfo_export_shortlist_csv');

/**
 * Enqueue admin scripts and styles for toggle switches
 */
function userinfo_enqueue_admin_assets($hook) {
    global $post_type;

    // Only load on userinfo pages
    if ($post_type !== 'userinfo' && $hook !== 'edit.php') {
        return;
    }

    // Add inline CSS for toggle switch
    add_action('admin_head', 'userinfo_admin_toggle_styles');

    // Add inline JavaScript for toggle functionality
    add_action('admin_footer', 'userinfo_admin_toggle_scripts');
}
add_action('admin_enqueue_scripts', 'userinfo_enqueue_admin_assets');

/**
 * Toggle switch CSS styles
 */
function userinfo_admin_toggle_styles() {
    global $post_type, $pagenow;

    // Only output on userinfo pages
    if ($post_type !== 'userinfo' && !($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'userinfo')) {
        return;
    }
    ?>
    <style>
        /* Toggle Switch Styles */
        .userinfo-toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
            margin: 0;
        }

        .userinfo-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .userinfo-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #dc3232;
            transition: .4s;
            border-radius: 24px;
        }

        .userinfo-toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        .userinfo-toggle-switch input:checked + .userinfo-toggle-slider {
            background-color: #46b450;
        }

        .userinfo-toggle-switch input:checked + .userinfo-toggle-slider:before {
            transform: translateX(26px);
        }

        .userinfo-toggle-switch.loading .userinfo-toggle-slider {
            opacity: 0.5;
            cursor: wait;
        }

        .userinfo-toggle-label {
            display: inline-block;
            margin-left: 8px;
            vertical-align: middle;
            font-weight: 600;
        }

        .userinfo-toggle-label.valid {
            color: #46b450;
        }

        .userinfo-toggle-label.invalid {
            color: #dc3232;
        }

        /* Shortlist Toggle Styles */
        .userinfo-shortlist-toggle {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
            margin: 0;
        }

        .userinfo-shortlist-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .userinfo-shortlist-toggle input:checked + .userinfo-toggle-slider {
            background-color: #0073aa;
        }

        .userinfo-shortlist-toggle.loading .userinfo-toggle-slider {
            opacity: 0.5;
            cursor: wait;
        }

        .userinfo-shortlist-label {
            display: inline-block;
            margin-left: 8px;
            vertical-align: middle;
            font-weight: 600;
        }

        .userinfo-shortlist-label.shortlisted {
            color: #0073aa;
        }

        .userinfo-shortlist-label.not-shortlisted {
            color: #999;
        }

        /* Details view toggle container */
        .userinfo-details-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
    </style>
    <?php
}

/**
 * Toggle switch JavaScript
 */
function userinfo_admin_toggle_scripts() {
    global $post_type, $pagenow;

    // Only output on userinfo pages
    if ($post_type !== 'userinfo' && !($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'userinfo')) {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Handle valid toggle switch clicks
        $('.userinfo-toggle-switch input').on('change', function() {
            var $toggle = $(this).closest('.userinfo-toggle-switch');
            var $label = $toggle.siblings('.userinfo-toggle-label');
            var postId = $toggle.data('post-id');
            var isChecked = $(this).is(':checked');

            // Add loading state
            $toggle.addClass('loading');

            // Make AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'userinfo_toggle_valid_status',
                    post_id: postId,
                    nonce: '<?php echo wp_create_nonce('userinfo_toggle_valid'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        // Update label
                        if (response.data.status == '1') {
                            $label.text('<?php echo esc_js(__('Valid', 'userinfo-manager')); ?>')
                                  .removeClass('invalid')
                                  .addClass('valid');
                        } else {
                            $label.text('<?php echo esc_js(__('Invalid', 'userinfo-manager')); ?>')
                                  .removeClass('valid')
                                  .addClass('invalid');
                        }

                        // Update hidden field if in details view
                        $('#userinfo_is_valid').val(response.data.status);

                        // Show success message briefly
                        if (typeof wp !== 'undefined' && wp.data) {
                            // WordPress 5.0+ notice
                            wp.data.dispatch('core/notices').createNotice(
                                'success',
                                response.data.message,
                                {
                                    isDismissible: true,
                                    type: 'snackbar'
                                }
                            );
                        }
                    } else {
                        // Revert toggle on error
                        $toggle.find('input').prop('checked', !isChecked);
                        alert(response.data.message || '<?php echo esc_js(__('Error updating status', 'userinfo-manager')); ?>');
                    }
                },
                error: function() {
                    // Revert toggle on error
                    $toggle.find('input').prop('checked', !isChecked);
                    alert('<?php echo esc_js(__('Error updating status', 'userinfo-manager')); ?>');
                },
                complete: function() {
                    // Remove loading state
                    $toggle.removeClass('loading');
                }
            });
        });

        // Handle shortlist toggle switch clicks
        $('.userinfo-shortlist-toggle input').on('change', function() {
            var $toggle = $(this).closest('.userinfo-shortlist-toggle');
            var $label = $toggle.siblings('.userinfo-shortlist-label');
            var postId = $toggle.data('post-id');
            var isChecked = $(this).is(':checked');

            // Add loading state
            $toggle.addClass('loading');

            // Make AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'userinfo_toggle_shortlist_status',
                    post_id: postId,
                    nonce: '<?php echo wp_create_nonce('userinfo_toggle_shortlist'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        // Update label
                        if (response.data.status == '1') {
                            $label.text('<?php echo esc_js(__('Selected', 'userinfo-manager')); ?>')
                                  .removeClass('not-shortlisted')
                                  .addClass('shortlisted');

                            // Add month info
                            if (response.data.month) {
                                var monthHtml = '<br><small style="color: #666;">' + response.data.month + '</small>';
                                $label.after(monthHtml);
                            }
                        } else {
                            $label.text('<?php echo esc_js(__('Not Selected', 'userinfo-manager')); ?>')
                                  .removeClass('shortlisted')
                                  .addClass('not-shortlisted');

                            // Remove month info
                            $label.siblings('small').remove();
                        }

                        // Show success message
                        if (typeof wp !== 'undefined' && wp.data) {
                            wp.data.dispatch('core/notices').createNotice(
                                'success',
                                response.data.message,
                                {
                                    isDismissible: true,
                                    type: 'snackbar'
                                }
                            );
                        }
                    } else {
                        // Revert toggle on error
                        $toggle.find('input').prop('checked', !isChecked);
                        alert(response.data.message || '<?php echo esc_js(__('Error updating shortlist status', 'userinfo-manager')); ?>');
                    }
                },
                error: function() {
                    // Revert toggle on error
                    $toggle.find('input').prop('checked', !isChecked);
                    alert('<?php echo esc_js(__('Error updating shortlist status', 'userinfo-manager')); ?>');
                },
                complete: function() {
                    // Remove loading state
                    $toggle.removeClass('loading');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Register bulk action for exporting selected items
 */
function userinfo_register_bulk_actions($bulk_actions) {
    $bulk_actions['export_selected_csv'] = __('Export to CSV', 'userinfo-manager');
    return $bulk_actions;
}
add_filter('bulk_actions-edit-userinfo', 'userinfo_register_bulk_actions');

/**
 * Handle bulk export action
 */
function userinfo_handle_bulk_export($redirect_to, $action, $post_ids) {
    if ($action !== 'export_selected_csv') {
        return $redirect_to;
    }

    // Check permissions
    if (!current_user_can('edit_posts')) {
        wp_die(__('You do not have permission to export data.', 'userinfo-manager'));
    }

    // Validate post IDs
    if (empty($post_ids)) {
        return $redirect_to;
    }

    // Get posts by IDs
    $args = array(
        'post_type'      => 'userinfo',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__in'       => $post_ids,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $posts = get_posts($args);

    if (empty($posts)) {
        return $redirect_to;
    }

    // Generate filename
    $filename = 'userinfo-export-selected-' . date('Y-m-d-His') . '.csv';

    // Generate and download CSV
    userinfo_generate_csv_download($posts, $filename);

    // Exit to prevent redirect
    exit;
}
add_filter('handle_bulk_actions-edit-userinfo', 'userinfo_handle_bulk_export', 10, 3);

/**
 * Helper function to generate and download CSV from posts array
 */
function userinfo_generate_csv_download($posts, $filename) {
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');
    header('Expires: 0');

    // Create output stream
    $output = fopen('php://output', 'w');

    // Add BOM for UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Add CSV headers
    fputcsv($output, array(
        __('Full Name', 'userinfo-manager'),
        __('Username', 'userinfo-manager'),
        __('Registration ID', 'userinfo-manager'),
        __('Agent ID', 'userinfo-manager'),
        __('Phone Number', 'userinfo-manager'),
        __('Email Address', 'userinfo-manager'),
        __('Position', 'userinfo-manager'),
        __('Prize', 'userinfo-manager'),
        __('Submitted Date', 'userinfo-manager'),
        __('Post Date', 'userinfo-manager'),
        __('Valid Member', 'userinfo-manager')
    ));

    // Add data rows
    if (!empty($posts)) {
        foreach ($posts as $post) {
            $full_name = get_post_meta($post->ID, '_userinfo_full_name', true);
            $username = get_post_meta($post->ID, '_userinfo_username', true);
            $registration_id = get_post_meta($post->ID, '_userinfo_registration_id', true);
            $agent_id = get_post_meta($post->ID, '_userinfo_agent_id', true);
            $phone_number = get_post_meta($post->ID, '_userinfo_phone_number', true);
            $email = get_post_meta($post->ID, '_userinfo_email', true);
            $position = get_post_meta($post->ID, '_userinfo_position', true);
            $prize = get_post_meta($post->ID, '_userinfo_prize', true);
            $submitted_date = get_post_meta($post->ID, '_userinfo_submitted_date', true);
            $is_valid = get_post_meta($post->ID, '_userinfo_is_valid', true);

            $formatted_submitted_date = $submitted_date ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submitted_date)) : 'N/A';
            $formatted_post_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_date));

            // Default to valid if not set
            if ($is_valid === '') {
                $is_valid = '1';
            }
            $valid_status = ($is_valid == '1') ? __('Valid', 'userinfo-manager') : __('Invalid', 'userinfo-manager');

            fputcsv($output, array(
                $full_name,
                $username,
                $registration_id,
                $agent_id,
                $phone_number,
                $email,
                $position ? $position : '',
                $prize ? $prize : '',
                $formatted_submitted_date,
                $formatted_post_date,
                $valid_status
            ));
        }
    }

    // Close output stream
    fclose($output);
}

/**
 * AJAX handler to verify user by username and phone number
 */
function userinfo_ajax_verify_user() {
    // Check nonce
    check_ajax_referer('userinfo_verify_user', 'nonce');

    // Get inputs
    $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
    $phone_number = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';

    // Validate inputs
    if (empty($username) || empty($phone_number)) {
        wp_send_json_error(array(
            'message' => __('Please enter both Username and Phone Number', 'userinfo-manager')
        ));
    }

    // Query for user with matching username and phone
    $args = array(
        'post_type'      => 'userinfo',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_userinfo_username',
                'value'   => $username,
                'compare' => '='
            ),
            array(
                'key'     => '_userinfo_phone_number',
                'value'   => $phone_number,
                'compare' => '='
            )
        )
    );

    $posts = get_posts($args);

    if (empty($posts)) {
        wp_send_json_error(array(
            'message' => __('No matching user found. Please check your Username and Phone Number.', 'userinfo-manager'),
            'found' => false
        ));
    }

    // User found - get details
    $post = $posts[0];
    $full_name = get_post_meta($post->ID, '_userinfo_full_name', true);
    $username = get_post_meta($post->ID, '_userinfo_username', true);
    $registration_id = get_post_meta($post->ID, '_userinfo_registration_id', true);
    $agent_id = get_post_meta($post->ID, '_userinfo_agent_id', true);
    $email = get_post_meta($post->ID, '_userinfo_email', true);
    $is_valid = get_post_meta($post->ID, '_userinfo_is_valid', true);

    // Default to valid if not set
    if ($is_valid === '') {
        $is_valid = '1';
    }

    wp_send_json_success(array(
        'found' => true,
        'full_name' => $full_name,
        'username' => $username,
        'registration_id' => $registration_id,
        'agent_id' => $agent_id,
        'phone_number' => $phone_number,
        'email' => $email,
        'is_valid' => $is_valid,
        'valid_text' => ($is_valid == '1') ? __('Valid', 'userinfo-manager') : __('Invalid', 'userinfo-manager')
    ));
}
add_action('wp_ajax_userinfo_verify_user', 'userinfo_ajax_verify_user');
add_action('wp_ajax_nopriv_userinfo_verify_user', 'userinfo_ajax_verify_user');

/**
 * Enqueue jQuery for verification form
 */
function userinfo_enqueue_verification_scripts() {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'userinfo_check')) {
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'userinfo_enqueue_verification_scripts');

/**
 * Shortcode for user verification form
 * Usage: [userinfo_check]
 */
function userinfo_check_shortcode($atts) {
    // Verification form JavaScript is now handled in userinfo-frontend.js
    // No inline scripts needed

    ob_start();
    ?>
    <div class="userinfo-check-container">
        <div class="userinfo-check-form-wrapper">
            <h2>
                <?php _e('Check User Validity', 'userinfo-manager'); ?>
            </h2>

            <form id="userinfo-check-form" class="userinfo-check-form">
                <?php wp_nonce_field('userinfo_verify_user', 'userinfo_verify_nonce'); ?>

                <div class="form-group">
                    <label for="check_username">
                        <?php _e('Username', 'userinfo-manager'); ?> <span style="color: #f5576c;">*</span>
                    </label>
                    <input
                        type="text"
                        id="check_username"
                        name="username"
                        required
                        title="<?php esc_attr_e('Username is required', 'userinfo-manager'); ?>"
                        placeholder="<?php esc_attr_e('ইউজার নেম লিখুন', 'userinfo-manager'); ?>"
                    />
                    <span class="field-error" data-error-for="check_username"></span>
                </div>

                <div class="form-group">
                    <label for="check_phone_number">
                        <?php _e('Phone Number', 'userinfo-manager'); ?> <span style="color: #f5576c;">*</span>
                    </label>
                    <input
                        type="text"
                        id="check_phone_number"
                        name="phone_number"
                        required
                        pattern="[0-9]{10,50}"
                        minlength="10"
                        maxlength="50"
                        title="<?php esc_attr_e('Phone number must be 10-50 digits only', 'userinfo-manager'); ?>"
                        placeholder="<?php esc_attr_e('মোবাইল নাম্বার লিখুন', 'userinfo-manager'); ?>"
                    />
                    <span class="field-error" data-error-for="check_phone_number"></span>
                </div>

                <div class="form-group full-width">
                    <button type="submit" id="verify-btn">
                        <span><?php _e('Verify User', 'userinfo-manager'); ?></span>
                    </button>
                </div>
            </form>

            <div id="verification-result"></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('userinfo_check', 'userinfo_check_shortcode');

/**
 * Results shortcode - Display shortlisted users by month/year in accordions
 * Usage: [userinfo_results]
 */
function userinfo_results_shortcode($atts) {
    ob_start();

    global $wpdb;

    // Get all published result_generate posts
    $result_posts = get_posts(array(
        'post_type' => 'result_generate',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    ?>
    <div class="userinfo-results-container">
        <h2 class="results-title"><?php _e('Lottery Results', 'userinfo-manager'); ?></h2>

        <?php if (!empty($result_posts)): ?>
            <div class="results-accordion-wrapper">
                <?php foreach ($result_posts as $index => $result_post): ?>
                    <?php
                    $result_title = get_the_title($result_post->ID);
                    $start_date = get_post_meta($result_post->ID, '_result_start_date', true);
                    $end_date = get_post_meta($result_post->ID, '_result_end_date', true);

                    // Format date display
                    $date_display = '';
                    if ($start_date && $end_date) {
                        $date_display = date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date));
                    }

                    // Query shortlisted users within this date range
                    $args = array(
                        'post_type' => 'userinfo',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => '_userinfo_shortlisted',
                                'value' => '1',
                                'compare' => '='
                            ),
                            array(
                                'key' => '_userinfo_submitted_date',
                                'value' => array($start_date, $end_date),
                                'compare' => 'BETWEEN',
                                'type' => 'DATE'
                            )
                        ),
                        'orderby' => 'meta_value',
                        'meta_key' => '_userinfo_position',
                        'order' => 'ASC'
                    );

                    $query = new WP_Query($args);
                    ?>

                    <div class="result-accordion-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <button class="accordion-header" data-result-id="<?php echo esc_attr($result_post->ID); ?>">
                            <span class="accordion-icon">🎊</span>
                            <span class="accordion-title"><?php echo esc_html($result_title); ?></span>
                            <?php if ($date_display): ?>
                                <span class="accordion-date">(<?php echo esc_html($date_display); ?>)</span>
                            <?php endif; ?>
                            <span class="accordion-count">[<?php echo $query->found_posts; ?> <?php _e('Winners', 'userinfo-manager'); ?>]</span>
                            <span class="accordion-arrow">▼</span>
                        </button>

                        <div class="accordion-content" <?php echo $index === 0 ? 'style="display: block;"' : ''; ?>>
                            <?php if ($query->have_posts()): ?>
                                <div class="winners-list">
                                    <?php while ($query->have_posts()): $query->the_post(); ?>
                                        <?php
                                        $post_id = get_the_ID();
                                        $phone_number = get_post_meta($post_id, '_userinfo_phone_number', true);
                                        $registration_id = get_post_meta($post_id, '_userinfo_registration_id', true);
                                        $position = get_post_meta($post_id, '_userinfo_position', true);
                                        $prize = get_post_meta($post_id, '_userinfo_prize', true);
                                        $full_name = get_post_meta($post_id, '_userinfo_full_name', true);

                                        // Mask middle 4 digits of phone number
                                        $masked_phone = userinfo_mask_phone_number($phone_number);
                                        ?>

                                        <div class="winner-card">
                                            <div class="winner-position">
                                                <?php if ($position): ?>
                                                    <span class="position-badge"><?php echo esc_html($position); ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="winner-details">
                                                <div class="winner-info-row">
                                                    <span class="info-label"><?php _e('Name:', 'userinfo-manager'); ?></span>
                                                    <span class="info-value"><?php echo esc_html($full_name); ?></span>
                                                </div>

                                                <div class="winner-info-row">
                                                    <span class="info-label"><?php _e('Phone Number:', 'userinfo-manager'); ?></span>
                                                    <span class="info-value"><?php echo esc_html($masked_phone); ?></span>
                                                </div>

                                                <div class="winner-info-row">
                                                    <span class="info-label"><?php _e('Lottery Number:', 'userinfo-manager'); ?></span>
                                                    <span class="info-value reg-id"><?php echo esc_html($registration_id); ?></span>
                                                </div>

                                                <?php if ($prize): ?>
                                                    <div class="winner-prize">
                                                        <span class="prize-icon">🎁</span>
                                                        <span class="prize-text"><?php echo esc_html($prize); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="no-winners"><?php _e('No winners for this month.', 'userinfo-manager'); ?></p>
                            <?php endif; ?>

                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-results"><?php _e('No results available yet.', 'userinfo-manager'); ?></p>
        <?php endif; ?>
    </div>

    <style>
        /* Results Container */
        .userinfo-results-container {
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .results-title {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        /* Accordion Styles */
        .results-accordion-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        .result-accordion-item {
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .result-accordion-item.active {
            border-color: #FFD700;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        }

        .accordion-header {
            width: 100%;
            padding: 18px 25px;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        .accordion-header:hover {
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            transform: translateY(-2px);
        }

        .accordion-icon {
            font-size: 24px;
            margin-right: 12px;
        }

        .accordion-title {
            flex: 1;
            text-align: left;
            font-size: 20px;
        }

        .accordion-date {
            margin: 0 10px;
            font-size: 13px;
            opacity: 0.8;
            color: #666;
            font-weight: normal;
        }

        .accordion-count {
            margin: 0 15px;
            font-size: 14px;
            opacity: 0.9;
            color: #2c3e50;
            font-weight: 600;
        }

        .accordion-arrow {
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .result-accordion-item.active .accordion-arrow {
            transform: rotate(180deg);
        }

        .accordion-content {
            display: none;
            padding: 25px;
            background: #f9f9f9;
        }

        /* Winners List - Grid Layout */
        .winners-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }

        .winner-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e8e8e8;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .winner-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FFD700 0%, #FFA500 50%, #FF8C00 100%);
        }

        .winner-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(255, 165, 0, 0.2);
            border-color: #FFD700;
        }

        .winner-position {
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .position-badge {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #1a1a1a;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            min-width: 45px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4);
            letter-spacing: 0.5px;
        }

        .winner-details {
            padding-top: 8px;
        }

        .winner-info-row {
            margin-bottom: 10px;
            display: flex;
            align-items: baseline;
        }

        .winner-info-row:last-of-type {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            min-width: 80px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #1a1a1a;
            font-weight: 600;
            font-size: 15px;
            flex: 1;
        }

        .info-value.reg-id {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            color: #333;
            letter-spacing: 1px;
            display: inline-block;
        }

        .winner-prize {
            margin-top: 12px;
            padding: 10px 14px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .prize-icon {
            font-size: 20px;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));
        }

        .prize-text {
            color: white;
            font-size: 14px;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .no-winners, .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #999;
            font-size: 16px;
        }

        /* Responsive Design - Enhanced for Mobile */
        @media (max-width: 768px) {
            /* Container adjustments */
            .userinfo-results-container {
                padding: 15px 10px;
                border-radius: 8px;
            }

            .results-title {
                font-size: 22px;
                margin-bottom: 20px;
            }

            /* Accordion header - mobile optimized */
            .accordion-header {
                padding: 15px;
                font-size: 16px;
                flex-wrap: wrap;
            }

            .accordion-icon {
                font-size: 20px;
                margin-right: 8px;
            }

            .accordion-title {
                font-size: 16px;
                flex: 1 1 auto;
            }

            .accordion-count {
                font-size: 12px;
                margin: 0 10px 0 0;
                order: 3;
                flex-basis: 100%;
                text-align: left;
                padding-left: 28px;
                margin-top: 5px;
            }

            .accordion-arrow {
                font-size: 12px;
            }

            /* Accordion content */
            .accordion-content {
                padding: 15px 10px;
            }

            /* Winners List - Single column on mobile */
            .winners-list {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            /* Winner cards - mobile optimized */
            .winner-card {
                padding: 14px;
            }

            .winner-position {
                top: 10px;
                right: 10px;
            }

            .position-badge {
                padding: 5px 10px;
                font-size: 13px;
                min-width: 40px;
            }

            .winner-details {
                padding-top: 6px;
            }

            /* Info rows - mobile */
            .winner-info-row {
                margin-bottom: 8px;
            }

            .info-label {
                font-size: 11px;
                min-width: 75px;
            }

            .info-value {
                font-size: 14px;
            }

            .info-value.reg-id {
                font-size: 13px;
                padding: 3px 8px;
            }

            /* Prize - mobile */
            .winner-prize {
                margin-top: 10px;
                padding: 8px 12px;
            }

            .prize-icon {
                font-size: 18px;
            }

            .prize-text {
                font-size: 13px;
            }

            /* Empty states */
            .no-winners, .no-results {
                padding: 30px 15px;
                font-size: 14px;
            }

        }

        /* Extra small mobile devices */
        @media (max-width: 480px) {
            .userinfo-results-container {
                padding: 10px 5px;
            }

            .results-title {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .accordion-header {
                padding: 10px;
                font-size: 14px;
            }

            .accordion-title {
                font-size: 14px;
            }

            .accordion-count {
                font-size: 11px;
                padding-left: 22px;
            }

            .accordion-content {
                padding: 12px 8px;
            }

            .winners-list {
                gap: 10px;
            }

            .winner-card {
                padding: 12px;
            }

            .position-badge {
                font-size: 12px;
                padding: 4px 8px;
                min-width: 38px;
            }

            .info-label {
                font-size: 10px;
                min-width: 70px;
            }

            .info-value {
                font-size: 13px;
            }

            .info-value.reg-id {
                font-size: 12px;
                padding: 3px 6px;
            }

            .winner-prize {
                padding: 7px 10px;
            }

            .prize-text {
                font-size: 12px;
            }

            .prize-icon {
                font-size: 16px;
            }
        }
    </style>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const accordionHeaders = document.querySelectorAll('.accordion-header');

        accordionHeaders.forEach(header => {
            // Support both click and touch events for better mobile experience
            header.addEventListener('click', function(e) {
                e.preventDefault();
                toggleAccordion(this);
            });

            // Improve touch responsiveness
            header.addEventListener('touchend', function(e) {
                e.preventDefault();
                toggleAccordion(this);
            });
        });

        function toggleAccordion(header) {
            const accordionItem = header.parentElement;
            const accordionContent = accordionItem.querySelector('.accordion-content');
            const isActive = accordionItem.classList.contains('active');

            // Close all accordions
            document.querySelectorAll('.result-accordion-item').forEach(item => {
                item.classList.remove('active');
                const content = item.querySelector('.accordion-content');
                content.style.display = 'none';
            });

            // Open clicked accordion if it wasn't active
            if (!isActive) {
                accordionItem.classList.add('active');
                accordionContent.style.display = 'block';

                // Smooth scroll to accordion on mobile
                if (window.innerWidth <= 768) {
                    setTimeout(function() {
                        accordionItem.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 100);
                }
            }
        }
    });
    </script>
    <?php

    return ob_get_clean();
}
add_shortcode('userinfo_results', 'userinfo_results_shortcode');

/**
 * NOTE: All styles for verification form are now in assets/css/userinfo-frontend.css
 * Inline styles have been removed to prevent conflicts with themes
 */

/**
 * Tabbed interface shortcode combining registration and status check
 * Usage: [userinfo_tabs]
 */
function userinfo_tabs_shortcode($atts) {
    // Tab switching is now handled in userinfo-frontend.js
    // No inline scripts needed

    ob_start();

    // Get plugin URL for logo
    $plugin_url = plugin_dir_url(__FILE__);
    $logo_url = $plugin_url . 'assets/logo.webp';

    // Get countdown settings
    $countdown_enabled = get_option('userinfo_countdown_enabled', 0);
    $custom_countdown_enabled = get_option('userinfo_custom_countdown_enabled', 0);
    $custom_countdown_date = get_option('userinfo_custom_countdown_date', '');

    // Check if ANY countdown is enabled (monthly OR custom)
    $countdown_active = ($countdown_enabled || ($custom_countdown_enabled && !empty($custom_countdown_date)));
    $show_countdown = $countdown_active;
    ?>
    <div class="userinfo-page-wrapper">
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
            <button class="userinfo-tab-btn" data-tab="result">
                <span class="tab-icon">🏆</span>
                <?php _e('Result', 'userinfo-manager'); ?>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="userinfo-tab-content-wrapper">
            <!-- Registration Tab Content -->
            <div id="registration-tab" class="userinfo-tab-content active">
                <div class="userinfo-modal-style-container">
                    <div class="userinfo-modal-style-content">
                        <?php echo do_shortcode('[userinfo_form]'); ?>
                    </div>
                </div>
            </div>

            <!-- Status Check Tab Content -->
            <div id="status-check-tab" class="userinfo-tab-content">
                <div class="userinfo-modal-style-container">
                    <div class="userinfo-modal-style-content">
                        <?php echo do_shortcode('[userinfo_check]'); ?>
                    </div>
                </div>
            </div>

            <!-- Result Tab Content -->
            <div id="result-tab" class="userinfo-tab-content">
                <div class="userinfo-modal-style-container">
                    <div class="userinfo-modal-style-content">
                        <?php echo do_shortcode('[userinfo_results]'); ?>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Footer - Prize List Button -->
        <div class="userinfo-footer">
            <button type="button" id="prizelist-btn" class="visit-website-btn">
                <svg class="prize-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                </svg>
                <span><?php _e('পুরস্কারের তালিকা', 'userinfo-manager'); ?></span>
            </button>
        </div>

        <!-- Prize List Modal -->
        <?php
        // Get prize data from database with dynamic prizes array
        $prize_data = get_option('userinfo_prize_data', array(
            'prizes' => array(
                array(
                    'title' => 'First Prize',
                    'rank' => '১ম পুরস্কার',
                    'icon' => '🥇',
                    'amount' => '৳ ১,০০,০০০',
                    'details' => 'স্বর্ণপদক + ট্রফি + সার্টিফিকেট',
                    'color' => 'gold'
                ),
                array(
                    'title' => 'Second Prize',
                    'rank' => '২য় পুরস্কার',
                    'icon' => '🥈',
                    'amount' => '৳ ৫০,০০০',
                    'details' => 'রৌপ্যপদক + ট্রফি + সার্টিফিকেট',
                    'color' => 'silver'
                ),
                array(
                    'title' => 'Third Prize',
                    'rank' => '৩য় পুরস্কার',
                    'icon' => '🥉',
                    'amount' => '৳ ২৫,০০০',
                    'details' => 'ব্রোঞ্জপদক + ট্রফি + সার্টিফিকেট',
                    'color' => 'bronze'
                ),
                array(
                    'title' => '4th - 10th Prize',
                    'rank' => '৪র্থ - ১০ম পুরস্কার',
                    'icon' => '🎁',
                    'amount' => '৳ ১০,০০০',
                    'details' => 'সার্টিফিকেট + উপহার',
                    'color' => 'standard'
                ),
                array(
                    'title' => 'Consolation Prize (11-20)',
                    'rank' => 'সান্ত্বনা পুরস্কার (১১-২০)',
                    'icon' => '🎖️',
                    'amount' => '৳ ৫,০০০',
                    'details' => 'সার্টিফিকেট',
                    'color' => 'consolation'
                )
            ),
            'modal_title' => '🏆 পুরস্কারের তালিকা',
            'important_note' => 'সকল পুরস্কার বিজয়ীদের নাম প্রতি মাসে ঘোষণা করা হবে। পুরস্কার প্রদান কার্যক্রম ১৫ দিনের মধ্যে সম্পন্ন করা হবে।'
        ));

        // Ensure prizes array exists
        if (!isset($prize_data['prizes']) || !is_array($prize_data['prizes'])) {
            $prize_data['prizes'] = array();
        }
        ?>
        <div id="prizelist-modal" class="prizelist-modal">
            <div class="prizelist-modal-content">
                <span class="prizelist-close">&times;</span>
                <h2 class="prizelist-title"><?php echo esc_html($prize_data['modal_title']); ?></h2>

                <div class="prizelist-container">
                    <?php
                    // Loop through dynamic prizes array
                    if (!empty($prize_data['prizes']) && is_array($prize_data['prizes'])) {
                        foreach ($prize_data['prizes'] as $prize) {
                            ?>
                            <div class="prize-item prize-<?php echo esc_attr($prize['color']); ?>">
                                <div class="prize-rank"><?php echo esc_html($prize['rank']); ?></div>
                                <div class="prize-amount"><?php echo esc_html($prize['amount']); ?></div>
                                <div class="prize-details"><?php echo esc_html($prize['details']); ?></div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p style="text-align: center; padding: 30px; color: #666;">No prizes available.</p>';
                    }
                    ?>
                </div>

                <div class="prizelist-note">
                    <strong>বিশেষ দ্রষ্টব্য:</strong> <?php echo esc_html($prize_data['important_note']); ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Agent9w.com Inspired Design */
        /* Page Wrapper */
        .userinfo-page-wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FF8C00 100%);
            min-height: 100vh;
        }

        /* Company Logo - Outside Form */
        .userinfo-company-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin-bottom: 30px;
        }

        .userinfo-company-logo a {
            display: inline-block;
            text-decoration: none;
            line-height: 0;
        }

        .userinfo-company-logo img {
            max-width: 200px;
            height: auto;
            filter: drop-shadow(3px 3px 6px rgba(0, 0, 0, 0.15));
            transition: transform 0.3s ease;
        }

        .userinfo-company-logo a:hover img {
            transform: scale(1.05);
        }

        /* Footer - Visit Website Button */
        .userinfo-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
            margin-top: 30px;
        }

        .visit-website-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 14px 32px;
            background: #FEBE1E;
            color: #000000;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Open Sans', Arial, sans-serif;
            border: 2px solid #FEBE1E;
            box-shadow: 6px 6px 9px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .visit-website-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #000000;
            transition: left 0.3s ease;
            z-index: 0;
        }

        .visit-website-btn:hover::before {
            left: 0;
        }

        .visit-website-btn:hover {
            color: #ffffff;
            border-color: #000000;
            transform: translateY(-2px);
            box-shadow: 8px 8px 12px rgba(0, 0, 0, 0.3);
        }

        .visit-website-btn span,
        .visit-website-btn svg {
            position: relative;
            z-index: 1;
        }

        .agent-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .external-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.8;
        }

        .visit-website-btn:hover .external-icon {
            opacity: 1;
            transform: translateX(2px) translateY(-2px);
            transition: all 0.3s ease;
        }

        /* Prize List Modal Styles */
        .prizelist-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .prizelist-modal-content {
            background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
            margin: 3% auto;
            padding: 0;
            border: none;
            border-radius: 20px;
            width: 90%;
            max-width: 900px;
            height: 80vh;
            max-height: 600px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
            animation: slideDown 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .prizelist-close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #4B5563;
            font-size: 32px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(0, 0, 0, 0.06);
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            z-index: 10;
            border: none;
            outline: none;
        }

        .prizelist-close:hover,
        .prizelist-close:focus {
            color: #EF4444;
            background: rgba(239, 68, 68, 0.1);
            transform: rotate(90deg) scale(1.1);
        }

        .prizelist-title {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            margin: 0;
            padding: 20px;
            font-weight: bold;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            border-radius: 20px 20px 0 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .prizelist-container {
            padding: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            overflow-y: auto;
            flex: 1;
        }

        .prize-item {
            background: white;
            border-radius: 16px;
            padding: 20px 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 2px solid #e5e7eb;
            border-right: 2px solid #e5e7eb;
            border-bottom: 2px solid #e5e7eb;
            border-top: 4px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 120px;
            position: relative;
            overflow: hidden;
        }

        .prize-item:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
        }

        .prize-gold {
            border-top-color: #FFD700;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 15%, #ffffff 100%);
        }

        .prize-gold:hover {
            box-shadow: 0 12px 32px rgba(255, 215, 0, 0.35);
        }

        .prize-silver {
            border-top-color: #9CA3AF;
            background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 15%, #ffffff 100%);
        }

        .prize-silver:hover {
            box-shadow: 0 12px 32px rgba(156, 163, 175, 0.35);
        }

        .prize-bronze {
            border-top-color: #D97706;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 15%, #ffffff 100%);
        }

        .prize-bronze:hover {
            box-shadow: 0 12px 32px rgba(217, 119, 6, 0.35);
        }

        .prize-standard {
            border-top-color: #3B82F6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 15%, #ffffff 100%);
        }

        .prize-standard:hover {
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.35);
        }

        .prize-consolation {
            border-top-color: #10B981;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 15%, #ffffff 100%);
        }

        .prize-consolation:hover {
            box-shadow: 0 12px 32px rgba(16, 185, 129, 0.35);
        }

        .prize-rank {
            font-size: 15px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 10px;
            line-height: 1.3;
            letter-spacing: 0.3px;
        }

        .prize-amount {
            font-size: 22px;
            font-weight: 700;
            color: #059669;
            margin: 10px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            letter-spacing: 0.5px;
        }

        .prize-gold .prize-amount {
            color: #D97706;
            font-size: 24px;
        }

        .prize-silver .prize-amount {
            color: #6B7280;
            font-size: 23px;
        }

        .prize-bronze .prize-amount {
            color: #EA580C;
            font-size: 22px;
        }

        .prize-details {
            font-size: 13px;
            color: #4B5563;
            margin-top: 8px;
            line-height: 1.4;
            font-weight: 500;
        }

        .prizelist-note {
            background: #fff9e6;
            border-top: 2px solid #FFD700;
            padding: 15px 20px;
            text-align: center;
            font-size: 12px;
            line-height: 1.5;
            color: #2c3e50;
            flex-shrink: 0;
            border-radius: 0 0 20px 20px;
        }

        .prizelist-note strong {
            color: #FF8C00;
            font-size: 13px;
        }

        /* Scrollbar styling */
        .prizelist-container::-webkit-scrollbar {
            width: 8px;
        }

        .prizelist-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .prizelist-container::-webkit-scrollbar-thumb {
            background: #FFD700;
            border-radius: 10px;
        }

        .prizelist-container::-webkit-scrollbar-thumb:hover {
            background: #FFA500;
        }

        /* Tablet/Large Mobile Responsive (iPad, large phones landscape) */
        @media (max-width: 1024px) and (min-width: 769px) {
            .prizelist-modal-content {
                width: 90%;
                max-width: 750px;
                height: 80vh;
                max-height: 580px;
            }

            .prizelist-container {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 12px;
            }

            .prize-item {
                min-height: 110px;
            }

            .prize-rank {
                font-size: 12px;
            }

            .prize-amount {
                font-size: 17px;
            }
        }

        /* Mobile/Tablet Portrait (most phones and small tablets) */
        @media (max-width: 768px) {
            .prizelist-modal-content {
                width: 96%;
                margin: 3% auto;
                height: 88vh;
                max-height: 600px;
            }

            .prizelist-title {
                font-size: 22px;
                padding: 16px;
            }

            .prizelist-close {
                top: 8px;
                right: 8px;
                width: 40px;
                height: 40px;
                font-size: 28px;
            }

            .prizelist-container {
                padding: 18px 12px;
                grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
                gap: 12px;
            }

            .prize-item {
                padding: 16px 12px;
                min-height: 110px;
                border-radius: 14px;
            }

            .prize-rank {
                font-size: 13px;
                margin-bottom: 8px;
            }

            .prize-amount {
                font-size: 18px;
                margin: 8px 0;
            }

            .prize-gold .prize-amount {
                font-size: 20px;
            }

            .prize-silver .prize-amount {
                font-size: 19px;
            }

            .prize-bronze .prize-amount {
                font-size: 18px;
            }

            .prize-details {
                font-size: 11px;
                margin-top: 5px;
            }

            .prizelist-note {
                padding: 14px 16px;
                font-size: 11px;
            }

            .prizelist-note strong {
                font-size: 12px;
            }
        }

        /* Small Mobile Phones */
        @media (max-width: 480px) {
            .prizelist-modal-content {
                width: 98%;
                margin: 2% auto;
                height: 90vh;
                max-height: 550px;
            }

            .prizelist-title {
                font-size: 20px;
                padding: 14px 12px;
            }

            .prizelist-close {
                top: 8px;
                right: 8px;
                width: 44px;
                height: 44px;
                font-size: 26px;
            }

            .prizelist-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                padding: 16px 10px;
            }

            .prize-item {
                min-height: 100px;
                padding: 14px 10px;
                border-radius: 12px;
            }

            .prize-rank {
                font-size: 12px;
                margin-bottom: 6px;
            }

            .prize-amount {
                font-size: 16px;
                margin: 6px 0;
            }

            .prize-gold .prize-amount {
                font-size: 18px;
            }

            .prize-silver .prize-amount {
                font-size: 17px;
            }

            .prize-bronze .prize-amount {
                font-size: 16px;
            }

            .prize-details {
                font-size: 10px;
                margin-top: 5px;
            }

            .prizelist-note {
                padding: 12px 14px;
                font-size: 10px;
            }

            .prizelist-note strong {
                font-size: 11px;
            }
        }

        /* Extra Small Mobile (iPhone SE, small Androids) */
        @media (max-width: 375px) {
            .prizelist-modal-content {
                width: 98%;
                height: 90vh;
                max-height: 520px;
            }

            .prizelist-title {
                font-size: 19px;
                padding: 12px 10px;
            }

            .prizelist-close {
                top: 6px;
                right: 6px;
                width: 44px;
                height: 44px;
                font-size: 24px;
            }

            .prizelist-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                padding: 14px 8px;
            }

            .prize-item {
                min-height: 95px;
                padding: 12px 8px;
                border-radius: 12px;
            }

            .prize-rank {
                font-size: 11px;
                margin-bottom: 5px;
            }

            .prize-amount {
                font-size: 15px;
                margin: 5px 0;
            }

            .prize-gold .prize-amount {
                font-size: 17px;
            }

            .prize-silver .prize-amount {
                font-size: 16px;
            }

            .prize-bronze .prize-amount {
                font-size: 15px;
            }

            .prize-details {
                font-size: 10px;
                margin-top: 4px;
            }

            .prizelist-note {
                padding: 10px 12px;
                font-size: 10px;
                line-height: 1.4;
            }

            .prizelist-note strong {
                font-size: 10px;
            }
        }

        /* Landscape Orientation Support */
        @media (max-height: 600px) and (orientation: landscape) {
            .prizelist-modal-content {
                height: 95vh;
                max-height: none;
                width: 85%;
                max-width: 850px;
            }

            .prizelist-title {
                font-size: 20px;
                padding: 12px 20px;
            }

            .prizelist-container {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 10px;
                padding: 12px 16px;
            }

            .prize-item {
                min-height: 85px;
                padding: 10px 8px;
            }

            .prize-rank {
                font-size: 11px;
            }

            .prize-amount {
                font-size: 14px;
                margin: 6px 0;
            }

            .prize-gold .prize-amount {
                font-size: 16px;
            }

            .prize-details {
                font-size: 9px;
                margin-top: 4px;
            }

            .prizelist-note {
                padding: 10px 16px;
                font-size: 10px;
            }

            .prizelist-close {
                font-size: 26px;
                padding: 6px 12px;
            }
        }

        /* Extra Small Landscape (like iPhone SE landscape) */
        @media (max-height: 400px) and (orientation: landscape) {
            .prizelist-modal-content {
                height: 98vh;
                margin: 1% auto;
            }

            .prizelist-title {
                font-size: 18px;
                padding: 10px 16px;
            }

            .prizelist-container {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 8px;
                padding: 10px 12px;
            }

            .prize-item {
                min-height: 80px;
                padding: 8px 6px;
            }

            .prize-rank {
                font-size: 10px;
            }

            .prize-amount {
                font-size: 13px;
                margin: 4px 0;
            }

            .prize-gold .prize-amount {
                font-size: 15px;
            }

            .prize-details {
                font-size: 8px;
                margin-top: 3px;
            }

            .prizelist-note {
                padding: 8px 12px;
                font-size: 9px;
            }
        }

        /* Container & Layout - Glassmorphism */
        .userinfo-tabs-container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(30px) saturate(200%);
            -webkit-backdrop-filter: blur(30px) saturate(200%);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37),
                        0 2px 8px 0 rgba(255, 255, 255, 0.1),
                        inset 0 1px 0 0 rgba(255, 255, 255, 0.6);
            overflow: hidden;
            position: relative;
            font-family: 'Open Sans', Arial, sans-serif;
        }

        /* Tab Navigation - Glassmorphism */
        .userinfo-tab-navigation {
            display: flex;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0;
            margin: 0;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .userinfo-tab-btn {
            flex: 1;
            padding: 18px 24px;
            border: none;
            outline: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .userinfo-tab-btn:focus {
            outline: none !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            box-shadow: none !important;
        }

        /* Ripple Effect on Tab Click */
        .userinfo-tab-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(254, 190, 30, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .userinfo-tab-btn:active::before {
            width: 300px;
            height: 300px;
        }

        .userinfo-tab-btn:hover {
            background: #FEBE1E;
            color: #000000;
        }

        .userinfo-tab-btn.active {
            background: #FEBE1E;
            color: #000000;
            border-bottom-color: #000000;
        }

        .tab-icon {
            font-size: 22px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .userinfo-tab-btn:hover .tab-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .userinfo-tab-btn.active .tab-icon {
            transform: scale(1.1);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Content Wrapper - Glassmorphism */
        .userinfo-tab-content-wrapper {
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            min-height: 400px;
            position: relative;
        }

        /* Tab Content with Advanced Animations */
        .userinfo-tab-content {
            display: none;
            opacity: 0;
            transform: translateX(-30px) scale(0.97);
        }

        .userinfo-tab-content.active {
            display: block;
            animation: slideInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .userinfo-tab-content.slide-out {
            animation: slideOutScale 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes slideInScale {
            0% {
                opacity: 0;
                transform: translateX(-30px) scale(0.97);
            }
            60% {
                transform: translateX(5px) scale(1.01);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes slideOutScale {
            0% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateX(30px) scale(0.97);
            }
        }

        /* Modal-Style Container for Registration Tab */
        .userinfo-modal-style-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 500px;
            padding: 20px 0;
        }

        .userinfo-modal-style-content {
            background: transparent;
            border-radius: 20px;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            box-shadow: none;
            position: relative;
            border: none;
        }

        /* Override form container styles for modal-style content */
        .userinfo-modal-style-content .userinfo-form-container,
        .userinfo-modal-style-content .userinfo-check-container {
            background: #ffffff;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            border: none;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        /* Results container keeps its original styling */
        .userinfo-modal-style-content .userinfo-results-container {
            background: #ffffff;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            /* Keep original border-radius and box-shadow */
        }

        /* Ensure title and countdown are inside the modal-style card */
        .userinfo-modal-style-content .userinfo-title-countdown-wrapper {
            margin-bottom: 20px;
        }

        .userinfo-modal-style-content .userinfo-registration-title h2 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin: 0 0 20px 0;
        }

        .userinfo-modal-style-content .userinfo-welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }

        .userinfo-modal-style-content .userinfo-welcome-message p {
            color: #34495e;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Form closed message styling */
        .userinfo-modal-style-content .userinfo-form-closed {
            text-align: center;
            padding: 40px 20px;
        }

        .userinfo-modal-style-content .userinfo-form-closed .closed-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .userinfo-modal-style-content .userinfo-form-closed .closed-title {
            color: #e74c3c;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .userinfo-modal-style-content .userinfo-form-closed .closed-message {
            color: #555;
            font-size: 16px;
            line-height: 1.8;
        }

        /* Agent9w.com Form Styling */
        .userinfo-tab-content .userinfo-form-container,
        .userinfo-tab-content .userinfo-check-container {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37),
                        inset 0 1px 1px 0 rgba(255, 255, 255, 0.5);
        }

        /* Two-Column Grid Layout for Form Fields */
        .userinfo-tab-content form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .userinfo-tab-content .form-group {
            margin-bottom: 0;
            position: relative;
        }

        /* Full-width fields */
        .userinfo-tab-content .form-group.full-width {
            grid-column: 1 / -1;
        }

        .userinfo-tab-content label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #000000;
            font-size: 15px;
            letter-spacing: 0.3px;
            text-shadow: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .userinfo-tab-content input[type="text"],
        .userinfo-tab-content input[type="email"],
        .userinfo-tab-content input[type="tel"],
        .userinfo-tab-content input[type="file"] {
            width: 100%;
            padding: 12px 16px;
            height: 44px;
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            color: #000000;
            box-sizing: border-box;
            font-family: 'Open Sans', Arial, sans-serif;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .userinfo-tab-content input[type="text"]::placeholder,
        .userinfo-tab-content input[type="email"]::placeholder,
        .userinfo-tab-content input[type="tel"]::placeholder {
            color: #666666;
            font-weight: 400;
            opacity: 1;
        }

        .userinfo-tab-content input[type="text"]:focus,
        .userinfo-tab-content input[type="email"]:focus,
        .userinfo-tab-content input[type="tel"]:focus {
            border-color: #FEBE1E;
            background: #ffffff;
            outline: none;
            box-shadow: 0 0 20px rgba(254, 190, 30, 0.6),
                        0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .userinfo-tab-content input[type="file"] {
            padding: 12px;
            cursor: pointer;
        }

        .userinfo-tab-content input[type="file"]:hover {
            border-color: #667eea;
            background: #ffffff;
        }

        .userinfo-tab-content small {
            display: block;
            margin-top: 6px;
            color: #6c757d;
            font-size: 13px;
            font-style: italic;
        }

        .userinfo-tab-content button[type="submit"],
        .userinfo-tab-content .userinfo-tab-content button {
            width: 100%;
            min-width: 215px;
            padding: 14px 32px;
            background: #FEBE1E;
            color: #000000;
            border: 2px solid #FEBE1E;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: none;
            letter-spacing: 0.3px;
            margin-top: 20px;
            font-family: 'Open Sans', Arial, sans-serif;
            box-shadow: 6px 6px 9px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .userinfo-tab-content button[type="submit"]::before,
        .userinfo-tab-content .userinfo-tab-content button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #000000;
            transition: left 0.3s ease;
            z-index: 0;
        }

        .userinfo-tab-content button[type="submit"]:hover::before,
        .userinfo-tab-content .userinfo-tab-content button:hover::before {
            left: 0;
        }

        .userinfo-tab-content button[type="submit"]:hover,
        .userinfo-tab-content .userinfo-tab-content button:hover {
            color: #ffffff;
            border-color: #000000;
            transform: translateY(-2px);
            box-shadow: 8px 8px 12px rgba(0, 0, 0, 0.3);
        }

        .userinfo-tab-content button[type="submit"] > *,
        .userinfo-tab-content .userinfo-tab-content button > * {
            position: relative;
            z-index: 1;
        }

        .userinfo-tab-content button[type="submit"]:focus,
        .userinfo-tab-content .userinfo-tab-content button:focus {
            outline: none;
            box-shadow: 0px 0px 5px 0px rgba(254, 190, 31, 0.6);
        }

        .userinfo-tab-content button[type="submit"]:active,
        .userinfo-tab-content .userinfo-tab-content button:active {
            transform: translateY(-1px);
        }

        /* Success & Error Messages - Agent9w.com Style */
        .userinfo-tab-content .userinfo-success,
        .userinfo-tab-content .userinfo-errors {
            border-radius: 2px;
            padding: 15px 20px;
            margin-bottom: 20px;
            animation: slideDown 0.4s ease-out;
            border: 1px solid;
            font-weight: 600;
        }

        .userinfo-tab-content .userinfo-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .userinfo-tab-content .userinfo-errors {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading State */
        .userinfo-tab-content button[disabled] {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            /* Stack form fields vertically on mobile */
            .userinfo-tab-content form {
                grid-template-columns: 1fr;
            }

            .userinfo-page-wrapper {
                padding: 15px;
            }

            /* Countdown responsive styles removed - now inline with title */

            .countdown-days-value {
                font-size: 24px;
            }

            .countdown-days-label {
                font-size: 12px;
            }

            .userinfo-company-logo {
                padding: 0;
                margin-bottom: 0;
            }

            .userinfo-company-logo img {
                max-width: 150px;
            }

            .userinfo-footer {
                padding: 25px 15px;
                margin-top: 25px;
            }

            .visit-website-btn {
                padding: 12px 28px;
                font-size: 15px;
                gap: 10px;
            }

            .agent-icon {
                width: 22px;
                height: 22px;
            }

            .external-icon {
                width: 14px;
                height: 14px;
            }

            .userinfo-tabs-container {
                border-radius: 12px;
            }

            .userinfo-tab-btn {
                padding: 16px 12px;
                font-size: 15px;
            }

            .tab-icon {
                font-size: 20px;
            }

            .userinfo-tab-content-wrapper {
                padding: 24px 16px;
            }

            .userinfo-tab-content .userinfo-form-container,
            .userinfo-tab-content .userinfo-check-container {
                padding: 24px 16px;
            }

            /* Modal-style container responsive */
            .userinfo-modal-style-container {
                padding: 10px 0;
                min-height: 400px;
            }

            .userinfo-modal-style-content {
                padding: 30px 20px;
                border-radius: 16px;
            }

            .userinfo-modal-style-content .userinfo-registration-title h2 {
                font-size: 24px;
            }

            .userinfo-modal-style-content .userinfo-welcome-message p {
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .userinfo-page-wrapper {
                padding: 10px;
            }

            /* Small screen countdown styles - now inline with title */
            .countdown-days-value {
                font-size: 20px;
            }

            .countdown-days-label {
                font-size: 10px;
            }

            .countdown-days-container {
                gap: 5px;
            }

            .userinfo-company-logo {
                padding: 0;
                margin-bottom: 0;
            }

            .userinfo-company-logo img {
                max-width: 120px;
            }

            .userinfo-footer {
                padding: 20px 10px;
                margin-top: 20px;
            }

            .visit-website-btn {
                padding: 12px 24px;
                font-size: 14px;
                gap: 8px;
            }

            .agent-icon {
                width: 20px;
                height: 20px;
            }

            .external-icon {
                width: 12px;
                height: 12px;
            }

            .userinfo-tab-btn {
                flex-direction: column;
                gap: 6px;
                padding: 14px 8px;
                font-size: 13px;
            }

            .tab-icon {
                font-size: 26px;
            }

            .userinfo-tab-content-wrapper {
                padding: 20px 12px;
            }

            .userinfo-tab-content .userinfo-form-container,
            .userinfo-tab-content .userinfo-check-container {
                padding: 20px 12px;
            }

            /* Modal-style container for small screens */
            .userinfo-modal-style-container {
                padding: 5px 0;
                min-height: 350px;
            }

            .userinfo-modal-style-content {
                padding: 24px 16px;
                border-radius: 12px;
            }

            .userinfo-modal-style-content .userinfo-registration-title h2 {
                font-size: 20px;
            }

            .userinfo-modal-style-content .userinfo-welcome-message p {
                font-size: 14px;
            }
        }
    </style>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        var modal = document.getElementById('prizelist-modal');
        var btn = document.getElementById('prizelist-btn');
        var closeBtn = document.getElementsByClassName('prizelist-close')[0];

        // Open modal when button is clicked
        if (btn) {
            btn.onclick = function(e) {
                e.preventDefault();
                if (modal) {
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                }
            }
        }

        // Close modal when X is clicked
        if (closeBtn) {
            closeBtn.onclick = function() {
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Restore scrolling
                }
            }
        }

        // Close modal when clicking outside of modal content
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restore scrolling
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal && modal.style.display === 'block') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restore scrolling
            }
        });
    });
    </script>

    <!-- Global Success/Error Modal -->
    <div id="userinfo-modal" class="userinfo-modal" style="display: none;">
        <div class="userinfo-modal-overlay"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="userinfo-modal-content">
                <button class="userinfo-modal-close">&times;</button>
                <div class="userinfo-modal-icon">
                    <span class="userinfo-modal-icon-success">✓</span>
                    <span class="userinfo-modal-icon-error">✗</span>
                </div>
                <h3 class="userinfo-modal-title"></h3>
                <div class="userinfo-modal-body"></div>
                <button class="userinfo-modal-btn"><?php _e('OK', 'userinfo-manager'); ?></button>
            </div>
        </div>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('userinfo_tabs', 'userinfo_tabs_shortcode');

/**
 * Hide header and footer when userinfo_tabs shortcode is used
 * DISABLED: Commented out to allow site header and footer to display
 */
if (false) {
function userinfo_hide_header_footer() {
    global $post;

    // Check if we're on a single page/post with the shortcode
    if (is_singular() && is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'userinfo_tabs')) {
        // Add body class for styling
        add_filter('body_class', 'userinfo_add_no_header_footer_class');

        // Add custom template filter
        add_filter('template_include', 'userinfo_blank_template');
    }
}
add_action('wp', 'userinfo_hide_header_footer');
}

/**
 * Add custom body class when shortcode is used
 * DISABLED: Not needed when header/footer are displayed
 */
if (false) {
function userinfo_add_no_header_footer_class($classes) {
    $classes[] = 'userinfo-fullwidth-page';
    $classes[] = 'no-header';
    $classes[] = 'no-footer';
    return $classes;
}
}

/**
 * Use blank template for pages with userinfo_tabs shortcode
 * DISABLED: Not needed when header/footer are displayed
 */
if (false) {
function userinfo_blank_template($template) {
    global $post;

    if (is_singular() && is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'userinfo_tabs')) {
        // Output blank template directly
        userinfo_output_blank_template();
        exit;
    }

    return $template;
}
}

/**
 * Output blank template HTML without header/footer
 * DISABLED: Not needed when header/footer are displayed
 */
if (false) {
function userinfo_output_blank_template() {
    global $post;

    // Set up post data
    setup_postdata($post);
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
        <style>
            /* Remove default WordPress admin bar spacing */
            html { margin-top: 0 !important; }
            * html body { margin-top: 0 !important; }

            /* Full width layout */
            body.userinfo-fullwidth-page {
                margin: 0;
                padding: 20px;
                background: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FF8C00 100%);
                min-height: 100vh;
            }

            /* Hide all header and footer elements */
            .userinfo-fullwidth-page .site-header,
            .userinfo-fullwidth-page header,
            .userinfo-fullwidth-page .site-footer,
            .userinfo-fullwidth-page footer,
            .userinfo-fullwidth-page .site-navigation,
            .userinfo-fullwidth-page nav,
            .userinfo-fullwidth-page .header,
            .userinfo-fullwidth-page .footer,
            .userinfo-fullwidth-page #header,
            .userinfo-fullwidth-page #footer,
            .userinfo-fullwidth-page #masthead,
            .userinfo-fullwidth-page #colophon {
                display: none !important;
            }

            /* Center the content */
            .userinfo-fullwidth-page .site-content,
            .userinfo-fullwidth-page main,
            .userinfo-fullwidth-page .content-area,
            .userinfo-fullwidth-page #content,
            .userinfo-fullwidth-page #primary {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 20px;
            }

            /* Remove any extra padding/margin */
            .userinfo-fullwidth-page .entry-content,
            .userinfo-fullwidth-page .page-content {
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body <?php body_class('userinfo-fullwidth-page'); ?>>
        <?php
        // Output the post content with the shortcode
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <div class="userinfo-blank-wrapper">
                    <?php the_content(); ?>
                </div>
                <?php
            }
        }
        wp_reset_postdata();
        ?>
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
}
}

