# UserInfo Manager Plugin

A custom WordPress plugin that creates a "UserInfo" custom post type with a frontend form for collecting user name and email information.

## ✨ Features

- **Custom Post Type**: "UserInfo" with dedicated admin interface
- **Modern Glassmorphism UI**: Beautiful frontend form with animated gradient backgrounds and glass effects
- **Frontend Form**: Clean, responsive form with validation and smooth animations
- **Image Upload**: Upload images with preview before submission (Max 2MB, JPG/PNG/GIF)
- **Security**: Nonce verification, data sanitization, and validation
- **Admin Interface**: Custom columns showing image thumbnail, name, email, and submission date
- **Backend Image Management**: Remove or replace images from admin edit screen
- **Month Filter**: Filter submissions by month in the admin interface
- **Date Tracking**: Automatic submission date storage with each form entry
- **CSV Export**: Export all data or filtered data to CSV format with image URLs
- **Easy Integration**: Simple shortcode implementation
- **Accessibility**: WCAG AA compliant with full keyboard navigation

## 🎨 Design System

The plugin features a cutting-edge **glassmorphism design system** with:
- Semi-transparent backdrop blur effects
- Animated gradient backgrounds (15s smooth animation)
- Staggered form field entrance animations
- Hover effects with elevation changes
- Modern purple-pink-blue color palette
- 60fps performance optimization
- Full responsive support (desktop, tablet, mobile)

For detailed design documentation, see:
- [GLASSMORPHISM-DESIGN-GUIDE.md](GLASSMORPHISM-DESIGN-GUIDE.md) - Complete technical documentation
- [DESIGN-SHOWCASE.md](DESIGN-SHOWCASE.md) - Visual reference and examples
- [IMPLEMENTATION-SUMMARY.md](IMPLEMENTATION-SUMMARY.md) - Quick overview and stats

## Installation

1. The plugin is already installed at: `wp-content/plugins/userinfo-manager/`
2. Log in to WordPress admin dashboard
3. Go to **Plugins** → **Installed Plugins**
4. Find "UserInfo Manager" and click **Activate**

## Usage

### Display Form on a Page/Post

Add the following shortcode to any page or post where you want the form to appear:

```
[userinfo_form]
```

### Steps to Create a Form Page:

1. Go to **Pages** → **Add New**
2. Enter a title (e.g., "Contact Information")
3. In the content area, add the shortcode: `[userinfo_form]`
4. Click **Publish**
5. Visit the page to see the form

### View Submitted Data

1. Go to **User Info** in the WordPress admin menu
2. You'll see all submitted entries with name, email, and submission date columns
3. Use the month filter dropdown to filter entries by submission month
4. Click on column headers to sort by that column
5. Click on any entry to view/edit details

### Export Data to CSV

1. Go to **User Info** in the WordPress admin menu
2. **(Optional)** Select a specific month from the month filter dropdown
3. Click the **"Export to CSV"** button (blue button in the top toolbar)
4. Your browser will download a CSV file with the data

**Export Options:**
- **Export All**: Leave month filter on "All Months" and click export
- **Export by Month**: Select a month first, then click export

## Form Fields

- **Name** (required): Text input for user's name
- **Email** (required): Email input with validation
- **Image Upload** (optional): File upload for images (JPG, PNG, GIF up to 2MB)
  - Real-time image preview after selection
  - Client-side validation for file size and type
  - Remove image button to clear selection

## Features Included

### Security
- WordPress nonce verification
- Data sanitization (sanitize_text_field, sanitize_email)
- Email validation (is_email)
- CSRF protection

### User Experience
- Success/error messages after submission
- Form field validation
- Responsive design
- Clean, modern styling

### Admin Features
- Custom post type with icon (dashicons-id-alt)
- Custom columns showing image thumbnail (50x50px), name, email, and submission date
- Sortable email and submission date columns
- Month filter dropdown for filtering by submission period
- Meta boxes for editing entries with full image display and submission date
- Image preview in details view (300px max width, clickable for full size)
- Date-based filtering using WordPress date queries
- CSV export button with smart filename generation
- Export respects active month filter (export all or filtered data)
- Image URLs included in CSV export

## File Structure

```
userinfo-manager/
├── userinfo-manager.php    # Main plugin file
└── README.md               # This file
```

## Technical Details

### Custom Post Type Settings
- **Slug**: userinfo
- **Public**: No (only visible in admin)
- **Menu Position**: 20
- **Supports**: Title only (name and email stored as meta fields)

