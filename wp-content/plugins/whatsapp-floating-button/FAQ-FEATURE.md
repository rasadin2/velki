# FAQ Feature Documentation

## Overview

The WhatsApp Floating Button plugin now includes a built-in FAQ (Frequently Asked Questions) accordion feature that displays within the WhatsApp popup. This allows you to answer common questions before users contact you via WhatsApp.

## Features

✅ **Accordion-Style FAQ**: Smooth expand/collapse animations
✅ **Unlimited FAQ Items**: Add as many questions and answers as needed
✅ **Multi-Language Support**: Full support for Bengali, English, and any language
✅ **Easy Management**: Simple admin interface to add, edit, and remove FAQs
✅ **Responsive Design**: Works perfectly on all devices
✅ **Keyboard Accessible**: Full keyboard navigation support
✅ **Custom Styling**: Matches your selected button color

## Configuration

### Step 1: Enable FAQ Section

1. Go to WordPress Admin → **WhatsApp Button**
2. Scroll down to **Enable FAQ Section**
3. Check the checkbox to enable
4. Save settings

### Step 2: Add FAQ Items

The plugin comes with 2 default FAQs in Bengali:

**Default FAQ 1:**
- **Question:** কিভাবে একাউন্ট খুলব?
- **Answer:** নাইন উইকেটসে গ্রাহক নিজে কোন অ্যাকাউন্ট খুলতে পারেনা...

**Default FAQ 2:**
- **Question:** কিভাবে ডিপোজিট করব?
- **Answer:** বিকাশ, নগদ, রকেট বা ব্যাংক ট্রান্সফারের মাধ্যমে...

### Step 3: Customize FAQs

**To Edit Existing FAQs:**
1. Modify the question text in the textarea
2. Modify the answer text in the larger textarea
3. Click **Save Settings**

**To Add More FAQs:**
1. Click the **+ Add Another FAQ** button
2. Enter your question
3. Enter your answer
4. Click **Save Settings**

**To Remove FAQs:**
1. Click the **Remove FAQ** button (red button below each FAQ except the first one)
2. Click **Save Settings**

## Usage Examples

### Bengali FAQs (Gaming/Betting Site)

**FAQ 1: Account Opening**
```
Question: কিভাবে একাউন্ট খুলব?
Answer: নাইন উইকেটসে গ্রাহক নিজে কোন অ্যাকাউন্ট খুলতে পারেনা, এই কারণে আমাদের নিজস্ব মাস্টার এজেন্ট এর ব্যবস্থা আছে। যারা আপনাদেরকে অ্যাকাউন্ট খুলে দিবে এবং আপনারা তাদের মাধ্যমে লেনদেন করতে পারবেন। অ্যাকাউন্ট খোলার জন্য আমাদের যেকোনো অনলাইন মাস্টার এজেন্ট এর সাথে হোয়াটসঅ্যাপে অথবা মেসেঞ্জারে যোগাযোগ করুন।
```

**FAQ 2: Deposit Methods**
```
Question: কিভাবে ডিপোজিট করব?
Answer: বিকাশ, নগদ, রকেট বা ব্যাংক ট্রান্সফারের মাধ্যমে আপনার মাস্টার এজেন্টকে টাকা পাঠান। তারপর তারা আপনার অ্যাকাউন্টে ব্যালেন্স যুক্ত করে দিবেন।
```

**FAQ 3: Withdrawal Process**
```
Question: কিভাবে উইথড্র করব?
Answer: আপনার অ্যাকাউন্ট থেকে টাকা উইথড্র করতে হলে আপনার মাস্টার এজেন্টের সাথে যোগাযোগ করুন। তারা আপনার অ্যাকাউন্ট থেকে টাকা কেটে নিয়ে বিকাশ, নগদ বা রকেটে পাঠিয়ে দিবেন।
```

### English FAQs (E-commerce)

**FAQ 1: Shipping**
```
Question: How long does shipping take?
Answer: Standard shipping takes 3-5 business days. Express shipping is available and takes 1-2 business days. Free shipping on orders over $50.
```

**FAQ 2: Returns**
```
Question: What is your return policy?
Answer: We accept returns within 30 days of purchase. Items must be unused and in original packaging. Contact us via WhatsApp to initiate a return.
```

**FAQ 3: Payment**
```
Question: What payment methods do you accept?
Answer: We accept credit cards, debit cards, PayPal, and bank transfers. All payments are secure and encrypted.
```

## User Experience

### How It Appears to Visitors

1. **User clicks WhatsApp button** → Popup opens
2. **Contact list displays** → Shows your 3 WhatsApp numbers
3. **FAQ section appears below** → If enabled
4. **User clicks a question** → Answer expands smoothly
5. **Click another question** → Previous answer closes, new answer opens
6. **Click same question again** → Answer collapses

### Visual Design

- **Question Style**: Bold text with chevron icon (▼)
- **Answer Style**: Lighter background with readable text
- **Active State**: Question background changes to your selected color
- **Hover Effect**: Subtle color change on hover
- **Animation**: Smooth expand/collapse transition

## Technical Details

### HTML Structure

