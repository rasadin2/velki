<?php
/**
 * Generate 20 Test User Entries
 *
 * This script creates 20 random user entries for testing the shortlist features
 * with varied months, positions, and prizes.
 *
 * Usage: Run from WordPress admin or via WP-CLI
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user has permission
if (!current_user_can('manage_options')) {
    die('Permission denied. You must be an administrator to run this script.');
}

// Sample data arrays
$first_names = array(
    'Ahmed', 'Fatima', 'Mohammed', 'Aisha', 'Omar', 'Zainab', 'Ali', 'Khadija',
    'Hassan', 'Mariam', 'Ibrahim', 'Noor', 'Yusuf', 'Layla', 'Bilal', 'Amina',
    'Khalid', 'Sara', 'Abdullah', 'Huda', 'Rashid', 'Zahra', 'Tariq', 'Safiya'
);

$last_names = array(
    'Rahman', 'Ahmed', 'Khan', 'Hassan', 'Ali', 'Hussain', 'Shah', 'Mahmud',
    'Karim', 'Saleh', 'Ibrahim', 'Siddique', 'Malik', 'Farooq', 'Aziz', 'Haque'
);

$agent_prefixes = array('A', 'B', 'C', 'D', 'E');

$positions = array(
    '1st Place', '2nd Place', '3rd Place',
    'Winner', 'Runner-up', 'Finalist',
    'Champion', 'Best Performance', 'Most Improved',
    'Rising Star', 'Top Performer', 'Excellence Award'
);

$prizes = array(
    '$1000', '$500', '$300', '$200', '$100',
    'Gold Trophy', 'Silver Trophy', 'Bronze Medal',
    'Certificate + $250', 'iPad Pro', 'Gift Card $500',
    'Scholarship $2000', 'Gold Medal', 'Trophy + Certificate'
);

// Generate random date between 6 months ago and now
function random_date() {
    $start = strtotime('-6 months');
    $end = time();
    $timestamp = mt_rand($start, $end);
    return date('Y-m-d H:i:s', $timestamp);
}

// Generate random phone number (10 digits)
function random_phone() {
    return sprintf('01%d%d%d%d%d%d%d%d',
        mt_rand(0,9), mt_rand(0,9), mt_rand(0,9), mt_rand(0,9),
        mt_rand(0,9), mt_rand(0,9), mt_rand(0,9), mt_rand(0,9)
    );
}

// Generate random email
function random_email($username) {
    $domains = array('gmail.com', 'yahoo.com', 'outlook.com', 'email.com');
    return strtolower($username) . '@' . $domains[array_rand($domains)];
}

// Get next sequential registration ID
// This function is now available from the main plugin file
// We just need to ensure the plugin is loaded
if (!function_exists('userinfo_get_next_registration_id')) {
    die('Error: UserInfo Manager plugin must be active to generate test users.');
}

// Start output
echo "<h2>Generating 20 Test User Entries</h2>\n";
echo "<pre>\n";

$created_users = array();
$shortlisted_count = 0;

// Create 20 users
for ($i = 1; $i <= 20; $i++) {
    $first_name = $first_names[array_rand($first_names)];
    $last_name = $last_names[array_rand($last_names)];
    $full_name = $first_name . ' ' . $last_name;
    $username = strtolower($first_name . $last_name . mt_rand(10, 99));

    // Create post
    $post_data = array(
        'post_title'    => $full_name,
        'post_type'     => 'userinfo',
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
    );

    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Generate submitted date
        $submitted_date = random_date();

        // Generate registration ID using new sequential system
        $registration_id = userinfo_get_next_registration_id();

        // Save basic meta data
        update_post_meta($post_id, '_userinfo_full_name', $full_name);
        update_post_meta($post_id, '_userinfo_username', $username);
        update_post_meta($post_id, '_userinfo_registration_id', $registration_id);
        update_post_meta($post_id, '_userinfo_agent_id', $agent_prefixes[array_rand($agent_prefixes)] . sprintf('%02d', mt_rand(1, 99)));
        update_post_meta($post_id, '_userinfo_phone_number', random_phone());
        update_post_meta($post_id, '_userinfo_email', random_email($username));
        update_post_meta($post_id, '_userinfo_submitted_date', $submitted_date);

        // 90% valid, 10% invalid
        $is_valid = (mt_rand(1, 10) <= 9) ? '1' : '0';
        update_post_meta($post_id, '_userinfo_is_valid', $is_valid);

        // 60% chance of being shortlisted
        $is_shortlisted = (mt_rand(1, 10) <= 6) ? '1' : '0';

        if ($is_shortlisted == '1') {
            $shortlisted_count++;

            // Random month from last 6 months
            $months_ago = mt_rand(0, 5);
            $shortlist_date = date('Y-m', strtotime("-{$months_ago} months"));

            update_post_meta($post_id, '_userinfo_shortlisted', '1');
            update_post_meta($post_id, '_userinfo_shortlist_month', $shortlist_date);

            // 70% chance of having position and prize
            if (mt_rand(1, 10) <= 7) {
                update_post_meta($post_id, '_userinfo_position', $positions[array_rand($positions)]);
                update_post_meta($post_id, '_userinfo_prize', $prizes[array_rand($prizes)]);
            }

            $status = "SHORTLISTED ({$shortlist_date})";
        } else {
            update_post_meta($post_id, '_userinfo_shortlisted', '0');
            $status = "Not Shortlisted";
        }

        $created_users[] = array(
            'id' => $post_id,
            'name' => $full_name,
            'registration_id' => $registration_id,
            'status' => $status,
            'valid' => $is_valid == '1' ? 'Valid' : 'Invalid'
        );

        echo sprintf(
            "%02d. ✓ Created: %s (ID: %d, Reg: %s) - %s - %s\n",
            $i,
            $full_name,
            $post_id,
            $registration_id,
            $status,
            $is_valid == '1' ? 'Valid' : 'Invalid'
        );
    } else {
        echo sprintf("%02d. ✗ Failed to create user\n", $i);
    }

    // Small delay to vary registration IDs
    usleep(100);
}

echo "\n";
echo "========================================\n";
echo "Summary:\n";
echo "========================================\n";
echo "Total Users Created: " . count($created_users) . "\n";
echo "Shortlisted Users: " . $shortlisted_count . "\n";
echo "Not Shortlisted: " . (count($created_users) - $shortlisted_count) . "\n";
echo "\n";
echo "You can now view these users at:\n";
echo "- All Users: wp-admin/edit.php?post_type=userinfo\n";
echo "- Selected Users: wp-admin/edit.php?post_type=userinfo&page=userinfo-selected\n";
echo "\n";
echo "</pre>\n";

echo "<h3>Generated Users</h3>\n";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>\n";
echo "<tr style='background: #f0f0f0;'>\n";
echo "<th>ID</th><th>Name</th><th>Registration ID</th><th>Status</th><th>Valid</th>\n";
echo "</tr>\n";

foreach ($created_users as $user) {
    $status_color = (strpos($user['status'], 'SHORTLISTED') !== false) ? '#0073aa' : '#999';
    $valid_color = ($user['valid'] == 'Valid') ? '#46b450' : '#dc3232';

    echo "<tr>\n";
    echo "<td>{$user['id']}</td>\n";
    echo "<td><strong>{$user['name']}</strong></td>\n";
    echo "<td>{$user['registration_id']}</td>\n";
    echo "<td style='color: {$status_color};'><strong>{$user['status']}</strong></td>\n";
    echo "<td style='color: {$valid_color};'><strong>{$user['valid']}</strong></td>\n";
    echo "</tr>\n";
}

echo "</table>\n";

echo "<p style='margin-top: 20px;'>\n";
echo "<strong>Next Steps:</strong><br>\n";
echo "1. Go to <a href='" . admin_url('edit.php?post_type=userinfo&page=userinfo-selected') . "'>Selected Users</a> to view shortlisted users<br>\n";
echo "2. Try filtering by different months<br>\n";
echo "3. Edit users to add/change position and prize<br>\n";
echo "4. Export to CSV to see all data<br>\n";
echo "</p>\n";
?>
