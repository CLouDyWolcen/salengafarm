# Local Testing Guide - Multi-Brand Setup

## Test Different Brands on Your Computer

---

## Step 1: Edit Windows Hosts File

1. **Open Notepad as Administrator:**
   - Right-click Notepad
   - Click "Run as administrator"

2. **Open hosts file:**
   - Click File → Open
   - Navigate to: `C:\Windows\System32\drivers\etc\`
   - Change file type to "All Files (*.*)"
   - Open the file named `hosts`

3. **Add these lines at the bottom:**
```
127.0.0.1 esthersgarden.local
127.0.0.1 salengafarm.local
```

4. **Save and close**

---

## Step 2: Start Laravel Server

Open terminal in your project:
```bash
cd C:\CODING\my_Inventory
php artisan serve
```

---

## Step 3: Test Both Brands

Open your browser and visit:

### Salenga Farm (Welcome Page):
```
http://salengafarm.local:8000
```
**Shows:**
- "Welcome to Salenga Farm"
- "Discover our wide range of available plants"
- Salenga Farm logo

### Esther's Garden (About Us Page):
```
http://esthersgarden.local:8000
```
**Shows:**
- "About Esther's Garden"
- Company description
- Feature list with checkmarks
- Esther's Garden logo (when you add it)

---

## What Changed

### 1. BrandHelper.php
Added new methods:
- `getSplashType()` - Returns 'about' or 'welcome'
- `getAboutContent()` - Returns about us content for Esther's Garden

### 2. plants.blade.php
Updated splash page to show:
- **Salenga Farm:** Simple welcome message
- **Esther's Garden:** About us with features list

### 3. splash-about.css
New styling for the about us section with:
- Blurred background box
- Checkmark icons
- Responsive design

---

## Customize About Content

Edit `app/Helpers/BrandHelper.php` to change Esther's Garden content:

```php
public static function getAboutContent()
{
    return [
        'title' => "About Esther's Garden",
        'description' => "Your custom description here...",
        'features' => [
            'Feature 1',
            'Feature 2',
            'Feature 3',
            'Feature 4'
        ]
    ];
}
```

---

## Remove Test Domains (When Done)

1. Open hosts file again as Administrator
2. Delete or comment out these lines:
```
# 127.0.0.1 esthersgarden.local
# 127.0.0.1 salengafarm.local
```
3. Save and close

---

## Deploy to Server

When ready to deploy:

```bash
git add -A
git commit -m "Added multi-brand splash pages"
git push origin main
```

Then on server:
```bash
cd /var/www/salengafarm && git pull origin main && php artisan view:clear && php artisan cache:clear && systemctl restart nginx
```

---

## Notes

- Both domains use the **same database**
- Both domains use the **same code**
- Only the **branding changes** based on domain
- You can test locally before buying esthersgarden.page domain
- When you buy the real domain, it will work the same way

---

**Last Updated:** May 19, 2026
