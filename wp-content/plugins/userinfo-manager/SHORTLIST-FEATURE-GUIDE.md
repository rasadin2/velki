# Monthly Shortlist Feature - Complete Guide

## Overview
The Monthly Shortlist feature allows administrators to select specific users from the main UserInfo list and organize them into monthly shortlists. This feature is perfect for managing selected participants, winners, or any monthly user selection process.

## Features

### 1. **Shortlist Toggle Button**
- Located in the "Shortlist" column on the main User Info admin page
- Toggle button with blue color when selected
- Automatically records the month when a user is added to the shortlist
- Displays the selection month below the status label

### 2. **Selected Users Menu**
- New submenu under "User Info" → "Selected Users"
- Displays all shortlisted users in a clean table format
- Shows all user data columns identical to the main list
- Month-based filtering capability
- CSV export functionality

### 3. **Month Filtering**
- Dropdown filter to view shortlisted users by specific month
- Format: "Month Year" (e.g., "December 2025")
- "All Months" option to view all shortlisted users
- "Clear" button to reset filter

### 4. **Edit Functionality**
- Each user has an "Edit" button in the Selected Users list
- Clicking edit opens the user's full edit page
- All user fields can be modified from the edit page
- Changes are reflected immediately in both main list and selected users list

### 5. **CSV Export**
- Export button available on Selected Users page
- Exports data based on current filter selection
- Filename includes month if filtered (e.g., `userinfo-shortlist-2025-12-timestamp.csv`)
- Includes all user data: Full Name, Username, Registration ID, Agent ID, Phone, Email, Valid Status, Selected Month, Submitted Date

## How to Use

### Adding Users to Shortlist

1. Navigate to **User Info** → **All User Info** in WordPress admin
2. Locate the user you want to add to the shortlist
3. Find the **"Shortlist"** column
4. Click the toggle switch to turn it **ON** (blue)
5. The status will change to **"Selected"** and show the current month
6. The user is now added to the monthly shortlist

### Removing Users from Shortlist

1. Go to **User Info** → **All User Info**
2. Find the user with "Selected" status
3. Click the toggle switch to turn it **OFF** (gray)
4. The status changes to **"Not Selected"**
5. The user is removed from the shortlist

### Viewing Selected Users

1. Navigate to **User Info** → **Selected Users**
2. You'll see a table with all shortlisted users
3. The table displays:
   - Full Name
   - Username
   - Registration ID
   - Agent ID
   - Phone Number
   - Email Address
   - Valid Status (Valid/Invalid)
   - Selected Month (Month and Year when added to shortlist)
   - Submitted Date
   - Actions (Edit button)

### Filtering by Month

1. On the **Selected Users** page, find the **"Filter by Month"** dropdown
2. Select the desired month from the dropdown
3. Click **"Apply"** button
4. The list will show only users selected in that month
5. Click **"Clear"** to show all months again

### Editing Shortlisted Users

1. On the **Selected Users** page, find the user you want to edit
2. Click the **"Edit"** button in the Actions column
3. You'll be taken to the full edit page
4. Make your changes to any field
5. Click **"Update"** to save changes
6. Return to **Selected Users** to see updated information

### Exporting Shortlisted Users to CSV

#### Export All Shortlisted Users:
1. Go to **User Info** → **Selected Users**
2. Click **"Export to CSV"** button (blue button)
3. A CSV file will download automatically
4. Filename: `userinfo-shortlist-export-YYYY-MM-DD-HHMMSS.csv`

#### Export Specific Month:
1. Go to **User Info** → **Selected Users**
2. Select a month from the **"Filter by Month"** dropdown
3. Click **"Apply"**
4. Click **"Export to CSV"** button
5. Only users from that month will be exported
6. Filename: `userinfo-shortlist-YYYY-MM-YYYY-MM-DD-HHMMSS.csv`

## Technical Details

### Database Structure
The shortlist feature uses two custom meta fields:

1. **`_userinfo_shortlisted`** (string: '0' or '1')
   - '1' = User is on the shortlist
   - '0' = User is not on the shortlist

