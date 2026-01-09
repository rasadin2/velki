# Monthly Shortlist Feature - Visual Workflow

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    UserInfo Manager Plugin                   │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌────────────────┐         ┌────────────────────────────┐  │
│  │  All User Info │         │    Selected Users          │  │
│  │  (Main List)   │         │    (Shortlist Page)        │  │
│  └───────┬────────┘         └────────────┬───────────────┘  │
│          │                               │                   │
│          │  Toggle ON/OFF                │  View/Filter      │
│          │  ↓                            │  Edit/Export      │
│          │                               │                   │
│  ┌───────▼──────────────────────────────▼──────────────┐    │
│  │         WordPress Post Meta Storage                  │    │
│  │  • _userinfo_shortlisted (1 or 0)                   │    │
│  │  • _userinfo_shortlist_month (YYYY-MM)              │    │
│  └──────────────────────────────────────────────────────┘    │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## User Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     Admin User Journey                       │
└─────────────────────────────────────────────────────────────┘

START
  │
  ├─→ Navigate to "User Info" → "All User Info"
  │
  ├─→ View User List with Columns:
  │   [Full Name][Username][Reg ID][Agent][Phone][Email][Valid][Shortlist][Date]
  │
  ├─→ Find User to Select
  │
  ├─→ Click Shortlist Toggle
  │   │
  │   ├─→ Toggle ON (Select)
  │   │   │
  │   │   ├─→ AJAX Request → Server
  │   │   ├─→ Set: _userinfo_shortlisted = 1
  │   │   ├─→ Set: _userinfo_shortlist_month = YYYY-MM
  │   │   ├─→ Response: Success
  │   │   └─→ UI Updates:
  │   │       • Toggle turns BLUE
  │   │       • Label shows "Selected"
  │   │       • Month displays below
  │   │
  │   └─→ Toggle OFF (Deselect)
  │       │
  │       ├─→ AJAX Request → Server
  │       ├─→ Set: _userinfo_shortlisted = 0
  │       ├─→ Delete: _userinfo_shortlist_month
  │       ├─→ Response: Success
  │       └─→ UI Updates:
  │           • Toggle turns GRAY
  │           • Label shows "Not Selected"
  │           • Month removed
  │
  ├─→ Navigate to "User Info" → "Selected Users"
  │
  ├─→ View Selected Users Table
  │   [Name][User][RegID][Agent][Phone][Email][Valid][Month][Date][Edit]
  │
  ├─→ Optional: Filter by Month
  │   │
  │   ├─→ Select month from dropdown
  │   ├─→ Click "Apply"
  │   └─→ View filtered results
  │
  ├─→ Optional: Edit User
  │   │
  │   ├─→ Click "Edit" button
  │   ├─→ Modify user data
  │   └─→ Save changes
  │
  ├─→ Optional: Export to CSV
  │   │
  │   ├─→ Click "Export to CSV"
  │   ├─→ Server generates CSV
  │   └─→ File downloads
  │
END
```

## Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Data Flow Process                         │
└─────────────────────────────────────────────────────────────┘

USER ACTION: Toggle Shortlist
        ↓
┌───────────────────────────────────┐
│  Browser JavaScript               │
│  • Capture click event            │
│  • Get post ID                    │
│  • Show loading state             │
└───────────┬───────────────────────┘
            ↓
┌───────────────────────────────────┐
│  AJAX Request                     │
│  • Action: toggle_shortlist       │
│  • Nonce: security token          │
│  • Post ID: user ID               │
└───────────┬───────────────────────┘
            ↓
┌───────────────────────────────────┐
│  WordPress AJAX Handler           │
│  1. Verify nonce                  │
│  2. Check user permissions        │
│  3. Validate post ID              │
└───────────┬───────────────────────┘
            ↓
┌───────────────────────────────────┐
│  Database Operation               │
│  IF toggling ON:                  │
│    • Set shortlisted = 1          │
│    • Set month = current month    │
│  ELSE:                            │
│    • Set shortlisted = 0          │
│    • Delete month                 │
└───────────┬───────────────────────┘
            ↓
┌───────────────────────────────────┐
│  AJAX Response                    │
│  • Status: success/error          │
│  • New status: 1 or 0             │
│  • Month: formatted month         │
│  • Message: status text           │
└───────────┬───────────────────────┘
            ↓
┌───────────────────────────────────┐
│  Browser JavaScript               │
│  • Update toggle state            │
│  • Update label text              │
│  • Show/hide month                │
│  • Display notification           │
│  • Remove loading state           │
└───────────────────────────────────┘
```

