# Dynamic Features Documentation

## Overview

The WhatsApp Floating Button plugin now features fully dynamic WhatsApp number and FAQ management with easy add/remove functionality.

## What's New in Version 1.2.0

### ✨ Dynamic WhatsApp Numbers

**Before (v1.0-1.1):**
- Fixed 3 WhatsApp number fields
- No ability to add more or remove unused fields
- Cluttered admin interface with empty fields

**Now (v1.2.0):**
- ✅ **Unlimited WhatsApp Numbers**: Add as many as you need
- ✅ **Add Button**: Click "+ Add Another WhatsApp Number" to add new contacts
- ✅ **Remove Button**: Remove any contact except the first one
- ✅ **Clean Interface**: Only shows the contacts you actually use
- ✅ **Backward Compatible**: Old settings automatically migrated

### ✨ Dynamic FAQ Section

**Features:**
- ✅ **Unlimited FAQs**: Add as many questions as needed
- ✅ **Add Button**: Click "+ Add Another FAQ" to add new items
- ✅ **Remove Button**: Delete unwanted FAQs easily
- ✅ **Drag & Reorder**: (Coming in future version)
- ✅ **Pre-loaded Defaults**: Comes with 2 Bengali FAQs

## How to Use

### Adding WhatsApp Numbers

1. **Go to Settings**
   - WordPress Admin → WhatsApp Button

2. **Add Your First Number**
   - You'll see one default contact field
   - Enter phone number (e.g., `1234567890`)
   - Enter label (e.g., "Sales Support")

3. **Add More Numbers**
   - Click **"+ Add Another WhatsApp Number"**
   - Fill in the new contact details
   - Repeat as needed

4. **Remove Numbers**
   - Click the **"Remove Contact"** button (red button below each contact)
   - Note: You cannot remove the first contact to ensure at least one number exists

5. **Save Settings**
   - Click **"Save Settings"** at the bottom

### Adding FAQs

1. **Enable FAQ Section**
   - Check "Enable FAQ Section" checkbox

2. **Edit Default FAQs**
   - Two Bengali FAQs are pre-loaded
   - Edit the question and answer text

3. **Add More FAQs**
   - Click **"+ Add Another FAQ"**
   - Enter your question
   - Enter your answer

4. **Remove FAQs**
   - Click **"Remove FAQ"** button (red button below each FAQ)
   - Note: You cannot remove the first FAQ

5. **Save Settings**
   - Click **"Save Settings"**

## Admin Interface Screenshots

### WhatsApp Numbers Section

```
┌──────────────────────────────────────────────────┐
│ WhatsApp Numbers                                 │
├──────────────────────────────────────────────────┤
│ ┌──────────────────────────────────────────────┐ │
│ │ WhatsApp Contact #1                          │ │
│ │                                              │ │
│ │ Phone Number:                                │ │
│ │ [1234567890                               ]  │ │
│ │ Enter phone number in international format   │ │
│ │                                              │ │
│ │ Label:                                       │ │
│ │ [Sales Support                            ]  │ │
│ │ Descriptive label for this contact          │ │
│ └──────────────────────────────────────────────┘ │
│                                                  │
│ ┌──────────────────────────────────────────────┐ │
│ │ WhatsApp Contact #2                          │ │
│ │                                              │ │
│ │ Phone Number:                                │ │
│ │ [0987654321                               ]  │ │
│ │                                              │ │
│ │ Label:                                       │ │
│ │ [Technical Support                        ]  │ │
│ │                                              │ │
│ │ [Remove Contact]  ← Red button              │ │
│ └──────────────────────────────────────────────┘ │
│                                                  │
│ [+ Add Another WhatsApp Number]                  │
└──────────────────────────────────────────────────┘
```

### FAQ Section

