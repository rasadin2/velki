# WhatsApp Floating Button

A professional WordPress plugin that adds a floating WhatsApp button to your website. When clicked, it displays a popup box with up to three customizable WhatsApp contact numbers.

## Features

- ✅ **Floating Button**: Eye-catching WhatsApp button with pulse animation
- ✅ **Multi-Contact Support**: Configure up to 3 different WhatsApp numbers
- ✅ **Custom Labels**: Add descriptive labels for each contact
- ✅ **Flexible Positioning**: Choose between bottom-right or bottom-left placement
- ✅ **Customizable Colors**: Match your brand with custom button colors
- ✅ **Fully Responsive**: Works perfectly on desktop, tablet, and mobile devices
- ✅ **Modern Design**: Clean, professional UI with smooth animations
- ✅ **Accessibility Ready**: Keyboard navigation and screen reader support
- ✅ **Analytics Ready**: Built-in tracking for Google Analytics and GTM
- ✅ **Developer Friendly**: Custom events and public API for extensions
- ✅ **Lightweight**: Optimized code with minimal performance impact
- ✅ **Translation Ready**: Fully internationalized with text domain support

## Installation

1. Download the plugin folder
2. Upload to `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to **WhatsApp Button** in the admin menu to configure settings

## Configuration

### Admin Settings

Navigate to **WhatsApp Button** in your WordPress admin menu to configure:

1. **WhatsApp Numbers**: Enter up to 3 phone numbers in international format (without + or spaces)
   - Example: `1234567890` for country code +1, number 234-567-890

2. **Contact Labels**: Add descriptive labels for each number
   - Example: "Sales Support", "Technical Support", "Customer Service"

3. **Button Position**: Choose between:
   - Bottom Right (default)
   - Bottom Left

4. **Button Color**: Select a custom color or use the default WhatsApp green (#25D366)

### Phone Number Format

- Enter numbers in international format
- Remove all spaces, dashes, and the + symbol
- Example formats:
  - USA: `12125551234` (country code 1 + area code + number)
  - UK: `447700900000` (country code 44 + number)
  - India: `919876543210` (country code 91 + number)

## Usage

Once configured, the floating WhatsApp button will automatically appear on all pages of your website.

### User Interaction

1. Visitors click the floating WhatsApp button
2. A popup box appears with your configured contacts
3. Visitors click on a contact to open WhatsApp chat in a new tab
4. They can start chatting immediately

### For Developers

#### Public API

The plugin exposes a global `WFB` object with the following methods:

```javascript
// Open the popup
WFB.open();

// Close the popup
WFB.close();

// Toggle the popup
WFB.toggle();
```

#### Custom Events

Listen to these events for custom functionality:

```javascript
// When plugin is ready
$(document).on('wfb:ready', function() {
    console.log('WhatsApp button ready');
});

// When popup opens
$(document).on('wfb:popup:opened', function() {
    console.log('Popup opened');
});

// When popup closes
$(document).on('wfb:popup:closed', function() {
    console.log('Popup closed');
});

// When a contact is clicked
$(document).on('wfb:contact:clicked', function(e, data) {
    console.log('Contact clicked:', data.label, data.number);
});
```

## Analytics Integration

The plugin automatically tracks WhatsApp contact clicks if you have:

- **Google Analytics (gtag.js)**: Tracks as `whatsapp_click` event
- **Google Tag Manager**: Pushes `whatsapp_click` event to dataLayer

### Google Analytics Events

```javascript
Event Category: WhatsApp
Event Action: whatsapp_click
Event Label: [Contact Label]
Event Value: [Phone Number]
```

### GTM DataLayer

```javascript
{
    'event': 'whatsapp_click',
    'whatsapp_label': '[Contact Label]',
    'whatsapp_number': '[Phone Number]'
}
```

## Customization

### CSS Customization

You can override styles by adding custom CSS to your theme:

```css
/* Change button size */
.wfb-button {
    width: 70px;
    height: 70px;
}

/* Customize popup width */
.wfb-popup {
    width: 400px;
}

/* Change contact item hover color */
.wfb-contact-item:hover {
    background: #128C7E;
}
```

### Hide on Specific Pages

Add this code to your theme's `functions.php`:

```php
add_action('wp_footer', function() {
    if (is_page('contact')) {
        echo '<style>.wfb-container { display: none !important; }</style>';
    }
}, 99);
```

## Browser Compatibility

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- jQuery (included with WordPress)

## File Structure

```
whatsapp-floating-button/
├── assets/
│   ├── css/
│   │   └── frontend.css
│   └── js/
│       └── frontend.js
├── whatsapp-floating-button.php
└── README.md
```

## Changelog

### Version 1.0.0
- Initial release
- Floating WhatsApp button with popup
- Support for 3 WhatsApp numbers
- Custom labels and colors
- Position selection (bottom-right/bottom-left)
- Responsive design
- Analytics integration
- Accessibility features
- Developer API

## Support

For support, feature requests, or bug reports, please contact the plugin author.

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- WhatsApp icon: Official WhatsApp brand assets
- Developed with modern WordPress best practices
- Built with accessibility in mind

## Roadmap

Planned features for future versions:

- [ ] More position options (corners, sides)
- [ ] Custom button text option
- [ ] Welcome message customization
- [ ] Schedule display times
- [ ] Page-specific visibility rules
- [ ] Multiple button styles
- [ ] Animation options
- [ ] RTL language support
- [ ] More analytics integrations

## FAQ

**Q: How many WhatsApp numbers can I add?**
A: Currently, you can configure up to 3 WhatsApp numbers. If you need more, contact the developer for a custom solution.

**Q: Will this slow down my website?**
A: No, the plugin is optimized for performance with minimal CSS and JavaScript. It loads asynchronously and doesn't block page rendering.

**Q: Can I hide the button on mobile devices?**
A: Yes, you can add custom CSS to hide it on specific screen sizes using media queries.

**Q: Does it work with page builders?**
A: Yes, the plugin works with all major page builders including Elementor, Divi, Beaver Builder, etc.

**Q: Can I change the button icon?**
A: Currently, the WhatsApp icon is standard. Custom icons may be added in future versions.

**Q: Is it translation ready?**
A: Yes, the plugin is fully internationalized with the `whatsapp-floating-button` text domain.