## Database Schema

```
┌─────────────────────────────────────────────────────────────┐
│                   wp_postmeta Table                          │
├──────────┬──────────┬────────────────────┬──────────────────┤
│ meta_id  │ post_id  │    meta_key        │   meta_value     │
├──────────┼──────────┼────────────────────┼──────────────────┤
│   1001   │   123    │ _userinfo_full_name│  John Doe        │
│   1002   │   123    │ _userinfo_username │  johndoe         │
│   1003   │   123    │ _userinfo_shortlist│  1               │ ← NEW
│   1004   │   123    │ _userinfo_..._month│  2025-12         │ ← NEW
├──────────┼──────────┼────────────────────┼──────────────────┤
│   1005   │   124    │ _userinfo_full_name│  Jane Smith      │
│   1006   │   124    │ _userinfo_username │  janesmith       │
│   1007   │   124    │ _userinfo_shortlist│  0               │ ← NEW
└──────────┴──────────┴────────────────────┴──────────────────┘
```

## UI Component Breakdown

### Main List - Shortlist Column

```
┌─────────────────────────────────────────────┐
│  Shortlist                                  │
├─────────────────────────────────────────────┤
│                                             │
│  NOT SELECTED:                              │
│  ┌─────┐                                    │
│  │ ○── │  Not Selected                      │
│  └─────┘                                    │
│  (Gray)                                     │
│                                             │
│  SELECTED:                                  │
│  ┌─────┐                                    │
│  │ ──● │  Selected                          │
│  └─────┘  December 2025                     │
│  (Blue)   (Month display)                   │
│                                             │
└─────────────────────────────────────────────┘
```

### Selected Users Page

```
┌─────────────────────────────────────────────────────────────┐
│  Selected Users                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Filter by Month: [December 2025 ▼] [Apply] [Clear]        │
│                   [Export to CSV]                           │
│                                                             │
│  ┌─────────────────────────────────────────────────────────┤
│  │ Name  │User │RegID│Agent│Phone│Email│Valid│Month│Edit  │
│  ├───────┼─────┼─────┼─────┼─────┼─────┼─────┼─────┼──────┤
│  │ John  │john │2512│ A01 │1234 │j@e  │Valid│Dec  │[Edit]│
│  │ Jane  │jane │2513│ A02 │5678 │j@e  │Valid│Dec  │[Edit]│
│  └───────┴─────┴─────┴─────┴─────┴─────┴─────┴─────┴──────┘
│                                                             │
│  Total Selected Users: 2                                    │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

## State Machine

```
┌─────────────────────────────────────────────┐
│      Shortlist Toggle State Machine         │
└─────────────────────────────────────────────┘

         ┌──────────────┐
    ┌───│  NOT SELECTED │
    │   │  (State: 0)   │
    │   └───────┬───────┘
    │           │
    │           │ Click Toggle ON
    │           ↓
    │   ┌───────────────┐
    │   │   LOADING     │
    │   │  (AJAX Call)  │
    │   └───────┬───────┘
    │           │
    │           │ Success
    │           ↓
    │   ┌───────────────┐
    └──→│   SELECTED    │
        │  (State: 1)   │
        │  Month: Set   │
        └───────┬───────┘
                │
                │ Click Toggle OFF
                ↓
        ┌───────────────┐
        │   LOADING     │
        │  (AJAX Call)  │
        └───────┬───────┘
                │
                │ Success
                ↓
        ┌───────────────┐
        │  NOT SELECTED │
        │  (State: 0)   │
        │  Month: Clear │
        └───────────────┘