```
┌──────────────────────────────────────────────────┐
│ Enable FAQ Section                               │
│ ☑ Show FAQ section in the popup                 │
├──────────────────────────────────────────────────┤
│ FAQ Items                                        │
│                                                  │
│ ┌──────────────────────────────────────────────┐ │
│ │ FAQ #1                                       │ │
│ │                                              │ │
│ │ Question:                                    │ │
│ │ [কিভাবে একাউন্ট খুলব?                    ]  │ │
│ │                                              │ │
│ │ Answer:                                      │ │
│ │ [নাইন উইকেটসে গ্রাহক নিজে...            ]  │ │
│ │                                              │ │
│ └──────────────────────────────────────────────┘ │
│                                                  │
│ ┌──────────────────────────────────────────────┐ │
│ │ FAQ #2                                       │ │
│ │                                              │ │
│ │ Question:                                    │ │
│ │ [কিভাবে ডিপোজিট করব?                    ]  │ │
│ │                                              │ │
│ │ Answer:                                      │ │
│ │ [বিকাশ, নগদ, রকেট বা ব্যাংক...          ]  │ │
│ │                                              │ │
│ │ [Remove FAQ]  ← Red button                  │ │
│ └──────────────────────────────────────────────┘ │
│                                                  │
│ [+ Add Another FAQ]                              │
└──────────────────────────────────────────────────┘
```

## Technical Details

### Data Structure

**WhatsApp Numbers:**
```php
$options['whatsapp_numbers'] = array(
    0 => array(
        'number' => '1234567890',
        'label' => 'Sales Support'
    ),
    1 => array(
        'number' => '0987654321',
        'label' => 'Technical Support'
    ),
    // ... unlimited entries
);
```

**FAQ Items:**
```php
$options['faq_items'] = array(
    0 => array(
        'question' => 'কিভাবে একাউন্ট খুলব?',
        'answer' => 'নাইন উইকেটসে গ্রাহক...'
    ),
    1 => array(
        'question' => 'কিভাবে ডিপোজিট করব?',
        'answer' => 'বিকাশ, নগদ, রকেট...'
    ),
    // ... unlimited entries
);
```

### JavaScript Functions

**Add WhatsApp Number:**
```javascript
$('#wfb-add-number').on('click', function() {
    // Appends new contact fields to container
    // Increments counter for unique field names
});
```

**Remove WhatsApp Number:**
```javascript
$('.wfb-remove-number').on('click', function() {
    // Removes parent contact container
});
```

**Add FAQ:**
```javascript
$('#wfb-add-faq').on('click', function() {
    // Appends new FAQ fields to container
    // Increments counter for unique field names
});
```

**Remove FAQ:**
```javascript
$('.wfb-remove-faq').on('click', function() {
    // Removes parent FAQ container
});
```

## Backward Compatibility

The plugin maintains **100% backward compatibility** with previous versions:

### Old Settings Format (v1.0-1.1)
```php
$options = array(
    'number_1' => '1234567890',
    'label_1' => 'Sales',
    'number_2' => '0987654321',
    'label_2' => 'Support',
    'number_3' => '',
    'label_3' => ''
);
```

### Automatic Migration

When you save settings in v1.2.0, old format is automatically converted to new format:

```php
// Old format detected → Migrated to new format
$options['whatsapp_numbers'] = array(
    0 => array('number' => '1234567890', 'label' => 'Sales'),
    1 => array('number' => '0987654321', 'label' => 'Support')
);
```

### Frontend Rendering

The frontend checks for both formats:
1. **First**: Tries new dynamic format (`whatsapp_numbers`)
2. **Fallback**: Uses old format (`number_1`, `number_2`, `number_3`)

This ensures your button keeps working even if you downgrade the plugin.

## Benefits

### For Administrators

1. **Unlimited Flexibility**
   - Add as many contacts as your business needs
   - Not limited to arbitrary 3-contact restriction

2. **Cleaner Interface**
   - Only see the fields you actually use
   - No empty fields cluttering the admin

3. **Easy Management**
   - Add new contacts in seconds
   - Remove outdated contacts with one click

