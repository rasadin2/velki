# Offer List Custom Post Type - Quick Reference Guide

## Overview
A complete custom post type system for managing and displaying promotional offers with Bengali language support, based on the design shown in the reference image.

## Files Created
1. `/inc/custom-post-type-offer-list.php` - Main custom post type file
2. `/inc/shortcode-settings.php` - Updated with offer list documentation
3. `functions.php` - Updated to include the custom post type (line 316)

## Admin Access
Navigate to: **WordPress Admin → Offer List**

## Features Implemented

### Admin Panel Features
✅ Custom post type "Offer List" with dashicons-tickets-alt icon
✅ Rich meta box interface with:
   - Offer Title (Bengali input)
   - Icon Upload/Choose with preview
   - Icon Background Color Picker
   - Offer Description (Bengali textarea)
   - Prize Amount (Bengali input)
   - Offer Link URL
   - Button Text (Bengali input)
   - Button Gradient Color Picker
✅ Featured Image support for header background
✅ Custom admin columns (Icon, Offer Title, Prize)
✅ Media uploader integration
✅ Real-time color preview
✅ Professional admin styling

### Frontend Features
✅ Responsive grid layout (3 columns → 1 column on mobile)
✅ Modern card design with hover effects
✅ Featured image header with badge overlay
✅ Customizable icon with colored background
✅ Prize display section
✅ Gradient CTA button with animation
✅ Bengali language support throughout
✅ Smooth hover transitions

## Shortcode Usage

### Basic Usage
```
[offer_list]
```
Displays all published offers in a responsive grid.

### With Attributes
```
[offer_list limit="3" order="DESC" orderby="date"]
```

### Shortcode Attributes
| Attribute | Type   | Default | Description                              |
|-----------|--------|---------|------------------------------------------|
| limit     | Number | -1      | Number of offers to display (-1 = all)   |
| order     | String | DESC    | Sort order (ASC or DESC)                 |
| orderby   | String | date    | Sort by (date, title, menu_order, etc.)  |

### Usage Examples
```php
// Display 3 latest offers
[offer_list limit="3"]

// Display all offers ordered by title
[offer_list orderby="title" order="ASC"]

// Display 6 offers in custom order
[offer_list limit="6" orderby="menu_order"]
```

## Creating an Offer (Step by Step)

1. Go to **WordPress Admin → Offer List → Add New**

2. **Enter Post Title** (internal use only, not displayed on frontend)

3. **Set Featured Image** (Click "Set offer image" in right sidebar)
   - This becomes the background image in the card header
   - Recommended size: 800x400px or larger