```

## Export Process Flow

```
┌─────────────────────────────────────────────┐
│         CSV Export Process Flow             │
└─────────────────────────────────────────────┘

START
  │
  ├─→ User clicks "Export to CSV"
  │
  ├─→ JavaScript captures click
  │
  ├─→ Check if month filter active
  │   │
  │   ├─→ Yes: Include month parameter
  │   └─→ No: Export all
  │
  ├─→ Build URL with parameters:
  │   • action=userinfo_export_shortlist_csv
  │   • nonce=security_token
  │   • month=YYYY-MM (optional)
  │
  ├─→ Browser redirects to export URL
  │
  ├─→ Server receives request
  │
  ├─→ Security checks:
  │   • Verify nonce
  │   • Check user capability
  │
  ├─→ Build database query:
  │   • Filter: shortlisted = 1
  │   • Optional: month = YYYY-MM
  │   • Order by: submitted_date DESC
  │
  ├─→ Fetch all matching posts
  │
  ├─→ Generate CSV file:
  │   • Set headers
  │   • Add BOM for UTF-8
  │   • Write column headers
  │   • Write data rows
  │
  ├─→ Generate filename:
  │   • All: userinfo-shortlist-export-TIMESTAMP.csv
  │   • Filtered: userinfo-shortlist-YYYY-MM-TIMESTAMP.csv
  │
  ├─→ Send file to browser
  │
  └─→ Browser downloads file
  │
END
```

## Security Flow

```
┌─────────────────────────────────────────────┐
│         Security Validation Flow            │
└─────────────────────────────────────────────┘

Request Received
        ↓
┌───────────────────────┐
│  Nonce Verification   │
│  check_ajax_referer() │
└───────┬───────────────┘
        │ ✓ Valid
        ↓
┌───────────────────────┐
│  Capability Check     │
│  current_user_can()   │
│  Required: edit_posts │
└───────┬───────────────┘
        │ ✓ Authorized
        ↓
┌───────────────────────┐
│  Input Validation     │
│  • Sanitize text      │
│  • Validate integers  │
│  • Check formats      │
└───────┬───────────────┘
        │ ✓ Clean
        ↓
┌───────────────────────┐
│  Process Request      │
│  • Perform operation  │
│  • Update database    │
└───────┬───────────────┘
        │ ✓ Complete
        ↓
┌───────────────────────┐
│  Output Escaping      │
│  • esc_html()         │
│  • esc_attr()         │
│  • esc_url()          │
│  • esc_js()           │
└───────┬───────────────┘
        │ ✓ Safe
        ↓
   Send Response
```

## Monthly Workflow Timeline

```
Month: December 2025
┌─────────────────────────────────────────────────────────────┐
│  Week 1        Week 2        Week 3        Week 4           │
├─────────────────────────────────────────────────────────────┤
│  Review        Continue      Final         Export &         │
│  November      Selection     Review        Archive          │
│  Submissions   Process                                      │
│                                                             │
│  • View all    • Toggle      • Filter by   • Export CSV    │
│    users         shortlist     Dec 2025    • Save file     │
│  • Check       • Add more    • Review all  • Next month    │
│    criteria      users         selected                    │
│  • Start       • Remove if   • Make final                  │
│    toggling      needed        edits                       │
│                                                             │
└─────────────────────────────────────────────────────────────┘
                           ↓
                    January 2026
┌─────────────────────────────────────────────────────────────┐
│  New month starts - December selections preserved           │
│  • Can still view/export December data                      │
│  • New selections tagged as "2026-01"                       │
│  • Both months independently manageable                     │
└─────────────────────────────────────────────────────────────┘
```

---

**Legend:**
- → Flow direction
- ✓ Success/Valid
- ✗ Failure/Invalid
- ○── Toggle OFF (Gray)
- ──● Toggle ON (Blue)
