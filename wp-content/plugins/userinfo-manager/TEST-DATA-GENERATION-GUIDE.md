# Test Data Generation Guide

## Overview
Two scripts are provided to generate 20 test user entries with random data for testing the shortlist features.

## Files Created

1. **`add-test-users.php`** (Root directory - Recommended)
   - Location: `C:\xampp\htdocs\formwp\add-test-users.php`
   - Browser-based with nice UI
   - Easy to use
   - Shows progress and summary

2. **`generate-test-users.php`** (Plugin directory)
   - Location: `C:\xampp\htdocs\formwp\wp-content\plugins\userinfo-manager\generate-test-users.php`
   - Alternative method
   - Same functionality

## Quick Start (Recommended Method)

### Step 1: Access the Script
Open your browser and go to:
```
http://localhost/formwp/add-test-users.php
```

### Step 2: Run the Script
- The script will automatically generate 20 users
- You'll see progress in real-time
- Summary table shows all created users

### Step 3: View Results
Click the buttons at the bottom:
- **View All Users** - See all 20 users in the main list
- **View Selected Users** - See shortlisted users
- **Go to Dashboard** - Return to WordPress admin

## What Gets Created

### User Distribution

**20 Total Users** with:
- **~12 Shortlisted** (60% chance)
- **~8 Not Shortlisted** (40% chance)
- **~18 Valid** (90% chance)
- **~2 Invalid** (10% chance)

### Random Data Generated

#### Basic User Info
```yaml
Full Name: Random combination (e.g., "Ahmed Rahman", "Fatima Khan")
Username: Lowercase name + random number (e.g., "ahmedrahman45")
Registration ID: Auto-generated format (MM## - e.g., "1201")
Agent ID: Random (A01-E99)
Phone: Random 10-digit number starting with 01
Email: username@email.com
Submitted Date: Random date within last 6 months
Valid Status: 90% Valid, 10% Invalid
```

#### Shortlist Data (for shortlisted users)
```yaml
Shortlisted: Yes (60% of users)
Shortlist Month: Random month from last 6 months
  Examples: 2025-12, 2025-11, 2025-10, etc.
```

#### Position and Prize (70% of shortlisted users)
```yaml
Position Options:
  - "1st Place", "2nd Place", "3rd Place"
  - "Winner", "Runner-up", "Finalist"
  - "Champion", "Best Performance", "Most Improved"
  - "Rising Star"

Prize Options:
  - "$1000", "$500", "$300", "$200"
  - "Gold Trophy", "Silver Trophy", "Bronze Medal"
  - "Certificate", "iPad Pro", "Gift Card"
```

## Sample Output

After running the script, you'll see something like:

```
01. ✓ Ahmed Rahman (ID: 123, Reg: 251201)
02. ✓ Fatima Khan (ID: 124, Reg: 251202)
03. ✓ Mohammed Hassan (ID: 125, Reg: 251203)
...
20. ✓ Huda Aziz (ID: 142, Reg: 251220)

Summary:
─────────────────────────
Total Users Created: 20
Shortlisted Users: 12
Not Shortlisted: 8
With Position/Prize: 8
```

## Expected Results

### In Main User Info List
You should see:
- All 20 users listed
- Shortlist column showing toggle status
- Some users with blue "Selected" status
- Some users with gray "Not Selected" status

### In Selected Users Page
You should see:
- ~12 shortlisted users
- Various months in the month filter dropdown
- Position and Prize columns populated for some users
- Blue position text and green prize text

### In CSV Export
Export will include:
- All 20 users (if exporting from main list)
- ~12 shortlisted users (if exporting from Selected Users)
- Position and Prize columns populated
- Varied month data

## Testing Scenarios

### Test 1: Month Filtering
```
1. Go to Selected Users page
2. Use "Filter by Month" dropdown
3. Select different months
4. Click "Apply"
5. Verify filtering works
```

### Test 2: Position and Prize Display
```
1. Go to Selected Users page
2. Look for users with position and prize
3. Verify blue color for Position
4. Verify green color for Prize
5. Edit a user to change position/prize
```

### Test 3: CSV Export
```
1. Go to Selected Users page
2. Filter by a specific month
3. Click "Export to CSV"
4. Open the file
5. Verify Position (column 7) and Prize (column 8)
```

### Test 4: Edit Functionality
```
1. Go to Selected Users page
2. Click "Edit" on any user
3. Scroll to "Shortlist Information" section
4. Modify Position and Prize
5. Click "Update"
6. Return to Selected Users and verify changes
```

## Cleanup (Optional)

### To Delete Test Users

**Method 1: One by One**
1. Go to User Info → All User Info
2. Hover over test user
3. Click "Trash"
4. Repeat for all test users

**Method 2: Bulk Delete**
1. Go to User Info → All User Info
2. Check boxes for all test users
3. Select "Move to Trash" from Bulk Actions
4. Click "Apply"

**Method 3: Delete All at Once (Advanced)**
Run this in WordPress database (phpMyAdmin):
```sql
-- WARNING: This deletes ALL userinfo posts
-- Only run if you want to delete everything
DELETE FROM wp_posts WHERE post_type = 'userinfo';
DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT ID FROM wp_posts);
```

## Troubleshooting

### Script doesn't run?
- Verify you're logged in as WordPress administrator
- Check URL: `http://localhost/formwp/add-test-users.php`
- Ensure XAMPP Apache and MySQL are running
- Check file exists in correct location

### "Permission denied" error?
- Log in to WordPress admin first
- Make sure your user has administrator role
- Try logging out and back in

### No users created?
- Check WordPress is installed correctly
- Verify userinfo post type is registered
- Check database connection
- Look at PHP error logs

### Users created but not showing?
- Refresh the page
- Clear browser cache
- Go to User Info → All User Info
- Check post_status is 'publish'

## Alternative Usage (Plugin Directory Method)

If you prefer the plugin directory script:

1. Navigate to:
```
http://localhost/formwp/wp-content/plugins/userinfo-manager/generate-test-users.php
```

2. Or copy to a page and access via WordPress admin

Both scripts create identical test data.

## Data Characteristics

### Realistic Patterns
- Names from common Bengali/South Asian names
- Phone numbers in Bangladesh format (01XXXXXXXX)
- Email addresses with common domains
- Registration IDs follow plugin's format
- Submitted dates spread over 6 months

### Variety for Testing
- Multiple months for filter testing
- Mix of shortlisted and not shortlisted
- Some with positions, some without
- Some with prizes, some without
- Valid and invalid statuses

### Perfect for Testing
- Month-based filtering
- Position and prize display
- CSV export functionality
- Edit page functionality
- Toggle operations
- Search and sorting

## Next Steps After Generation

1. **Explore Features**:
   - Try month filtering
   - Test position/prize editing
   - Export to CSV
   - Toggle shortlist status

2. **Test Workflows**:
   - Simulate monthly selection process
   - Practice assigning positions and prizes
   - Test export for different months

3. **Verify Everything Works**:
   - All columns display correctly
   - Colors show properly
   - CSV exports include all data
   - Edit functionality works

## Tips

- Run script multiple times to get different data
- Each run creates 20 new users (not duplicates)
- Delete test data when done testing
- Use for training or demonstrations
- Perfect for screenshots and documentation

## Notes

- Test data is randomly generated each time
- No duplicate usernames (random number suffix)
- Registration IDs auto-increment correctly
- All WordPress security checks in place
- Safe to run multiple times

---

**Script Status**: ✅ Ready to Use
**Location**: `C:\xampp\htdocs\formwp\add-test-users.php`
**Usage**: Open in browser while logged in as admin