4. **Better Organization**
   - Number contacts automatically (Contact #1, #2, #3...)
   - Clear visual separation between contacts

### For Users

1. **Better Experience**
   - More contact options available
   - Comprehensive FAQ coverage
   - Professional, organized presentation

2. **Faster Support**
   - Multiple specialized contact points
   - FAQ answers without contacting support

### For Developers

1. **Clean Code**
   - Dynamic array structure
   - No hard-coded limits
   - Easy to extend

2. **Future-Proof**
   - Scalable architecture
   - Ready for additional features
   - Proper data normalization

## Best Practices

### WhatsApp Numbers

**Do:**
- ✅ Add contacts with clear, descriptive labels
- ✅ Use international format for phone numbers
- ✅ Remove outdated or unused contacts
- ✅ Keep most important contacts at the top

**Don't:**
- ❌ Add duplicate numbers
- ❌ Leave phone number field empty
- ❌ Use spaces or special characters in numbers
- ❌ Exceed 10 contacts (for UI clarity)

### FAQs

**Do:**
- ✅ Keep questions concise (under 15 words)
- ✅ Write clear, helpful answers
- ✅ Organize FAQs by topic
- ✅ Update FAQs based on common questions

**Don't:**
- ❌ Create duplicate questions
- ❌ Write overly long answers
- ❌ Leave questions or answers empty
- ❌ Exceed 15 FAQs (for user experience)

## Limitations

### Current Limitations

1. **No Drag-and-Drop Reordering**
   - Contacts and FAQs cannot be reordered
   - Workaround: Remove and re-add in desired order
   - Coming in future version

2. **No Bulk Import**
   - Cannot import multiple contacts from CSV
   - Must add manually one by one
   - Feature request for future version

3. **No Icons/Images**
   - Cannot add custom icons per contact
   - All contacts use WhatsApp icon
   - May be added in future version

## Troubleshooting

### WhatsApp Numbers Not Showing

**Problem:** Added numbers but they don't appear on frontend

**Solutions:**
1. Verify number fields are not empty
2. Check that you clicked "Save Settings"
3. Clear website cache
4. Test in incognito window

### Remove Button Not Working

**Problem:** Clicking "Remove Contact" or "Remove FAQ" doesn't work

**Solutions:**
1. Check JavaScript console for errors
2. Ensure jQuery is loaded
3. Try disabling other plugins temporarily
4. Clear browser cache

### Settings Not Saving

**Problem:** Changes disappear after saving

**Solutions:**
1. Check for PHP errors in debug log
2. Verify proper permissions
3. Ensure no other plugin conflicts
4. Increase `max_input_vars` in php.ini if you have many contacts

## Migration Guide

### From v1.0-1.1 to v1.2.0

**Automatic Migration:**
1. Update plugin to v1.2.0
2. Go to WhatsApp Button settings
3. Your existing numbers will appear in new format
4. Click "Save Settings" to complete migration
5. Done! No manual work required

**Manual Verification:**
1. Check all numbers are present
2. Verify labels are correct
3. Test frontend display
4. Test add/remove functionality

## Future Enhancements

Planned features for upcoming versions:

- [ ] Drag-and-drop reordering
- [ ] Bulk import from CSV
- [ ] Custom icons per contact
- [ ] Contact groups/categories
- [ ] Schedule-based contact display
- [ ] A/B testing for different contacts
- [ ] Analytics per contact
- [ ] FAQ search functionality
- [ ] FAQ categories/tags

## Version History

**Version 1.2.0** (Current)
- Added dynamic WhatsApp number management
- Added dynamic FAQ management
- Unlimited contacts support
- Unlimited FAQs support
- Add/remove buttons for easy management
- Backward compatibility with v1.0-1.1

**Version 1.1.0**
- Added FAQ accordion feature
- Bengali language support
- 3 fixed WhatsApp numbers

**Version 1.0.0**
- Initial release
- 3 fixed WhatsApp contact numbers
- Basic popup functionality

---

**Need Help?**
For support with dynamic features, contact plugin support or check the main README.md file.