4. **Fill in Offer Details Meta Box:**

   **Offer Title (Bengali):**
   - Example: `দৈনিক বোনাস`
   - This appears in the badge and as the main title

   **Offer Icon:**
   - Click "Upload/Choose Icon"
   - Select or upload an icon (recommended: 100x100px PNG with transparency)
   - Preview appears immediately

   **Icon Background Color:**
   - Use color picker to choose background
   - Default: #f39c12 (orange)
   - Example colors from screenshot: Orange (#f39c12), Purple (#8b5cf6), Orange-Red (#f97316)

   **Offer Description (Bengali):**
   - Example: `প্রতিদিনের বিশেষ বোনাস অফার পান`
   - Brief description (1-2 sentences)

   **Prize Amount (Bengali):**
   - Example: `১০,০০০ টাকা`
   - Example: `১,০০,০০০ টাকা`
   - Example: `৫০,০০০ টাকা`

   **Offer Link URL:**
   - Full URL where users will be redirected
   - Example: `https://example.com/daily-bonus`

   **Button Text (Bengali):**
   - Example: `এখনই যুক্ত হন`
   - Default if left empty: `এখনই যুক্ত হন`

   **Button Gradient Color:**
   - Base color for the button gradient
   - Default: #f39c12
   - Gradient is automatically generated from this color

5. **Publish** the offer

## Meta Fields Reference

All meta fields are stored with underscore prefix to hide from custom fields UI:

| Meta Key                | Type    | Description                           |
|-------------------------|---------|---------------------------------------|
| _offer_title            | Text    | Offer title in Bengali                |
| _offer_icon             | URL     | Icon image URL                        |
| _offer_icon_bg_color    | Color   | Icon container background (hex)       |
| _offer_description      | Text    | Brief description in Bengali          |
| _offer_prize            | Text    | Prize amount in Bengali with format   |
| _offer_link             | URL     | Target link for the offer             |
| _offer_button_text      | Text    | CTA button text in Bengali            |
| _offer_button_color     | Color   | Button gradient base color (hex)      |

## Frontend Output Structure

```html
<div class="offer-list-container">
    <div class="offer-card">
        <div class="offer-header">
            <img src="[featured_image]" />
            <div class="offer-badge">[offer_title]</div>
        </div>
        <div class="offer-body">
            <div class="offer-icon-wrapper" style="background-color: [icon_bg_color]">
                <img src="[offer_icon]" />
            </div>
            <h3 class="offer-title">[offer_title]</h3>
            <p class="offer-description">[offer_description]</p>
            <div class="offer-prize-section">
                <div class="offer-prize-label">পুরস্কার</div>
                <div class="offer-prize-amount">[offer_prize]</div>
            </div>
            <a href="[offer_link]" class="offer-button">[offer_button_text]</a>
        </div>
    </div>
</div>
```

## Styling Customization

All CSS is included inline in the shortcode. To customize:

1. Copy the styles from `inc/custom-post-type-offer-list.php` (lines ~449-595)
2. Move to your theme's stylesheet
3. Modify as needed
4. Remove inline styles from the shortcode function

### Key CSS Classes
- `.offer-list-container` - Grid container
- `.offer-card` - Individual card
- `.offer-header` - Image header section
- `.offer-badge` - Top-right badge
- `.offer-icon-wrapper` - Icon container
- `.offer-body` - Card content area
- `.offer-prize-section` - Prize display box
- `.offer-button` - CTA button

## Example Data (From Screenshot)

### Card 1: Daily Bonus
```
Title: দৈনিক বোনাস
Description: প্রতিদিনের বিশেষ বোনাস অফার পান
Prize: ১০,০০০ টাকা
Icon BG Color: #f39c12 (Orange)
Button Text: এখনই যুক্ত হন
```

### Card 2: Super Jackpot
```
Title: সুপার জ্যাকপট
Description: সপ্তাহের মধ্য পুরস্কার জিতে নিন
Prize: ১,০০,০০০ টাকা
Icon BG Color: #8b5cf6 (Purple)
Button Text: এখনই যুক্ত হন
```

### Card 3: Cricket Championship
```
Title: ক্রিকেট চ্যাম্পিয়নশিপ
Description: আজকের বিশেষ ক্রিকেট ম্যাচে অংশ নিন এবং জিতুন বড় পুরস্কার
Prize: ৫০,০০০ টাকা
Icon BG Color: #f97316 (Orange-Red)
Button Text: এখনই যুক্ত হন
```

## Troubleshooting

### Shortcode not displaying
- Verify the file is included in functions.php (line 316)
- Check if offers are published (not draft)
- Clear any caching plugins

### Icons not uploading
- Ensure WordPress media library is functional
- Check file permissions on uploads directory
- Verify `wp_enqueue_media()` is loading

### Colors not saving
- Check `sanitize_hex_color` function exists (WordPress 3.4+)
- Verify nonce is passing correctly

### Styling issues
- Check for theme CSS conflicts
- Inspect browser console for errors
- Verify grid support in older browsers

## Documentation Access

Full documentation available at:
**WordPress Admin → Appearance → Shortcode Docs**

## Support

For issues or customization requests, contact the theme developer.

---

**Created:** 2026-01-10
**Theme:** Wicket
**WordPress Version:** 5.0+
