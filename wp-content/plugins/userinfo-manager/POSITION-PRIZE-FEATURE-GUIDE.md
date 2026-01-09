# Position and Prize Feature - Complete Guide

## Overview
The Position and Prize feature extends the Monthly Shortlist functionality by allowing administrators to assign positions (ranks) and prizes (awards) to shortlisted users. This is perfect for competitions, contests, awards, or any ranking-based selection process.

## Features

### 1. **Position Field**
- Text input field for entering user's position/rank
- Examples: "1st", "2nd", "3rd", "Winner", "Runner-up", "Finalist"
- Displayed in the Selected Users list with blue color styling
- Included in CSV exports

### 2. **Prize Field**
- Text input field for entering prize/award information
- Examples: "$1000", "Gold Medal", "Trophy", "Certificate"
- Displayed in the Selected Users list with green color styling
- Included in CSV exports

### 3. **Edit Page Integration**
- Position and Prize fields appear in a dedicated "Shortlist Information" section
- Located below the standard user fields in the edit page
- Only visible when editing a user entry
- Values are saved automatically when updating the user

### 4. **Display in Selected Users List**
- Position column shows rank with blue color emphasis
- Prize column shows award with green color emphasis
- Empty fields show "—" placeholder
- Sortable table structure

### 5. **CSV Export Integration**
- Position column included in all CSV exports
- Prize column included in all CSV exports
- Empty values export as blank cells
- Column order: Name → Registration → Contact → **Position** → **Prize** → Date → Status

## How to Use

### Adding Position and Prize to a User

1. **Navigate to Edit Page**:
   - Go to **User Info** → **Selected Users**
   - Click the **"Edit"** button for the user you want to update
   - OR go to **User Info** → **All User Info** and edit any user

2. **Find Shortlist Information Section**:
   - Scroll down in the edit page
   - Look for the gray header: **"Shortlist Information"**
   - This section appears below the standard user fields

3. **Enter Position**:
   - In the **"Position"** field, enter the rank/position
   - Examples: `1st`, `2nd`, `3rd`, `Winner`, `Runner-up`
   - This field accepts any text value

4. **Enter Prize**:
   - In the **"Prize"** field, enter the prize/award
   - Examples: `$1000`, `Gold Medal`, `Trophy + Certificate`
   - This field accepts any text value

5. **Save Changes**:
   - Click the **"Update"** button
   - Position and Prize are saved to the database
   - Return to Selected Users to see the updated information

### Viewing Position and Prize Information

#### In Selected Users List

1. Navigate to **User Info** → **Selected Users**
2. Position column displays:
   - **Blue bold text** if position is set
   - **Gray "—"** if position is empty
3. Prize column displays:
   - **Green bold text** if prize is set
   - **Gray "—"** if prize is empty

#### In CSV Export

1. Go to **User Info** → **Selected Users**
2. Click **"Export to CSV"** button
3. Open the downloaded file
4. Find columns:
   - Column 7: **Position**
   - Column 8: **Prize**
5. Values export as entered, empty fields are blank

### Common Workflows

#### Competition Results Entry

**Scenario**: Monthly photography competition with 3 winners

1. **Select Winners**:
   - Toggle 3 users to shortlist
   - All tagged with current month

2. **Assign Positions**:
   - Edit 1st place user → Position: `1st` → Prize: `$500`
   - Edit 2nd place user → Position: `2nd` → Prize: `$300`
   - Edit 3rd place user → Position: `3rd` → Prize: `$200`

3. **Review Results**:
   - View Selected Users page
   - See positions and prizes clearly displayed
   - Export to CSV for records

#### Award Ceremony Preparation

**Scenario**: Monthly awards with various categories

1. **Select All Winners**:
   - Toggle all award winners to shortlist
   - Month automatically recorded

2. **Enter Award Details**:
   - Best Performer → Position: `Best Performer` → Prize: `Gold Trophy`
   - Most Improved → Position: `Most Improved` → Prize: `Certificate`
   - Rising Star → Position: `Rising Star` → Prize: `Medal`

3. **Export for Ceremony**:
   - Filter by award month
   - Export CSV with all winners
   - Use for ceremony announcements

#### Contest Finalist Tracking

**Scenario**: Multi-round contest with finalists

1. **Mark Finalists**:
   - Toggle all finalists to shortlist
   - Month marks the contest round

