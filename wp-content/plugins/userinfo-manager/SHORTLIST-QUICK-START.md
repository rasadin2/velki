# Monthly Shortlist Feature - Quick Start Guide

## 🚀 Quick Start (5 Minutes)

### Step 1: Add Users to Shortlist (2 min)

1. Go to **WordPress Admin** → **User Info** → **All User Info**
2. Look for the **"Shortlist"** column (new blue/gray toggle)
3. Click the toggle for users you want to select
4. Toggle turns **BLUE** and shows **"Selected"** with current month

### Step 2: View Selected Users (1 min)

1. Go to **WordPress Admin** → **User Info** → **Selected Users**
2. See all shortlisted users in one table
3. Notice the month each user was selected

### Step 3: Filter by Month (1 min)

1. On the **Selected Users** page
2. Use the **"Filter by Month"** dropdown
3. Select a month → Click **"Apply"**
4. View only users selected in that month

### Step 4: Export to CSV (1 min)

1. On the **Selected Users** page
2. Optional: Filter by month first
3. Click **"Export to CSV"** button
4. File downloads automatically

## ✅ Visual Guide

### Main List View
```
User Info → All User Info

┌──────────────────────────────────────────────────────┐
│ Full Name  │ Username │ Valid  │ [Shortlist]         │
├────────────┼──────────┼────────┼─────────────────────┤
│ John Doe   │ johndoe  │ Valid  │ ○── Not Selected    │ ← Gray (Not Selected)
│ Jane Smith │ jane     │ Valid  │ ──● Selected        │ ← Blue (Selected)
│                                  │     December 2025   │
└──────────────────────────────────┴─────────────────────┘
```

### Selected Users View
```
User Info → Selected Users

Filter by Month: [December 2025 ▼] [Apply] [Export to CSV]

┌──────────────────────────────────────────────────────────┐
│ Name    │ Username │ RegID │ Month       │ [Actions]     │
├─────────┼──────────┼───────┼─────────────┼───────────────┤
│ Jane S. │ jane     │ 25120 │ Dec 2025    │ [Edit]        │
└──────────────────────────────────────────────────────────┘

Total Selected Users: 1
```

## 🎯 Common Tasks

### Task: Select Users for This Month
**Time: 5 minutes**

1. Navigate: **User Info** → **All User Info**
2. Review user list
3. Toggle ON users who meet your criteria
4. Done! They're automatically tagged with current month

### Task: Review Last Month's Selections
**Time: 3 minutes**

1. Navigate: **User Info** → **Selected Users**
2. Select previous month from dropdown
3. Click **"Apply"**
4. Review the filtered list

### Task: Export Monthly Report
**Time: 2 minutes**

1. Navigate: **User Info** → **Selected Users**
2. Filter by desired month
3. Click **"Apply"**
4. Click **"Export to CSV"**
5. Open downloaded file in Excel

### Task: Remove User from Shortlist
**Time: 30 seconds**

1. Navigate: **User Info** → **All User Info**
2. Find the user (they'll have blue toggle)
3. Click toggle to turn it OFF
4. User removed from shortlist

### Task: Edit Shortlisted User
**Time: 2 minutes**

1. Navigate: **User Info** → **Selected Users**
2. Find the user in the table
3. Click **"Edit"** button
4. Modify user details
5. Click **"Update"**
6. Return to Selected Users to see changes

## 💡 Pro Tips

1. **Monthly Routine**:
   - Export at end of month before new selections
   - Creates monthly archives automatically

2. **Quick Selection**:
   - Use main list's existing filters first
   - Then toggle users in filtered view

3. **Bulk Review**:
   - Filter by month on Selected Users page
   - See exactly who you selected when

4. **Filename Convention**:
   - Exports include month in filename
   - Example: `userinfo-shortlist-2025-12-20251120-143025.csv`

5. **Double-Check**:
   - Always verify count before export
   - "Total Selected Users: X" shows at bottom

## ⚠️ Important Notes

### Automatic Month Tagging
- Month is **automatically set** when you toggle ON
- Uses **current month** (YYYY-MM format)
- Cannot manually change month after selection

### Data Safety
- Toggle OFF removes from shortlist
- Original user data is **never deleted**
- Can always add back to shortlist later

### Permissions
- Requires **Editor or Admin** role
- Contributors cannot access shortlist features

## 🔄 Monthly Workflow Example

### December 2025 Process

**Week 1** (Review)
- Review November submissions
- Identify users meeting criteria

**Week 2** (Selection)
- Toggle ON selected users
- All tagged as "2025-12"

**Week 3** (Review)
- Check Selected Users page
- Make any adjustments

**Week 4** (Export)
- Filter by December 2025
- Export to CSV
- Archive file

**January 2026** (New Month)
- Start fresh selections
- New users tagged as "2026-01"
- December data still accessible

## 📊 Understanding the Data

### Shortlist Column (Main List)
- **Gray Toggle**: Not selected
- **Blue Toggle**: Selected for current/past month
- **Month Label**: When user was selected

### Selected Users Page
- Shows **all shortlisted** users by default
- **Filter** to see specific month
- **Export** gets current view (filtered or all)

### CSV Export Columns
1. Full Name
2. Username
3. Registration ID
4. Agent ID
5. Phone Number
6. Email Address
7. Submitted Date
8. Post Date
9. Valid Member Status

## 🆘 Troubleshooting

### Toggle doesn't work?
- Refresh the page
- Check browser console for errors
- Verify you have admin/editor role

### Month not showing?
- Refresh browser
- Clear cache
- Check that toggle is blue (selected)

### Export button not working?
- Try different month filter
- Check if any users are selected
- Refresh page and try again

### Can't see Selected Users menu?
- Verify admin/editor role
- Check plugin is active
- Refresh WordPress admin

## 📚 Need More Help?

- **Full Documentation**: See `SHORTLIST-FEATURE-GUIDE.md`
- **Technical Details**: See `SHORTLIST-IMPLEMENTATION-SUMMARY.md`
- **Visual Diagrams**: See `SHORTLIST-WORKFLOW-DIAGRAM.md`

---

**Ready to Start?**
Go to **User Info** → **All User Info** and toggle your first user!

✅ **It's that simple!**
