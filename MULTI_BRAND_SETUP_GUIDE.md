# Multi-Brand Setup Guide
## One Website, Two Brands: Salenga Farm & Esther's Garden

---

## What We're Setting Up

- **salengafarm.page** → Shows "Salenga Farm" branding
- **esthersgarden.com** → Shows "Esther's Garden" branding
- **Same website, same data, just different names and logos!**

---

## Step 1: Update composer.json (On Your Computer)

Open `composer.json` and find the `"autoload"` section. Add the helpers file:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [
        "app/Helpers/BrandHelper.php"
    ]
},
```

Save the file.

---

## Step 2: Update Layout Files

### File: `resources/views/layouts/public.blade.php`

Find the logo section (around line 20-30) and replace with:

```blade
<a href="{{ route('public.plants') }}" class="navbar-brand d-flex align-items-center">
    <img src="{{ \App\Helpers\BrandHelper::getLogo() }}" 
         alt="{{ \App\Helpers\BrandHelper::getName() }}" 
         height="40" 
         class="me-2">
    <span class="fw-bold">{{ \App\Helpers\BrandHelper::getName() }}</span>
</a>
```

### File: `resources/views/public/plants.blade.php`

Find the welcome section and replace with:

```blade
<h1 class="display-4 fw-bold mb-3">{{ \App\Helpers\BrandHelper::getWelcomeMessage() }}</h1>
<p class="lead text-muted mb-4">{{ \App\Helpers\BrandHelper::getTagline() }}</p>
```

### File: `resources/views/layouts/sidebar.blade.php` (if exists)

Find any "Salenga Farm" text and replace with:

```blade
{{ \App\Helpers\BrandHelper::getName() }}
```

---

## Step 3: Create Esther's Garden Logo

1. Create a logo image for Esther's Garden
2. Save it as: `public/images/esthersgarden-modified.png`
3. Make it the same size as `salengap-modified.png` (same dimensions)

**For now, you can copy the Salenga Farm logo:**
- Copy `public/images/salengap-modified.png`
- Rename copy to `esthersgarden-modified.png`
- Later, replace with actual Esther's Garden logo

---

## Step 4: Push Changes to GitHub

```bash
cd C:\CODING\my_Inventory
git add .
git commit -m "Added multi-brand support"
git push origin main
```

---

## Step 5: Update Server

Connect to your server console and run:

```bash
cd /var/www/salengafarm
git pull origin main
composer dump-autoload
php artisan config:cache
php artisan view:clear
systemctl restart nginx
```

---

## Step 6: Buy Esther's Garden Domain

1. Go to name.com (or any domain registrar)
2. Buy: **esthersgarden.com** (or .page, .net, etc.)
3. Cost: ~$10-15/year

---

## Step 7: Point Domain to Server

In name.com DNS settings, add:

| Type | Host | Value | TTL |
|------|------|-------|-----|
| A | @ | 165.245.182.243 | 300 |
| A | www | 165.245.182.243 | 300 |

---

## Step 8: Update Nginx Configuration

On the server:

```bash
nano /etc/nginx/sites-available/salengafarm
```

Update the `server_name` line to include both domains:

```nginx
server_name salengafarm.page www.salengafarm.page esthersgarden.com www.esthersgarden.com 165.245.182.243;
```

Save (Ctrl+X, Y, Enter), then:

```bash
nginx -t
systemctl reload nginx
```

---

## Step 9: Add SSL for Esther's Garden

```bash
certbot --nginx -d esthersgarden.com -d www.esthersgarden.com
```

Choose option to get new certificate.

---

## Step 10: Test Both Sites

1. **Visit:** https://salengafarm.page
   - Should show "Salenga Farm" logo and name

2. **Visit:** https://esthersgarden.com
   - Should show "Esther's Garden" logo and name

3. **Test:** Add a plant on salengafarm.page
   - Should appear on both sites!

---

## How It Works

```
User visits salengafarm.page
    ↓
Laravel detects domain
    ↓
BrandHelper returns "Salenga Farm"
    ↓
Shows Salenga Farm branding

User visits esthersgarden.com
    ↓
Laravel detects domain
    ↓
BrandHelper returns "Esther's Garden"
    ↓
Shows Esther's Garden branding
```

---

## What's Shared

✅ Database (same plants, users, orders)
✅ Code (same functionality)
✅ Server (same hosting)
✅ Admin panel (same login)

## What's Different

❌ Brand name ("Salenga Farm" vs "Esther's Garden")
❌ Logo image
❌ Domain name

---

## Cost

- **Server:** $6/month (same droplet for both)
- **Domain 1:** salengafarm.page (already paid)
- **Domain 2:** esthersgarden.com (~$10-15/year)

**Total:** $6/month + one-time domain cost

---

## Troubleshooting

### Logo not changing
```bash
php artisan view:clear
php artisan config:cache
```

### Domain not working
- Wait 5-30 minutes for DNS propagation
- Check DNS: `nslookup esthersgarden.com 8.8.8.8`

### SSL error
```bash
certbot --nginx -d esthersgarden.com -d www.esthersgarden.com
```

---

## Next Steps

1. ✅ Create BrandHelper.php (done)
2. ⏳ Update composer.json
3. ⏳ Update layout files
4. ⏳ Create Esther's Garden logo
5. ⏳ Push to GitHub
6. ⏳ Update server
7. ⏳ Buy domain
8. ⏳ Configure DNS
9. ⏳ Update Nginx
10. ⏳ Add SSL

**Start with Step 1 - Update composer.json!**