2. **Track Progress**:
   - Round 1 → Position: `Finalist` → Prize: `TBD`
   - Round 2 → Position: `Semi-Finalist` → Prize: `TBD`
   - Final → Update with actual positions and prizes

3. **Final Results**:
   - Export complete contest history
   - Clear record of all participants

## Field Specifications

### Position Field
```yaml
Field Name: Position
Database Key: _userinfo_position
Field Type: Text input
Max Length: Unlimited (sanitized text)
Required: No (optional)
Validation: Accepts any text
Examples:
  - "1st", "2nd", "3rd"
  - "Winner", "Runner-up"
  - "Gold", "Silver", "Bronze"
  - "Champion", "Finalist"
  - "Category A Winner"
Display Color: Blue (#0073aa)
Display Style: Bold
```

### Prize Field
```yaml
Field Name: Prize
Database Key: _userinfo_prize
Field Type: Text input
Max Length: Unlimited (sanitized text)
Required: No (optional)
Validation: Accepts any text
Examples:
  - "$1000", "$500", "$250"
  - "Gold Medal", "Silver Trophy"
  - "Certificate + Badge"
  - "Scholarship"
  - "iPad Pro"
Display Color: Green (#46b450)
Display Style: Bold (weight: 600)
```

## Display Locations

### 1. User Edit Page

**Location**: Below "Submitted Date" field

```
┌─────────────────────────────────────────────┐
│  Shortlist Information                      │
├─────────────────────────────────────────────┤
│  Position: [________________]               │
│  (e.g., 1st, 2nd, Winner)                   │
│                                             │
│  Prize: [________________]                  │
│  (e.g., $1000, Gold Medal)                  │
└─────────────────────────────────────────────┘
```

### 2. Selected Users List

**Location**: Between "Valid" and "Selected Month" columns

```
┌──────────────────────────────────────────────────────┐
│ Name │Valid│ Position │ Prize        │ Month │ Edit │
├──────┼─────┼──────────┼──────────────┼───────┼──────┤
│ John │Valid│ 1st      │ $1000        │ Dec   │[Edit]│
│ Jane │Valid│ 2nd      │ Gold Medal   │ Dec   │[Edit]│
│ Bob  │Valid│ 3rd      │ Certificate  │ Dec   │[Edit]│
└──────────────────────────────────────────────────────┘
```

### 3. CSV Export

**Column Order**:
1. Full Name
2. Username
3. Registration ID
4. Agent ID
5. Phone Number
6. Email Address
7. **Position** ← NEW
8. **Prize** ← NEW
9. Submitted Date
10. Post Date
11. Valid Member

## Styling Details

### Position Display
```css
Color: #0073aa (WordPress blue)
Font Weight: bold
Font Style: strong tag
Placeholder (empty): #999 (gray) with "—"
```

### Prize Display
```css
Color: #46b450 (WordPress green)
Font Weight: 600 (semi-bold)
Font Style: span tag
Placeholder (empty): #999 (gray) with "—"
```

### Edit Page Section Header
```css
Background: #f0f0f0 (light gray)
Padding: 10px
Font Weight: bold
Border Top: 2px solid #ddd
```

## Database Structure

```sql
-- Position meta field
meta_key: _userinfo_position
meta_value: VARCHAR (any text)
Example: "1st", "Winner", "Gold Medal Winner"

-- Prize meta field
meta_key: _userinfo_prize
meta_value: VARCHAR (any text)
Example: "$1000", "Trophy", "Certificate + Badge"
```

## Security

### Input Sanitization
- Both fields use `sanitize_text_field()` on save
- Removes HTML tags and special characters
- Prevents XSS attacks

### Output Escaping
- Display uses `esc_html()` for safe output
- CSV export sanitizes data
- All attributes use `esc_attr()`

### Permissions
- **Edit Access**: Requires `edit_posts` capability
- **View Access**: Requires `edit_posts` capability
- **Export Access**: Requires `edit_posts` capability

## Best Practices

### Position Field

**DO**:
- ✅ Use consistent format (all "1st, 2nd" OR all "Winner, Runner-up")
- ✅ Be concise (3-20 characters ideal)
- ✅ Use clear, understandable terms
- ✅ Match your organization's terminology

**DON'T**:
- ❌ Use overly long descriptions
- ❌ Mix different format styles
- ❌ Include unnecessary details
- ❌ Use special characters excessively

