# Installation Guide - WhatsApp Floating Button

## Quick Start Guide

Follow these simple steps to get your WhatsApp floating button up and running:

### Step 1: Activate the Plugin

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins** → **Installed Plugins**
3. Find **WhatsApp Floating Button** in the list
4. Click **Activate**

### Step 2: Configure Settings

1. After activation, go to **WhatsApp Button** in the left admin menu
2. You'll see the settings page with the following options:

#### Configure Your WhatsApp Numbers

**WhatsApp Number 1:**
- Enter your first phone number in international format
- Example: `1234567890` (without + or spaces)
- Add a label like "Sales Support" or "Customer Service"

**WhatsApp Number 2:**
- Enter your second phone number (optional)
- Add a descriptive label

**WhatsApp Number 3:**
- Enter your third phone number (optional)
- Add a descriptive label

#### Phone Number Format Examples

| Country | Format Example | Notes |
|---------|---------------|-------|
| USA | `12125551234` | Country code (1) + area code + number |
| UK | `447700900000` | Country code (44) + number |
| India | `919876543210` | Country code (91) + number |
| Brazil | `5511999999999` | Country code (55) + area code + number |

#### Button Settings

**Button Position:**
- Choose "Bottom Right" (default) or "Bottom Left"

**Button Color:**
- Click the color picker to select your brand color
- Default is WhatsApp green (#25D366)

### Step 3: Save Settings

1. Click **Save Settings** at the bottom of the page
2. You'll see a success message

### Step 4: Test on Frontend

1. Visit any page on your website
2. You should see the floating WhatsApp button in the position you selected
3. Click the button to see the popup with your configured contacts
4. Click on a contact to test the WhatsApp link

## Troubleshooting

### Button Not Showing?

**Check 1: Plugin Activated**
- Go to Plugins → Installed Plugins
- Verify WhatsApp Floating Button is activated

**Check 2: At Least One Number Configured**
- Go to WhatsApp Button settings
- Make sure at least one WhatsApp number is entered and saved

**Check 3: Clear Cache**
- If using a caching plugin, clear your cache
- Try viewing in an incognito/private browser window

**Check 4: Check for Conflicts**
- Temporarily switch to a default WordPress theme (Twenty Twenty-Three)
- If button appears, there may be a theme conflict
- Contact your theme developer for assistance

### Popup Not Opening?

**Check 1: JavaScript Errors**
- Open browser console (F12 or right-click → Inspect)
- Look for JavaScript errors in the console
- Common fix: Clear browser cache and refresh

**Check 2: jQuery Conflict**
- Some themes or plugins may have jQuery conflicts
- Try deactivating other plugins one by one to identify the conflict

### WhatsApp Link Not Working?

**Check 1: Phone Number Format**
- Numbers must be in international format
- Remove all spaces, dashes, and + symbol
- Example: `12125551234` not `+1 (212) 555-1234`

**Check 2: WhatsApp Installed**
- On mobile, ensure WhatsApp is installed
- On desktop, WhatsApp Web will open in browser

## Configuration Examples

### Example 1: Business with Sales & Support

```
Number 1: 12125551234
Label 1: Sales Team

Number 2: 12125555678
Label 2: Technical Support

Number 3: 12125559999
Label 3: General Inquiries

Position: Bottom Right
Color: #25D366 (WhatsApp Green)
```

### Example 2: Multi-Language Support

```
Number 1: 447700900001
Label 1: English Support

Number 2: 447700900002
Label 2: Spanish Support

Number 3: 447700900003
Label 3: French Support

Position: Bottom Right
Color: #1E90FF (Custom Blue)
```

### Example 3: Department-Based

```
Number 1: 919876543210
Label 1: HR Department

Number 2: 919876543211
Label 2: Accounts

Number 3: 919876543212
Label 3: Operations

Position: Bottom Left
Color: #FF6B6B (Custom Red)
```

## Advanced Configuration

### Hide on Specific Pages

Add this code to your theme's `functions.php`:

```php
// Hide WhatsApp button on checkout page
add_action('wp_footer', function() {
    if (is_page('checkout')) {
        echo '<style>.wfb-container { display: none !important; }</style>';
    }
}, 99);
```

### Hide on Mobile Devices

Add this to your Additional CSS (Appearance → Customize → Additional CSS):

```css
@media (max-width: 768px) {
    .wfb-container {
        display: none !important;
    }
}
```

### Change Button Size

Add this to your Additional CSS:

```css
.wfb-button {
    width: 70px !important;
    height: 70px !important;
}

.wfb-button-icon {
    width: 36px !important;
    height: 36px !important;
}
```

## Getting Help

If you need assistance:

1. Check this installation guide
2. Review the README.md file
3. Contact plugin support

## Next Steps

After installation:

1. ✅ Test the button on multiple devices
2. ✅ Train your team on responding to WhatsApp messages
3. ✅ Set up WhatsApp Business features
4. ✅ Monitor click analytics (if configured)
5. ✅ Customize colors to match your brand

## Uninstallation

To remove the plugin:

1. Go to Plugins → Installed Plugins
2. Deactivate **WhatsApp Floating Button**
3. Click **Delete**
4. All settings will be removed from your database

---

**Plugin Version:** 1.0.0
**Last Updated:** November 2025
