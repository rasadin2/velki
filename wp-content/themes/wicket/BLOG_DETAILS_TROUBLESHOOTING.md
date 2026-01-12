# Blog Details Troubleshooting Guide

## Common Errors and Solutions

### "option dom not found" Error

This error can occur for several reasons. Follow these diagnostic steps:

---

## Diagnostic Steps

### Step 1: Identify Error Location

**Check WordPress Debug Log:**
```php
// Add to wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check `/wp-content/debug.log` for detailed errors.

**Check Browser Console:**
1. Open browser developer tools (F12)
2. Go to Console tab
3. Look for JavaScript errors
4. Note the file and line number

---

### Step 2: Common Causes and Fixes

#### Cause #1: Missing WordPress Data (Most Common)

**Symptoms:**
- Error occurs on newly created posts
- Error with posts without categories
- Error with posts without featured images

**Solution:**
```php
// Already fixed in content-single.php with fallbacks:

// Back button fallback
$archive_link = get_post_type_archive_link('post');
if ( ! $archive_link ) {
    $archive_link = home_url('/');
}

// Category check
$categories = get_the_category();
if ( ! empty( $categories ) ) {
    // Display category
}

// Featured image check
if ( has_post_thumbnail() ) {
    // Display image
}
```

**Test:**
1. Create a new blog post
2. Assign at least one category
3. Add a featured image
4. Publish and view

---

#### Cause #2: JavaScript Errors

**Symptoms:**
- Share button not working
- Console shows script errors
- Navigation.share or clipboard errors

**Solution:**
Enhanced error handling already added to `content-single.php`:

```javascript
function sharePost() {
    try {
        // Primary: Web Share API
        if (navigator.share) {
            navigator.share({...})
            .catch(() => copyToClipboard());
        } else {
            // Secondary: Clipboard API
            copyToClipboard();
        }
    } catch (error) {
        // Tertiary: Alert fallback
        alert('Share URL: ' + url);
    }
}

// Multiple clipboard fallback methods
function fallbackCopyToClipboard(text) {
    // Creates temporary textarea for older browsers
}
```

**Test:**
1. Click share button
2. Check browser console for errors
3. Verify copy/share functionality works

---

#### Cause #3: Theme Conflicts

**Symptoms:**
- Styling not applying
- Elements missing or misaligned
- Other theme features broken

**Solution:**

1. **Check Theme Parent/Child:**
```bash
# Verify Wicket is active theme
wp theme list
```

2. **Check CSS Loading:**
```bash
# View source, look for:
<link rel="stylesheet" href=".../blog-details.css" ...>
```

3. **Check Functions.php:**
```php
// Verify enqueue is present
if ( is_single() && get_post_type() === 'post' ) {
    wp_enqueue_style( 'wicket-blog-details', ... );
}
```

**Fix:** Clear all caches:
```bash
# WordPress cache
wp cache flush

# Object cache (if using Redis/Memcached)
wp cache flush
```

Or via admin:
- Settings → Permalink Settings → Save Changes (flushes rewrite rules)
- Performance plugins → Clear all caches

---

#### Cause #4: Plugin Conflicts

**Symptoms:**
- Works in one environment, fails in another
- Error only appears with certain plugins active
- DOM manipulation conflicts

**Solution:**

1. **Identify Conflicting Plugin:**
```bash
# Disable all plugins
wp plugin deactivate --all

# Test blog details page
# Re-enable plugins one by one
wp plugin activate <plugin-name>
# Test after each activation
```

2. **Common Conflicting Plugins:**
- Page builders (Elementor, Divi) - May override single.php
- Caching plugins - May serve stale HTML
- SEO plugins - May inject scripts that interfere
- Share plugins - May conflict with native share button

3. **Compatibility Check:**
```php
// Add to functions.php for debugging
add_action('wp_footer', function() {
    if (is_single() && current_user_can('manage_options')) {
        echo '<!-- Blog Details Template Active -->';
    }
});
```

---

#### Cause #5: Missing Template File

**Symptoms:**
- 404 error
- Blank page
- Falls back to default template

**Solution:**

1. **Verify File Exists:**
```bash
ls wp-content/themes/wicket/template-parts/post/content-single.php
```

2. **Check File Permissions:**
```bash
# Should be 644
chmod 644 wp-content/themes/wicket/template-parts/post/content-single.php
```

3. **Verify Template Call:**
```php
// In single.php:
if (get_post_type($post) == 'post') {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'template-parts/post/content', 'single' );
    }
}
```

---

## Error Messages Dictionary

### PHP Errors

**"Undefined index"**
```
Solution: Check array key exists before accessing
if ( isset($array['key']) ) { ... }
```

**"Call to undefined function"**
```
Solution: Function not available, check WordPress version or plugin dependency
```

**"Headers already sent"**
```
Solution: Remove whitespace before <?php in template files
```

### JavaScript Errors

**"navigator.share is not a function"**
```
Solution: Already handled with fallback to clipboard
Browser doesn't support Web Share API (normal)
```

**"Cannot read property 'writeText' of undefined"**
```
Solution: Already handled with execCommand fallback
Older browser without Clipboard API
```

**"Uncaught TypeError: ... is not a function"**
```
Solution: Check function name spelling and scope
Ensure function is defined before being called
```

### WordPress Errors

**"The post type 'post' is not registered"**
```
Solution: Very rare, indicates core WordPress issue
Try reinstalling WordPress core
```

**"Trying to access array offset on value of type null"**
```
Solution: Check if variable is array before accessing
Already handled with empty() checks
```

---

## Testing Checklist

### Before Publishing

- [ ] Create test post with category
- [ ] Add featured image
- [ ] Add content with headings
- [ ] Add tags
- [ ] Publish post
- [ ] View on frontend
- [ ] Check browser console (F12)
- [ ] Test back button
- [ ] Test share button
- [ ] Test on mobile device
- [ ] Test in incognito/private mode

### After Plugin/Theme Updates

- [ ] Clear all caches
- [ ] Test blog details page
- [ ] Check browser console
- [ ] Verify styles loading
- [ ] Test all interactive elements

---

## Debug Mode for Blog Details

Add this to `functions.php` for enhanced debugging:

```php
/**
 * Blog Details Debug Mode
 * Remove after troubleshooting
 */