```html
<div class="wfb-faq-section">
    <h4 class="wfb-faq-title">Frequently Asked Questions</h4>
    <div class="wfb-faq-accordion">
        <div class="wfb-faq-item">
            <button class="wfb-faq-question">
                <span>Your Question Here</span>
                <svg class="wfb-faq-icon">...</svg>
            </button>
            <div class="wfb-faq-answer">
                <p>Your answer here</p>
            </div>
        </div>
    </div>
</div>
```

### CSS Classes

- `.wfb-faq-section` - Main FAQ container
- `.wfb-faq-title` - "Frequently Asked Questions" heading
- `.wfb-faq-accordion` - Accordion wrapper
- `.wfb-faq-item` - Individual FAQ item
- `.wfb-faq-question` - Question button (clickable)
- `.wfb-faq-answer` - Answer content (collapsible)
- `.wfb-faq-icon` - Chevron icon
- `.wfb-active` - Active/open state class

### JavaScript Events

**Custom Events:**

```javascript
// When FAQ is toggled
$(document).on('wfb:faq:toggled', function(e, data) {
    console.log('Question:', data.question);
    console.log('Is Open:', data.isOpen);
});
```

**Public API:**

```javascript
// No special API needed - uses standard jQuery selectors
$('.wfb-faq-question').first().click(); // Open first FAQ
$('.wfb-faq-item.wfb-active .wfb-faq-question').click(); // Close active FAQ
```

## Customization

### Change FAQ Title

Edit this line in `whatsapp-floating-button.php` (line ~459):

```php
<h4 class="wfb-faq-title"><?php _e('Frequently Asked Questions', 'whatsapp-floating-button'); ?></h4>
```

### Custom CSS Styling

Add to your theme's custom CSS:

```css
/* Change FAQ title color */
.wfb-faq-title {
    color: #your-color;
}

/* Change question background */
.wfb-faq-question {
    background: #your-color;
}

/* Change answer text size */
.wfb-faq-answer p {
    font-size: 14px;
}

/* Remove border between FAQ items */
.wfb-faq-item {
    border: none;
}
```

### Hide FAQ Section Programmatically

```php
// In your theme's functions.php
add_filter('wfb_show_faq', '__return_false');
```

## Best Practices

### Question Writing Tips

1. **Be Specific**: "How do I create an account?" not "Account help?"
2. **Use User Language**: Match how your customers ask questions
3. **Keep Questions Short**: Aim for 10 words or less
4. **One Topic Per Question**: Don't combine multiple topics

### Answer Writing Tips

1. **Be Concise**: 2-4 sentences is ideal
2. **Be Clear**: Use simple language
3. **Be Complete**: Answer the full question
4. **Add Next Steps**: Tell users what to do next
5. **Use Line Breaks**: Break long answers into paragraphs

### Recommended FAQ Topics

**E-commerce:**
- Shipping times and costs
- Return/refund policy
- Payment methods
- Product availability
- Order tracking

**Services:**
- How to book/schedule
- Pricing information
- What to expect
- Cancellation policy
- Service area

**Gaming/Betting:**
- Account creation process
- Deposit methods
- Withdrawal process
- Minimum amounts
- Processing times

## Troubleshooting

### FAQ Section Not Showing

**Check 1: Enable FAQ**
- Go to WhatsApp Button settings
- Verify "Enable FAQ Section" is checked
- Save settings

**Check 2: Add FAQ Items**
- At least one FAQ must have both question and answer filled
- Empty FAQs are automatically hidden

**Check 3: Clear Cache**
- Clear WordPress cache
- Clear browser cache
- Test in incognito window

### FAQ Not Expanding

**Check 1: JavaScript Loaded**
- Open browser console (F12)
- Check for JavaScript errors
- Verify jQuery is loaded

**Check 2: CSS Conflicts**
- Check if other plugins override CSS
- Try disabling other plugins temporarily

### Bengali Text Not Displaying

**Check 1: Character Encoding**
- Database should use UTF-8 encoding
- File encoding should be UTF-8

**Check 2: Font Support**
- Some fonts don't support Bengali characters
- Default system fonts should work fine

## Analytics & Tracking

Track FAQ interactions with the custom event:

```javascript
$(document).on('wfb:faq:toggled', function(e, data) {
    // Google Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'faq_toggle', {
            'event_category': 'FAQ',
            'event_label': data.question,
            'value': data.isOpen ? 1 : 0
        });
    }

    // Google Tag Manager
    if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
            'event': 'faq_interaction',
            'faq_question': data.question,
            'faq_action': data.isOpen ? 'open' : 'close'
        });
    }
});
```

## Performance

- **CSS**: ~2KB additional
- **JavaScript**: ~500 bytes additional
- **Load Time Impact**: Negligible (<50ms)
- **No External Dependencies**: Uses existing jQuery

## Accessibility

✅ **Keyboard Navigation**: Tab to focus, Enter/Space to toggle
✅ **Screen Readers**: Proper ARIA labels and semantic HTML
✅ **Focus Indicators**: Clear visual focus states
✅ **Logical Order**: FAQs follow natural reading order

## Version History

**Version 1.1.0** (Current)
- Added FAQ accordion feature
- Bengali language support
- Dynamic add/remove FAQs in admin
- Smooth animations
- Keyboard accessibility
- Custom event tracking

**Version 1.0.0**
- Initial release with WhatsApp button
- 3 contact numbers
- Basic popup functionality

---

**Need Help?**
Contact plugin support for assistance with FAQ setup and customization.
