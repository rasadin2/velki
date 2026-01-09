<?php
/**
 * Test script for registration ID generation
 * Load WordPress environment and test the sequential ID system
 */

// Load WordPress
require_once('../../../wp-load.php');

// Test if function exists
if (!function_exists('userinfo_get_next_registration_id')) {
    die('Error: userinfo_get_next_registration_id() function not found. Ensure plugin is active.');
}

echo "<h2>Registration ID Generation Test</h2>\n";
echo "<pre>\n";

// Test 1: Generate 10 sequential IDs
echo "=== Test 1: Sequential Generation ===\n";
$ids = array();
for ($i = 1; $i <= 10; $i++) {
    $id = userinfo_get_next_registration_id();
    $ids[] = $id;
    echo "ID #{$i}: {$id}\n";
}

// Test 2: Check for duplicates
echo "\n=== Test 2: Duplicate Check ===\n";
$unique_ids = array_unique($ids);
if (count($ids) === count($unique_ids)) {
    echo "✅ PASS: No duplicates found\n";
} else {
    echo "❌ FAIL: Duplicate IDs detected!\n";
    echo "Total IDs: " . count($ids) . "\n";
    echo "Unique IDs: " . count($unique_ids) . "\n";
}

// Test 3: Check sequential increment
echo "\n=== Test 3: Sequential Increment Check ===\n";
$is_sequential = true;
for ($i = 1; $i < count($ids); $i++) {
    $expected = intval($ids[$i-1]) + 1;
    $actual = intval($ids[$i]);
    if ($expected !== $actual) {
        echo "❌ FAIL: Expected {$expected}, got {$actual}\n";
        $is_sequential = false;
        break;
    }
}
if ($is_sequential) {
    echo "✅ PASS: All IDs increment by 1\n";
}

// Test 4: Check starting value
echo "\n=== Test 4: Starting Value Check ===\n";
$first_id = intval($ids[0]);
if ($first_id >= 9000) {
    echo "✅ PASS: First ID ({$first_id}) starts from 9000 or higher\n";
} else {
    echo "⚠️  WARNING: First ID ({$first_id}) is below 9000\n";
    echo "   This is normal if you already generated IDs before.\n";
}

// Test 5: Check current counter value in database
echo "\n=== Test 5: Database Counter Value ===\n";
$current_counter = get_option('userinfo_registration_counter', 'Not set');
echo "Current counter in database: {$current_counter}\n";

// Summary
echo "\n=== Summary ===\n";
echo "First ID: {$ids[0]}\n";
echo "Last ID: {$ids[count($ids)-1]}\n";
echo "Total generated: " . count($ids) . "\n";
echo "All unique: " . (count($ids) === count($unique_ids) ? 'Yes' : 'No') . "\n";
echo "Sequential: " . ($is_sequential ? 'Yes' : 'No') . "\n";

echo "</pre>\n";
echo "<p><a href='" . admin_url() . "'>← Back to WordPress Admin</a></p>\n";
