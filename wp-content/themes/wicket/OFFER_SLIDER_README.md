# Offer List Slider Shortcode - Implementation Guide

## Quick Start

The `[offer_list_slider]` shortcode displays promotional offers in an interactive slider with customizable desktop/mobile layouts.

### Basic Usage
```
[offer_list_slider]
```
This displays a slider with 3 items on desktop, 1 on mobile, with autoplay enabled.

## Shortcode Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `desktop_items` | Number | 3 | Number of slides visible on desktop (>768px) |
| `mobile_items` | Number | 1 | Number of slides visible on mobile (≤768px) |
| `autoplay` | Boolean | true | Enable/disable automatic sliding |
| `autoplay_speed` | Number | 3000 | Autoplay interval in milliseconds |
| `limit` | Number | -1 | Number of offers to display (-1 = all) |
| `order` | String | DESC | Sort order (ASC or DESC) |
| `orderby` | String | date | Sort by (date, title, menu_order, etc.) |

## Usage Examples

### Example 1: Basic Slider
```
[offer_list_slider]
```
- Desktop: Shows 3 items
- Mobile: Shows 1 item
- Autoplay: Enabled (3 seconds)

### Example 2: Custom Desktop/Mobile Layout
```
[offer_list_slider desktop_items="4" mobile_items="2"]
```
- Desktop: Shows 4 items
- Mobile: Shows 2 items
- Autoplay: Enabled (3 seconds)

### Example 3: Disable Autoplay
```
[offer_list_slider desktop_items="3" mobile_items="1" autoplay="false"]
```
- Desktop: Shows 3 items
- Mobile: Shows 1 item
- Autoplay: Disabled (manual navigation only)

### Example 4: Slower Autoplay
```
[offer_list_slider desktop_items="2" mobile_items="1" autoplay_speed="5000"]
```
- Desktop: Shows 2 items
- Mobile: Shows 1 item
- Autoplay: Enabled (5 seconds interval)

### Example 5: Limited Offers with Custom Order
```
[offer_list_slider limit="6" orderby="title" order="ASC" desktop_items="3" mobile_items="1"]
```
- Shows only 6 offers
- Sorted alphabetically by title
- Desktop: Shows 3 items
- Mobile: Shows 1 item

## Slider Features

### Navigation
- **Previous Arrow (Left):** Navigate to previous slide(s)
- **Next Arrow (Right):** Navigate to next slide(s)
- **Keyboard Support:** Not implemented (can be added if needed)

### Autoplay Behavior
- Automatically advances to next slide after specified interval
- Pauses when user hovers over slider
- Resumes when mouse leaves slider area
- Loops back to beginning when reaching the end
- Stops at last slide if autoplay is disabled

### Responsive Behavior
| Screen Size | Breakpoint | Items Shown |
|-------------|------------|-------------|
| Desktop | > 768px | `desktop_items` value |
| Mobile | ≤ 768px | `mobile_items` value |

### Button States
- Previous button disabled when at first slide
- Next button disabled when at last slide (if not looping)
- Disabled buttons have reduced opacity (30%)

## Design Specifications

### Slider Layout
- Maximum width: 1200px
- Padding: 40px (top/bottom), 20px (left/right)
- Slide gap: 30px between cards

### Navigation Arrows
- **Desktop:** 50px diameter circles
- **Mobile:** 40px diameter circles
- Background: White with shadow
- Hover effect: Light gray background
- Icon size: 24px (desktop), 20px (mobile)

### Card Design
- Border radius: 12px
- Shadow: 0 4px 6px rgba(0, 0, 0, 0.1)
- Hover shadow: 0 8px 15px rgba(0, 0, 0, 0.2)
- Hover transform: translateY(-5px)
- Transition: 0.3s ease for all effects

### Header Image
- Height: 200px
- Object fit: cover
- Badge position: Top right (15px from edges)
- Badge background: Purple gradient

### Card Body
- Padding: 25px all sides
- Icon wrapper: 70x70px with 15px border-radius
- Title: 24px, font-weight 700
- Description: 15px, line-height 1.6
- Prize section: Light blue background

### Button
- Full width CTA button
- Padding: 14px vertical, 24px horizontal
- Border radius: 8px
- Gradient background (customizable)
- Arrow animation on hover

## Technical Details

### JavaScript Functionality
- Vanilla JavaScript (no jQuery dependency)
- Self-contained IIFE for each slider instance
- Unique ID generation prevents conflicts
- Window resize debouncing (250ms)
- Automatic cleanup on slider destruction

### Performance Optimizations
- CSS transforms for smooth animations
- Flexbox layout for efficient rendering
- Minimal DOM manipulation
- Optimized event listeners
- Debounced resize handlers

### Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (with potential minor styling differences)
- Mobile browsers (iOS Safari, Chrome Mobile)
- Responsive design tested on various screen sizes

## Multiple Sliders on Same Page

The shortcode supports multiple instances on the same page:

```
<!-- Slider 1: Featured Offers -->
[offer_list_slider limit="5" desktop_items="3" mobile_items="1"]

<!-- Slider 2: Recent Offers -->
[offer_list_slider limit="6" orderby="date" desktop_items="2" mobile_items="1" autoplay="false"]
```

Each slider:
- Has a unique ID
- Operates independently
- Maintains separate state
- Has isolated JavaScript scope

## Customization Options

### Change Slider Title
Edit line 974 in `custom-post-type-offer-list.php`:
```php
<h2 class="offer-slider-title">সাইন করুন</h2>
```

### Adjust Slide Gap
Edit line 759 in `custom-post-type-offer-list.php`:
```css
gap: 30px; /* Change this value */
```

### Modify Transition Speed
Edit line 758 in `custom-post-type-offer-list.php`:
```css
transition: transform 0.5s ease-in-out; /* Adjust duration */
```

### Change Breakpoint
Edit line 951 and line 1078 in `custom-post-type-offer-list.php`:
```css
@media (max-width: 768px) { /* Change breakpoint */
```
```javascript
return window.innerWidth <= 768 ? mobileItems : desktopItems;
```

## Troubleshooting

### Slider Not Displaying
1. Verify you have published offers in WordPress Admin → Offer List
2. Check that the shortcode is correct: `[offer_list_slider]`
3. Clear browser cache and WordPress cache
4. Check browser console for JavaScript errors

### Autoplay Not Working
1. Ensure `autoplay="true"` (not "yes" or "1")
2. Verify you have more offers than `desktop_items` count
3. Check browser console for errors
4. Try disabling browser extensions

### Navigation Arrows Not Working
1. Check browser console for JavaScript errors
2. Verify slider container has unique ID
3. Ensure no CSS conflicts with arrow buttons
4. Test in different browser

### Responsive Issues
1. Test the exact breakpoint (768px)
2. Verify `mobile_items` is set correctly
3. Check for CSS conflicts
4. Test on actual mobile device, not just browser resize

### Layout Breaking
1. Check parent container width
2. Verify no conflicting CSS from theme
3. Inspect element to see computed styles
4. Try adding `!important` to key CSS rules (temporary debug)

## Advanced Usage

### Combine with Category Filter
First, modify the shortcode to accept category parameter, then:
```php
// In custom-post-type-offer-list.php, add to $args array:
if ( ! empty( $atts['category'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'offer-category',
            'field'    => 'slug',
            'terms'    => $atts['category'],
        ),
    );
}
```

Usage:
```
[offer_list_slider category="featured"]
```

### Add Dots Navigation
Add this CSS and JavaScript to implement dots:
```css
.offer-slider-dots {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}
.offer-slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ddd;
    cursor: pointer;
}
.offer-slider-dot.active {
    background: #2271b1;
}
```

### Touch/Swipe Support
Consider adding a library like Hammer.js or implement custom touch events:
```javascript
let startX = 0;
container.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
});
container.addEventListener('touchend', (e) => {
    const endX = e.changedTouches[0].clientX;
    if (startX - endX > 50) nextSlide();
    if (endX - startX > 50) prevSlide();
});
```

## Performance Considerations

### Loading Speed
- Slider initializes after DOM content loaded
- No external library dependencies
- Inline CSS for critical styles
- Minimal JavaScript footprint

### Animation Performance
- Uses CSS transforms (GPU accelerated)
- Avoids layout thrashing
- Debounced resize handlers
- RequestAnimationFrame for smooth animations

### Memory Management
- Event listeners properly scoped
- No memory leaks from intervals
- Automatic cleanup on navigation
- Efficient DOM queries

## Best Practices

1. **Item Count:** Keep `desktop_items` between 2-4 for optimal viewing
2. **Mobile Items:** Usually 1 or 2 for mobile devices
3. **Autoplay Speed:** 3000-5000ms provides good user experience
4. **Offer Limit:** Limit to 10-15 offers for performance
5. **Image Optimization:** Compress featured images (800x400px recommended)
6. **Content Length:** Keep descriptions concise for consistent card heights
7. **Testing:** Always test on actual mobile devices
8. **Accessibility:** Consider adding ARIA labels for screen readers

## Related Files

- **Main Implementation:** `wp-content/themes/wicket/inc/custom-post-type-offer-list.php`
- **Documentation:** `wp-content/themes/wicket/inc/shortcode-settings.php`
- **Theme Functions:** `wp-content/themes/wicket/functions.php` (line 316)

## Admin Documentation

Full documentation available in WordPress Admin:
**Appearance → Shortcode Docs → Section #11**

## Support

For customization requests or technical issues:
1. Check this README first
2. Review browser console for errors
3. Test in default WordPress theme (Twenty Twenty-Three)
4. Contact theme developer with specific details

---

**Created:** 2026-01-10
**Theme:** Wicket
**WordPress:** 5.0+
**Shortcode:** `[offer_list_slider]`
