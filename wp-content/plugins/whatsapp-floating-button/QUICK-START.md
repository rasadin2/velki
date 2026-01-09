# Quick Start Guide - WhatsApp Floating Button

## 🚀 Get Started in 3 Minutes

### Step 1: Activate (30 seconds)
1. Go to **Plugins** → **Installed Plugins**
2. Find **WhatsApp Floating Button**
3. Click **Activate**

### Step 2: Configure (2 minutes)
1. Go to **WhatsApp Button** in admin menu
2. Enter your WhatsApp numbers (without + or spaces)
   - Example: `12125551234`
3. Add labels for each number
   - Example: "Sales Support"
4. Choose position (Bottom Right or Bottom Left)
5. Select button color (optional)
6. Click **Save Settings**

### Step 3: Test (30 seconds)
1. Visit your website
2. See the floating WhatsApp button
3. Click to open popup
4. Click a contact to test

## ✅ That's It!

Your WhatsApp button is now live on your website!

---

## 📱 Phone Number Format

**Correct Format:**
- USA: `12125551234` (1 + area + number)
- UK: `447700900000` (44 + number)
- India: `919876543210` (91 + number)

**Remove:**
- ❌ Plus sign (+)
- ❌ Spaces
- ❌ Dashes (-)
- ❌ Parentheses ()

---

## 🎨 Quick Customization

### Hide on Mobile
Appearance → Customize → Additional CSS:
```css
@media (max-width: 768px) {
    .wfb-container { display: none; }
}
```

### Change Size
```css
.wfb-button {
    width: 70px;
    height: 70px;
}
```

---

## ❓ Need Help?

**Button not showing?**
- Check: At least 1 number is saved
- Clear: Website cache
- Try: Incognito window

**Link not working?**
- Check: Phone number format
- Verify: No spaces or + symbol

---

## 📚 Full Documentation
See `README.md` and `INSTALLATION.md` for complete details.

**Version:** 1.0.0