### Meta Fields
- `_userinfo_name`: Stores the user's name
- `_userinfo_email`: Stores the user's email
- `_userinfo_image`: Stores the WordPress attachment ID of uploaded image
- `_userinfo_submitted_date`: Stores the submission date and time (MySQL datetime format)

### Hooks Used
- `init`: Register post type and session
- `add_meta_boxes`: Add admin meta boxes
- `save_post`: Save meta box data
- `admin_post_nopriv_userinfo_submit`: Handle form submission (non-logged-in users)
- `admin_post_userinfo_submit`: Handle form submission (logged-in users)
- `restrict_manage_posts`: Add month filter dropdown to admin
- `pre_get_posts`: Filter posts by month and handle sortable columns
- `manage_userinfo_posts_columns`: Add custom columns to admin list
- `manage_userinfo_posts_custom_column`: Populate custom column content
- `admin_footer-edit.php`: Add export button to admin toolbar
- `admin_post_userinfo_export_csv`: Handle CSV export with security checks

## Customization

### Change Form Styling
Edit the `<style>` section in the `userinfo_form_shortcode()` function.

### Add More Fields
1. Add field to meta box in `userinfo_meta_box_callback()`
2. Save field in `userinfo_save_meta_box_data()`
3. Add field to frontend form in `userinfo_form_shortcode()`
4. Handle field in `userinfo_handle_form_submission()`

### Modify Success Messages
Edit the messages in `userinfo_handle_form_submission()` function.

## Support

For issues or questions, check the WordPress admin interface or contact your developer.

## Recent Updates

### Version 1.7.3 (UI ENHANCEMENT - Layout Unification)
- **ENHANCED**: Unified `body.userinfo-fullwidth-page` and `.userinfo-page-wrapper` styling
- Changed `body.userinfo-fullwidth-page` background from solid `#FFF8E3` to gradient
- Applied same gradient: `linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FF8C00 100%)`
- Unified padding approach: `20px` padding on both layouts
- Removed flexbox centering from body, using natural flow like wrapper
- Consistent layout behavior across all page types
- Golden-orange gradient creates cohesive visual experience
- Professional, branded appearance throughout the application

### Version 1.7.2 (BUGFIX - Container Border Radius)
- **FIXED**: Container border-radius overflow issue on both Registration and Status Check forms
- Changed `.userinfo-form-container::before` and `.userinfo-check-container::before` from `position: fixed` to `position: absolute`
- Added `border-radius: 20px` and `overflow: hidden` to both containers
- Added `isolation: isolate` for proper stacking context on both forms
- Gradient backgrounds now properly contained within rounded corners
- Expanded pseudo-element size (200% width/height) to prevent gaps during animation
- Professional appearance with properly clipped background animation on both forms
- Consistent styling across all form containers

### Version 1.7.1 (BUGFIX - Border Radius)
- **FIXED**: Form border-radius extending upward issue
- Reverted to `overflow: hidden` for proper border-radius clipping
- Added `isolation: isolate` to create new stacking context
- Simplified shimmer animation with `z-index: -1`
- Border-radius now displays correctly without visual artifacts
- Corners properly rounded on all sides
- Clean, professional appearance maintained

### Version 1.7.0 (DESIGN UNIFICATION - Both Forms)
- **MAJOR UPDATE**: Applied glassmorphism design to Status Check form
- Both Registration and Status Check forms now have matching designs
- Animated gradient background on both forms
- Glass form cards with backdrop blur effects
- Staggered entrance animations for form fields
- Glass input fields with smooth transitions
- Gradient submit buttons with shine sweep animation
- Hover effects with elevation changes
- Focus states with blue glow rings
- Responsive design across all breakpoints
- Loading spinner animation for verification button
- WCAG AA accessibility compliance
- Consistent user experience across all forms

### Version 1.6.2 (UI POLISH - Icon Centering)
- **IMPROVED**: Upload icon now perfectly centered horizontally
- Changed margin from `margin-bottom: 16px` to `margin: 0 auto 20px auto`
- Applied consistent centering across all responsive breakpoints
- Desktop: 64px icon with 20px bottom margin, centered
- Tablet (768px): 56px icon with 18px bottom margin, centered
- Mobile (480px): 48px icon with 16px bottom margin, centered
- Better visual balance in upload area
- Professional alignment across all devices

### Version 1.6.1 (LOCALIZATION - Bengali Text)
- **ADDED**: Bengali text in upload area "আপনার এনআইডি ছবি আপলোড করেন"
- Prominent gradient text styling for Bengali heading
- Positioned above English instructions for better visibility
- Larger font size (18px) with bold weight (700)
- Gradient color effect matching design theme
- Responsive sizing for mobile devices (16px on <480px screens)
- Translation-ready with WordPress i18n functions
- Enhanced user experience for Bengali-speaking users