**Examples**:
```
Good: "1st Place", "Winner", "Champion"
Okay: "First Place Winner", "Category A"
Poor: "The winner of the monthly competition in category 1"
```

### Prize Field

**DO**:
- ✅ Include currency symbol if monetary ($, €, £)
- ✅ Be specific about prize components
- ✅ Use recognized award names
- ✅ Keep format consistent

**DON'T**:
- ❌ Use ambiguous terms
- ❌ Omit important details
- ❌ Include internal codes
- ❌ Mix currencies without clarification

**Examples**:
```
Good: "$1000", "Gold Trophy + Certificate", "iPad Pro"
Okay: "Cash Prize", "Trophy", "Gift"
Poor: "TBD", "Prize001", "See manager"
```

## Workflow Examples

### Example 1: Monthly Contest

```yaml
Month: December 2025
Contest Type: Photography

Winners:
  1st_place:
    Name: "John Doe"
    Position: "1st Place"
    Prize: "$500 + Featured Photo"

  2nd_place:
    Name: "Jane Smith"
    Position: "2nd Place"
    Prize: "$300 + Certificate"

  3rd_place:
    Name: "Bob Johnson"
    Position: "3rd Place"
    Prize: "$100 + Certificate"

Process:
  1. Shortlist 3 winners
  2. Edit each → Add position and prize
  3. Export CSV for announcement
  4. Archive for records
```

### Example 2: Award Categories

```yaml
Month: December 2025
Event: Annual Awards

Categories:
  best_performance:
    Winner: "Alice Brown"
    Position: "Best Performance"
    Prize: "Gold Trophy"

  most_improved:
    Winner: "Charlie Davis"
    Position: "Most Improved"
    Prize: "Silver Trophy"

  rising_star:
    Winner: "Diana Evans"
    Position: "Rising Star"
    Prize: "Bronze Medal"
```

### Example 3: Scholarship Awards

```yaml
Month: December 2025
Program: Educational Scholarships

Recipients:
  full_scholarship:
    Student: "Emma Wilson"
    Position: "Full Scholarship"
    Prize: "$10,000 Tuition"

  partial_scholarship_1:
    Student: "Frank Martin"
    Position: "Partial Scholarship"
    Prize: "$5,000 Tuition"

  partial_scholarship_2:
    Student: "Grace Lee"
    Position: "Partial Scholarship"
    Prize: "$5,000 Tuition"
```

## Reporting Examples

### Monthly Winners Report

Export CSV and create summary:

```
December 2025 Winners

1st Place: John Doe - $500 + Featured Photo
2nd Place: Jane Smith - $300 + Certificate
3rd Place: Bob Johnson - $100 + Certificate

Total Prize Value: $900
Total Winners: 3
```

### Annual Summary

Combine multiple months:

```
2025 Annual Contest Summary

Total Competitions: 12 months
Total Winners: 36 users
Total Prize Money: $10,800
Most Wins: John Doe (3 times)
Highest Prize: $1000 (January Winner)
```

## Troubleshooting

### Position/Prize not saving?
- Check that you're clicking "Update" button
- Verify you have edit permissions
- Check browser console for JavaScript errors
- Refresh page after saving

### Position/Prize not showing in list?
- Refresh the Selected Users page
- Verify data was saved (re-open edit page)
- Clear browser cache
- Check if user is actually shortlisted

### CSV export missing Position/Prize?
- Re-export the CSV file
- Check column headers (Position is column 7, Prize is column 8)
- Verify data exists in edit page
- Try exporting with month filter

### Formatting issues?
- Keep text concise (<50 characters recommended)
- Avoid special characters that break CSV
- Use standard currency symbols
- Test export after entering data

## Integration Notes

### Backward Compatibility
- Works with existing shortlist data
- Older shortlisted users without position/prize show "—"
- No migration needed
- Optional fields - not required

### Future Enhancements
- Predefined position dropdown
- Prize amount validation
- Position/Prize bulk edit
- Prize statistics dashboard
- Position-based sorting
- Prize value calculations

## Version History

- **Version 1.7.0** - Position and Prize Feature Added
  - Position field for ranking/position entry
  - Prize field for award/prize entry
  - Edit page section "Shortlist Information"
  - Selected Users list display integration
  - CSV export integration
  - Blue/Green color styling
  - Full security implementation

---

**Feature Status**: ✅ Production Ready
**Last Updated**: November 20, 2025
**Version**: 1.7.0
