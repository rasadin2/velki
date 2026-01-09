# CSV Export Feature Guide

## Overview

The UserInfo Manager plugin includes a powerful CSV export feature that allows you to download user submission data in CSV format for analysis, backup, or integration with other systems.

## How to Export Data

### Method 1: Export All Data

1. Navigate to **User Info** in WordPress admin
2. Ensure the month filter shows **"All Months"**
3. Click the **"Export to CSV"** button (blue button in toolbar)
4. CSV file downloads automatically

**Filename Format**: `userinfo-export-YYYY-MM-DD-HHmmss.csv`

Example: `userinfo-export-2025-11-11-173045.csv`

### Method 2: Export Filtered Data by Month

1. Navigate to **User Info** in WordPress admin
2. Select a specific month from the dropdown (e.g., "November 2025")
3. Wait for the list to filter
4. Click the **"Export to CSV"** button
5. CSV file downloads with only that month's data

**Filename Format**: `userinfo-export-YYYY-MM-YYYY-MM-DD-HHmmss.csv`

Example: `userinfo-export-2025-11-2025-11-11-173045.csv`

## CSV File Structure

The exported CSV file contains the following columns:

| Column | Description | Example |
|--------|-------------|---------|
| Name | User's submitted name | John Doe |
| Email | User's submitted email | john@example.com |
| Submitted Date | Date/time form was submitted | November 11, 2025 5:30 PM |
| Post Date | Date/time WordPress post was created | November 11, 2025 5:30 PM |

## Technical Details

### Character Encoding
- **Encoding**: UTF-8 with BOM (Byte Order Mark)
- **Purpose**: Ensures proper display in Excel and Google Sheets
- **Compatibility**: Works with international characters and special symbols

### Security Features
- **Permission Check**: Only users with `edit_posts` capability can export
- **Nonce Verification**: Prevents CSRF attacks
- **Input Validation**: Month parameter validated with regex pattern
- **Data Sanitization**: All inputs sanitized before processing

### Export Process

```
User clicks Export →
JavaScript builds URL with parameters →
WordPress validates permissions →
Query database with filters →
Generate CSV file →
Stream to browser →
File downloads
```

## Use Cases

### 1. Monthly Reports
Export data by month for monthly reporting:
- Filter by "November 2025"
- Export to CSV
- Import into Excel or Google Sheets
- Create charts and analytics

### 2. Data Backup
Regular backups of all submissions:
- Export all data monthly
- Store CSV files in backup location
- Keep historical records

### 3. Email Marketing
Build email lists from submissions:
- Export all or filtered data
- Import into email marketing platform (Mailchimp, Constant Contact, etc.)
- Create targeted campaigns

### 4. Data Analysis
Analyze submission trends:
- Export data for specific periods
- Import into analytics tools
- Identify patterns and trends

### 5. CRM Integration
Import into customer relationship management systems:
- Export formatted data
- Import into CRM (Salesforce, HubSpot, etc.)
- Track customer interactions

## Troubleshooting

### Export Button Not Visible
**Issue**: Can't see the "Export to CSV" button
**Solution**:
- Ensure you're on the User Info list page
- Check that you have admin/editor permissions
- Clear browser cache and reload

### Download Not Starting
**Issue**: Clicking export doesn't download file
**Solution**:
- Check browser popup blocker settings
- Ensure JavaScript is enabled
- Try different browser
- Check browser console for errors

### Empty CSV File
**Issue**: CSV downloads but has no data
**Solution**:
- Verify there are submissions in the database
- Check if month filter is too restrictive
- Try exporting "All Months" first

### Special Characters Not Displaying
**Issue**: Names with accents or special characters show incorrectly
**Solution**:
- The file uses UTF-8 with BOM
- Try opening in Google Sheets instead of Excel
- In Excel: Use "Data → From Text/CSV" import instead of double-clicking

### File Permission Errors
**Issue**: "You do not have permission to export data"
**Solution**:
- Contact site administrator
- Ensure your user role is Editor or Administrator
- Check WordPress capabilities settings

## Best Practices

### Regular Exports
- Set up a schedule (weekly/monthly)
- Store exports in organized folders
- Name exports with descriptive dates

### Data Privacy
- Protect exported files (contains email addresses)
- Follow GDPR/privacy regulations
- Delete old exports when no longer needed
- Encrypt sensitive export files

### File Management
- Organize by date: `exports/2025/11/`
- Use consistent naming conventions
- Document export purposes
- Archive old exports regularly

## Advanced Tips

### Filter Before Export
For more targeted exports:
1. Filter by month first
2. Review filtered results
3. Export only what you need
4. Reduces file size and processing time

### Excel Import
For best results in Excel:
1. Open Excel
2. Go to Data → From Text/CSV
3. Select the exported CSV file
4. Choose UTF-8 encoding
5. Import data

### Automated Reporting
Consider combining with:
- Scheduled WordPress cron jobs
- External automation tools (Zapier, IFTTT)
- Email delivery of exports
- Cloud storage integration

## Support

For additional help with CSV exports:
- Check plugin README.md
- Review WordPress admin interface
- Contact site administrator
- Review WordPress user capabilities

## Version History

**Version 1.2.0** (Current)
- Initial CSV export functionality
- Month-based filtering
- UTF-8 BOM support
- Security features (nonce, permissions)
