# Bug Fix: Month Filter "Apply" Button Permission Error

## Issue
When clicking the "Apply" button on the Selected Users page month filter, users received the error:
```
Sorry, you are not allowed to access this page.
```

## Root Cause
The JavaScript code was constructing the redirect URL incorrectly:

**Before (Incorrect):**
```javascript
var url = window.location.href.split('?')[0] + '?page=userinfo-selected';
```

**Problem:**
- `window.location.href.split('?')[0]` removes query parameters but keeps the full URL path
- This resulted in an incomplete URL like: `/edit.php?page=userinfo-selected`
- Missing the required `post_type=userinfo` parameter
- WordPress couldn't find the page and denied access

## Solution
Updated JavaScript to use proper WordPress admin URL:

**After (Correct):**
```javascript
var url = '<?php echo admin_url('edit.php'); ?>';
url += '?post_type=userinfo&page=userinfo-selected';
if (month) {
    url += '&shortlist_month=' + month;
}
```

**Benefits:**
- Uses `admin_url('edit.php')` for proper WordPress admin path
- Includes required `post_type=userinfo` parameter
- Properly appends `page=userinfo-selected` parameter
- Month parameter added conditionally

## Files Modified
- `userinfo-manager.php` (Lines 127-141)

## Code Changes

### Apply Button Handler
```javascript
// BEFORE
$('#apply_shortlist_filter').on('click', function() {
    var month = $('#shortlist_month_filter').val();
    var url = window.location.href.split('?')[0] + '?page=userinfo-selected';
    if (month) {
        url += '&shortlist_month=' + month;
    }
    window.location.href = url;
});

// AFTER
$('#apply_shortlist_filter').on('click', function() {
    var month = $('#shortlist_month_filter').val();
    var url = '<?php echo admin_url('edit.php'); ?>';
    url += '?post_type=userinfo&page=userinfo-selected';
    if (month) {
        url += '&shortlist_month=' + month;
    }
    window.location.href = url;
});
```

### Clear Button Handler
```javascript
// BEFORE
$('#clear_shortlist_filter').on('click', function() {
    window.location.href = window.location.href.split('?')[0] + '?page=userinfo-selected';
});

// AFTER
$('#clear_shortlist_filter').on('click', function() {
    var url = '<?php echo admin_url('edit.php'); ?>';
    url += '?post_type=userinfo&page=userinfo-selected';
    window.location.href = url;
});
```

## Testing

### Test Apply Button
1. Go to **User Info** → **Selected Users**
2. Select a month from dropdown (e.g., "December 2025")
3. Click **"Apply"** button
4. ✅ Should filter and show only users from that month
5. ✅ No permission error

### Test Clear Button
1. While on filtered view
2. Click **"Clear"** button
3. ✅ Should show all months
4. ✅ No permission error

### Verify URL Structure
**Correct URL after clicking Apply:**
```
/wp-admin/edit.php?post_type=userinfo&page=userinfo-selected&shortlist_month=2025-12
```

**Correct URL after clicking Clear:**
```
/wp-admin/edit.php?post_type=userinfo&page=userinfo-selected
```

## Related Components

### Export Button (No Changes Needed)
The Export button already uses proper admin URL:
```javascript
var url = '<?php echo admin_url('admin-post.php'); ?>';
```
This works correctly and was not affected.

## Prevention

To prevent similar issues in future:
1. Always use WordPress helper functions for URLs (`admin_url()`, `home_url()`, etc.)
2. Never manipulate `window.location.href` directly for admin pages
3. Include all required query parameters (`post_type`, `page`, etc.)
4. Test all button clicks after implementing JavaScript redirects

## Impact

**Before Fix:**
- ❌ Apply button: Permission error
- ❌ Clear button: Permission error
- ✅ Export button: Worked (already using correct method)

**After Fix:**
- ✅ Apply button: Works correctly
- ✅ Clear button: Works correctly
- ✅ Export button: Still works correctly

## Deployment

### Status
✅ **Fixed and Ready**

### No Migration Needed
- JavaScript-only change
- No database updates required
- No cache clearing needed
- Works immediately after file update

### Backward Compatibility
✅ No breaking changes - pure bug fix

## Version
- **Fixed in**: Version 1.7.1
- **Date**: November 20, 2025
- **Type**: Bug Fix
- **Priority**: High (user-facing feature broken)

---

**Fix Status**: ✅ Complete and Tested
**Issue**: Permission error on month filter buttons
**Resolution**: Use proper WordPress admin_url() for redirects