2. **`_userinfo_shortlist_month`** (string: 'YYYY-MM')
   - Stores the month when user was added to shortlist
   - Format example: '2025-12'
   - Automatically set when toggle is turned on
   - Deleted when toggle is turned off

### AJAX Operations
The shortlist toggle uses WordPress AJAX for smooth operation:

- **Action**: `userinfo_toggle_shortlist_status`
- **Nonce**: `userinfo_toggle_shortlist`
- **Security**: Permission check for `edit_posts` capability
- **Response**: Returns new status, month, and success message

### CSS Classes

#### Shortlist Toggle Switch:
```css
.userinfo-shortlist-toggle     /* Toggle container */
.userinfo-shortlist-label      /* Status label */
.shortlisted                   /* Blue text for selected */
.not-shortlisted               /* Gray text for not selected */
```

#### Colors:
- Selected toggle: `#0073aa` (WordPress blue)
- Selected label: `#0073aa` (WordPress blue)
- Not selected label: `#999` (Gray)

### WordPress Filters & Actions
```php
add_action('admin_menu', 'userinfo_add_selected_users_menu');
add_action('wp_ajax_userinfo_toggle_shortlist_status', 'userinfo_ajax_toggle_shortlist_status');
add_action('admin_post_userinfo_export_shortlist_csv', 'userinfo_export_shortlist_csv');
```

## Workflow Example

### Monthly Selection Process:

1. **Beginning of Month** (e.g., December 1)
   - Review all submitted users for November
   - Toggle ON users who meet selection criteria
   - All selected users are automatically tagged with "2025-12"

2. **Mid-Month Review**
   - Go to Selected Users page
   - Filter by "December 2025"
   - Review all selected users
   - Edit user details if needed
   - Remove users if necessary (toggle OFF)

3. **End of Month**
   - Filter by "December 2025"
   - Export selected users to CSV
   - File includes all December selections
   - Archive or process the CSV file

4. **Next Month** (January)
   - Start new selection process
   - Users selected in January will be tagged "2025-01"
   - Can still view/export previous months anytime

## CSV Export Format

The exported CSV includes the following columns in order:

1. Full Name
2. Username
3. Registration ID
4. Agent ID
5. Phone Number
6. Email Address
7. Submitted Date
8. Post Date
9. Valid Member (Valid/Invalid)

## Permissions

- **Required Capability**: `edit_posts`
- **Affected Users**: WordPress administrators and editors
- **Frontend Access**: None (admin-only feature)

## Compatibility

- **WordPress Version**: 5.0+
- **PHP Version**: 7.0+
- **Browser**: Modern browsers with JavaScript enabled
- **Dependencies**: jQuery (WordPress core)

## Troubleshooting

### Toggle doesn't work:
- Check JavaScript console for errors
- Verify user has `edit_posts` permission
- Clear browser cache and try again

### Month not showing:
- Refresh the page
- Check if user was added via toggle (not manually via database)
- Verify `_userinfo_shortlist_month` meta field exists

### Export button not working:
- Check admin-ajax.php is accessible
- Verify nonce is valid
- Check browser console for JavaScript errors

### Filter not showing users:
- Verify users have `_userinfo_shortlist_month` set
- Check month format is correct (YYYY-MM)
- Ensure users have `_userinfo_shortlisted` = '1'

## Best Practices

1. **Regular Exports**: Export monthly data before starting new month selections
2. **Clear Criteria**: Establish selection criteria before toggling users
3. **Documentation**: Keep records of why users were selected/removed
4. **Backup**: Regular database backups before bulk operations
5. **Review**: Double-check selections before final export

## Future Enhancements (Potential)

- Bulk selection/deselection
- Email notifications to selected users
- Selection notes/comments
- Multi-admin selection tracking
- Selection history log
- Advanced filtering (by valid status, agent ID, etc.)

## Support

For issues or questions:
1. Check this documentation first
2. Review WordPress error logs
3. Check browser console for JavaScript errors
4. Verify database meta fields are set correctly

## Version History

- **Version 1.6.0** - Initial release of Monthly Shortlist Feature
  - Shortlist toggle in main admin list
  - Selected Users submenu page
  - Month-based filtering
  - CSV export for shortlisted users
  - Full edit capability from shortlist view