add_action('wp_footer', function() {
    if (is_single() && get_post_type() === 'post' && current_user_can('manage_options')) {
        $debug_info = array(
            'Template' => 'content-single.php',
            'CSS Loaded' => wp_style_is('wicket-blog-details', 'enqueued') ? 'Yes' : 'No',
            'Post ID' => get_the_ID(),
            'Categories' => count(get_the_category()),
            'Has Thumbnail' => has_post_thumbnail() ? 'Yes' : 'No',
            'Post Type' => get_post_type()
        );

        echo '<div style="position:fixed;bottom:0;right:0;background:#000;color:#fff;padding:10px;font-size:12px;z-index:99999;">';
        echo '<strong>Blog Details Debug:</strong><br>';
        foreach ($debug_info as $key => $value) {
            echo "$key: $value<br>";
        }
        echo '</div>';
    }
});
```

---

## Performance Troubleshooting

### Slow Loading

**Check:**
1. Large featured images → Optimize/compress
2. Too many plugins → Disable unused
3. No caching → Enable WP Super Cache or similar
4. Slow server → Check hosting resources

**Optimize:**
```bash
# Optimize images
wp media regenerate --yes

# Check database
wp db optimize
```

### CSS Not Loading

**Check:**
```php
// In browser, view source and find:
<link rel='stylesheet' id='wicket-blog-details-css' href='...' />

// If missing, check functions.php enqueue
// If present but not working, check file permissions
```

---

## Quick Fixes

### Reset Everything

```bash
# 1. Clear caches
wp cache flush

# 2. Regenerate permalink structure
wp rewrite flush

# 3. Regenerate CSS
# Delete transients
wp transient delete --all

# 4. Verify files exist
ls -la wp-content/themes/wicket/template-parts/post/content-single.php
ls -la wp-content/themes/wicket/assets/css/blog-details.css
```

### Force Reload CSS

```php
// In functions.php, change version number:
wp_enqueue_style(
    'wicket-blog-details',
    get_template_directory_uri() . '/assets/css/blog-details.css',
    array('wicket-style'),
    '1.0.1' // Change this number
);
```

### Restore Backup

If issues persist:
1. Backup modified files
2. Restore original single.php
3. Remove content-single.php
4. Remove blog-details.css enqueue
5. Test if regular posts work
6. Re-implement one file at a time

---

## Getting Help

### Information to Provide

When asking for support, include:

1. **WordPress Version:**
```bash
wp core version
```

2. **PHP Version:**
```bash
php -v
```

3. **Active Theme:**
```bash
wp theme list
```

4. **Active Plugins:**
```bash
wp plugin list --status=active
```

5. **Error Message:**
- Full text
- File and line number
- Browser console output

6. **Steps to Reproduce:**
- What you did
- What you expected
- What actually happened

### Support Resources

- WordPress Codex: https://codex.wordpress.org/
- WordPress Support Forums: https://wordpress.org/support/
- Theme Documentation: Check theme author support
- Browser DevTools: F12 for console errors

---

## Prevention Tips

### Best Practices

1. **Always Test Locally First**
   - Use Local WP or XAMPP
   - Test changes before production

2. **Use Child Theme**
   - Prevent updates from overwriting changes
   - Easier to troubleshoot

3. **Version Control**
   - Use Git for tracking changes
   - Easy rollback if issues occur

4. **Regular Backups**
   - Daily database backups
   - Weekly file backups
   - Before major changes

5. **Update Regularly**
   - WordPress core
   - Plugins
   - Themes
   - But test in staging first!

6. **Monitor Errors**
   - Enable WP_DEBUG_LOG
   - Check logs regularly
   - Fix issues promptly

---

## Advanced Troubleshooting

### Database Check

```sql
-- Check post meta
SELECT * FROM wp_postmeta WHERE post_id = <YOUR_POST_ID>;

-- Check taxonomies
SELECT * FROM wp_term_relationships WHERE object_id = <YOUR_POST_ID>;
```

### Server Logs

```bash
# Apache error log
tail -f /var/log/apache2/error.log

# PHP error log
tail -f /var/log/php-fpm/error.log

# WordPress debug log
tail -f wp-content/debug.log
```

### Memory Issues

```php
// Increase memory limit in wp-config.php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
```

---

**Last Updated:** 2026-01-12
**Version:** 1.0.0
**For:** Wicket Theme Blog Details