### Version 1.6.0 (MAJOR UI ENHANCEMENT - Custom File Upload)
- **COMPLETE REDESIGN**: Custom drag-and-drop file upload interface
- Replaced native browser file input with modern custom design
- Gradient circular icon with animated upload arrow SVG
- Three-line instructional text (title, subtitle, format info)
- Glassmorphism upload area with dashed border
- Full drag-and-drop support with visual feedback
- Enhanced hover effects (border glow, icon rotation, elevation)
- Drag-active state with purple glow and enhanced animations
- Updated JavaScript with drag-and-drop event handlers
- Manual file input update for dropped files
- Seamless preview transitions (upload area ↔ preview)
- Cross-browser DataTransfer API implementation
- Touch-optimized for mobile devices
- Responsive design (desktop, tablet, mobile breakpoints)
- Maintains all existing functionality and accessibility
- Complete technical documentation in `CUSTOM-FILE-UPLOAD.md`

### Version 1.5.2 (BUGFIX - Text Alignment)
- **FIXED**: File input text vertical alignment
- Button and filename text now perfectly centered
- Added flexbox alignment to file input container
- Enhanced vertical-align properties for button and text
- Improved line-height consistency
- Cross-browser alignment optimization
- Visual polish for professional appearance

### Version 1.5.1 (BUGFIX - File Input)
- **FIXED**: NID Image file input "Choose File" button functionality
- Added cross-browser support for file selector button styling
- Fixed z-index and pointer-events conflicts
- Enhanced Chrome/Safari/Edge compatibility with `::-webkit-file-upload-button`
- Enhanced Firefox compatibility with `::file-selector-button`
- Added IE/Edge legacy support with `::-ms-browse`
- Removed conflicting inline styles from HTML
- Added hover and active states for better feedback
- Firefox-specific padding adjustments
- Comprehensive cross-browser testing completed
- Full technical documentation in `BUGFIX-FILE-INPUT.md`

### Version 1.5.0 (DESIGN OVERHAUL)
- **MAJOR UI REDESIGN**: Complete glassmorphism design system implementation
- Animated gradient background with 5-color smooth transitions
- Semi-transparent form card with backdrop blur effects
- Staggered entrance animations for form fields (sequential reveal)
- Enhanced input fields with glass effects and smooth transitions
- Gradient submit button with shine sweep animation
- Glassmorphism image preview container with fade-in effect
- Modern success/error messages with glass styling
- Enhanced tooltips with dark glassmorphism
- Responsive design optimizations (3 breakpoints)
- WCAG AA accessibility compliance throughout
- 60fps performance optimization
- Browser compatibility with graceful fallbacks
- Comprehensive design documentation (3 new markdown files)
- Custom file input styling with gradient button
- Hover effects with elevation changes
- Focus states with blue glow rings
- Micro-animations and interactions
- Modern purple-pink-blue color palette

### Version 1.4.1 (BUGFIX)
- **CRITICAL FIX**: Added `enctype="multipart/form-data"` to backend post edit form
- Fixed issue where images uploaded from backend admin weren't being saved
- Added comprehensive error handling for image uploads
- Added admin notices for upload success/failure with detailed error messages
- Added proper upload error detection and user-friendly messages

### Version 1.4.0
- Added backend image management in admin edit screen
- Remove image button with confirmation dialog
- Replace image functionality with preview
- Automatic cleanup of old images when replacing
- Upload new images directly from admin
- Real-time preview of new images before saving
- Client-side validation in admin (2MB, image types)

### Version 1.3.0
- Added image upload functionality to frontend form
- Image preview before submission with client-side validation
- File size limit (2MB) and type validation (JPG, PNG, GIF)
- Remove image button to clear selection
- Image thumbnail column (50x50px) in admin list view
- Full image display in entry details (clickable for full size)
- Image URLs included in CSV export
- Server-side image validation and WordPress media handling

### Version 1.2.0
- Added CSV export functionality with "Export to CSV" button
- Export all data or filtered data by month
- Smart filename generation (includes month if filtered)
- UTF-8 BOM support for proper character encoding
- Security: Permission checks and nonce verification for exports

### Version 1.1.0
- Added automatic submission date tracking
- Added submission date column in admin interface
- Added month filter dropdown for filtering by submission period
- Made submission date column sortable
- Display submission date in entry edit screen

### Version 1.0.0
- Initial release
- Custom post type with name and email fields
- Frontend form with validation
- Admin interface with custom columns
