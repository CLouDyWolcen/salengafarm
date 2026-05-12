# How to Clone Salenga Farm and Create Esther's Garden

## Overview

This guide shows you how to:
1. Copy the Salenga Farm project
2. Rename it to "Esther's Garden"
3. Deploy it as a second website on the same server (or different server)

---

## Option 1: Same Server (Two Websites on One Droplet)

You can host both websites on the same $6/month droplet!

### Advantages:
- ✅ Save money (one server for both)
- ✅ Share resources
- ✅ Easier to manage

### Requirements:
- Different domain (e.g., esthersgarden.com)
- Separate database
- Separate Nginx configuration

---

## Option 2: Different Server (Two Separate Droplets)

Each website on its own server.

### Advantages:
- ✅ Complete isolation
- ✅ Better performance
- ✅ Independent scaling

### Cost:
- $6/month × 2 = $12/month

---

## Step-by-Step: Clone and Rebrand

### 1. Copy Project Files (On Your Local Computer)

```bash
# Navigate to where you want the new project
cd C:\Users\Acer\Documents\

# Copy the entire project folder
xcopy /E /I salengafarm esthersgarden

# Or use File Explorer:
# Right-click salengafarm folder → Copy → Paste → Rename to esthersgarden
```

---

### 2. Update Project Name in Files

You need to change "Salenga Farm" to "Esther's Garden" in these files:

#### 2.1 Environment File (.env)
**File:** `.env`

```env
# Change this:
APP_NAME="Salenga Farm"

# To this:
APP_NAME="Esther's Garden"
```

#### 2.2 Configuration File (config/app.php)
**File:** `config/app.php`

```php
// Change:
'name' => env('APP_NAME', 'Salenga Farm'),

// To:
'name' => env('APP_NAME', "Esther's Garden"),
```

#### 2.3 Package File (composer.json)
**File:** `composer.json`

```json
{
    "name": "esthersgarden/esthersgarden",
    "description": "Esther's Garden Plant Management System",
    ...
}
```

#### 2.4 Package File (package.json)
**File:** `package.json`

```json
{
    "name": "esthersgarden",
    "description": "Esther's Garden",
    ...
}
```

#### 2.5 README File
**File:** `README.md`

Change all mentions of "Salenga Farm" to "Esther's Garden"

---

### 3. Search and Replace All Text References

Use your code editor (VS Code, Sublime, etc.) to find and replace:

**Find:** `Salenga Farm`  
**Replace:** `Esther's Garden`

**Find:** `salengafarm`  
**Replace:** `esthersgarden`

**Find:** `salengaFarm`  
**Replace:** `esthersGarden`

**Files to check:**
- All `.blade.php` files in `resources/views/`
- All `.php` files in `app/`
- All `.js` files in `public/js/`
- `README.md`
- `composer.json`
- `package.json`

---

### 4. Update Database Configuration

#### 4.1 Change Database Name in .env
**File:** `.env`

```env
# Change:
DB_DATABASE=salengafarm_db

# To:
DB_DATABASE=esthersgarden_db
```

#### 4.2 Update Database Seeders (Optional)
If you have seeders with "Salenga Farm" data, update them in:
- `database/seeders/`

---

### 5. Update Logo and Branding

#### 5.1 Replace Logo Images
**Location:** `public/images/`

Replace these files with Esther's Garden branding:
- `salengap.png` → `esthersgarden.png`
- `salengap-modified.png` → `esthersgarden-modified.png`

#### 5.2 Update Logo References in Views
**Files:** `resources/views/layouts/*.blade.php`

```html
<!-- Change: -->
<img src="{{ asset('images/salengap-modified.png') }}" alt="Salenga Farm">

<!-- To: -->
<img src="{{ asset('images/esthersgarden-modified.png') }}" alt="Esther's Garden">
```

---

### 6. Update Email Templates

**Location:** `resources/views/emails/`

Update all email templates to say "Esther's Garden" instead of "Salenga Farm"

Files to update:
- `client-request.blade.php`
- `inquiry-response.blade.php`
- `plant-request.blade.php`
- `role-request.blade.php`

---

### 7. Create New GitHub Repository

```bash
cd C:\Users\Acer\Documents\esthersgarden

# Remove old git history
rmdir /s .git

# Initialize new repository
git init
git add .
git commit -m "Initial commit - Esther's Garden"

# Create new repository on GitHub (esthersgarden)
# Then push:
git remote add origin https://github.com/YourUsername/esthersgarden.git
git branch -M main
git push -u origin main
```

---

## Deployment Options

### Option A: Deploy on Same Server (Recommended for Cost Savings)

Both websites on one droplet at 165.245.182.243

#### Requirements:
- New domain (e.g., esthersgarden.com)
- Separate database
- Separate Nginx configuration

#### Steps:

**1. Create New Database**
```bash
mysql -u root -p
```

```sql
CREATE DATABASE esthersgarden_db;
CREATE USER 'esthersgarden_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON esthersgarden_db.* TO 'esthersgarden_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**2. Clone Repository to Server**
```bash
cd /var/www
git clone https://github.com/YourUsername/esthersgarden.git esthersgarden
cd esthersgarden

# Set permissions
chown -R www-data:www-data /var/www/esthersgarden
chmod -R 755 /var/www/esthersgarden
chmod -R 775 /var/www/esthersgarden/storage
chmod -R 775 /var/www/esthersgarden/bootstrap/cache

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
nano .env
# Update: APP_NAME, APP_URL, DB_* settings

php artisan key:generate
php artisan migrate --force
```

**3. Create Nginx Configuration**
```bash
nano /etc/nginx/sites-available/esthersgarden
```

```nginx
server {
    listen 80;
    server_name esthersgarden.com www.esthersgarden.com;
    root /var/www/esthersgarden/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location ~* \.css$ {
        add_header Content-Type text/css;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    location ~* \.js$ {
        add_header Content-Type application/javascript;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 20M;
}
```

**4. Enable Site**
```bash
ln -s /etc/nginx/sites-available/esthersgarden /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

**5. Configure Domain DNS**
Point `esthersgarden.com` to `165.245.182.243` in your domain registrar

**6. Install SSL Certificate**
```bash
certbot --nginx -d esthersgarden.com -d www.esthersgarden.com
```

**7. Update .env for HTTPS**
```bash
nano /var/www/esthersgarden/.env
```

```env
APP_URL=https://esthersgarden.com
ASSET_URL=https://esthersgarden.com
FORCE_HTTPS=true
```

```bash
php artisan config:cache
```

---

### Option B: Deploy on New Server

Create a new droplet and follow the same deployment process as Salenga Farm.

**Advantages:**
- Complete isolation
- Independent resources
- Easier troubleshooting

**Steps:**
1. Create new DigitalOcean droplet
2. Follow DEPLOYMENT_SUMMARY.md
3. Use esthersgarden repository
4. Use different domain

---

## File Structure Comparison

```
Server with Both Websites:

/var/www/
├── salengafarm/          (First website)
│   ├── app/
│   ├── public/
│   ├── .env              (DB: salengafarm_db)
│   └── ...
│
└── esthersgarden/        (Second website)
    ├── app/
    ├── public/
    ├── .env              (DB: esthersgarden_db)
    └── ...

/etc/nginx/sites-available/
├── salengafarm           (Nginx config for salengafarm.page)
└── esthersgarden         (Nginx config for esthersgarden.com)
```

---

## Checklist: What to Change

### Required Changes:
- [ ] `.env` - APP_NAME, APP_URL, DB_DATABASE, DB_USERNAME
- [ ] `composer.json` - name, description
- [ ] `package.json` - name, description
- [ ] `README.md` - All text references
- [ ] Logo images in `public/images/`
- [ ] All `.blade.php` files - Text references
- [ ] Email templates - Branding
- [ ] Database name and credentials

### Optional Changes:
- [ ] Favicon (`public/favicon.ico`)
- [ ] Color scheme in CSS files
- [ ] Footer copyright text
- [ ] Meta descriptions for SEO

---

## Quick Reference: Key Differences

| Item | Salenga Farm | Esther's Garden |
|------|--------------|-----------------|
| **Domain** | salengafarm.page | esthersgarden.com |
| **Server Path** | /var/www/salengafarm | /var/www/esthersgarden |
| **Database** | salengafarm_db | esthersgarden_db |
| **DB User** | salengafarm_user | esthersgarden_user |
| **Nginx Config** | /etc/nginx/sites-available/salengafarm | /etc/nginx/sites-available/esthersgarden |
| **GitHub Repo** | CLouDyWolcen/salengafarm | YourUsername/esthersgarden |

---

## Cost Breakdown

### Same Server (Recommended):
- **Droplet:** $6/month
- **Domain 1:** salengafarm.page (already paid)
- **Domain 2:** esthersgarden.com (~$10-15/year)
- **Total:** $6/month + domain costs

### Separate Servers:
- **Droplet 1:** $6/month (Salenga Farm)
- **Droplet 2:** $6/month (Esther's Garden)
- **Domains:** Already paid
- **Total:** $12/month

---

## Testing Checklist

After deployment, test:

- [ ] Homepage loads with correct branding
- [ ] Logo shows "Esther's Garden"
- [ ] Login/Register works
- [ ] Database connection works
- [ ] CSS/JS loads properly
- [ ] Images display correctly
- [ ] Email templates show correct name
- [ ] SSL certificate works (green padlock)
- [ ] All pages accessible
- [ ] Admin dashboard works

---

## Common Issues and Solutions

### Issue: Old branding still showing
**Solution:** Clear all caches
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
```

### Issue: Database connection error
**Solution:** Check .env database credentials match the database you created

### Issue: CSS not loading
**Solution:** Rebuild assets
```bash
npm run build
php artisan config:cache
```

### Issue: 404 errors
**Solution:** Check Nginx configuration and restart
```bash
nginx -t
systemctl restart nginx
```

---

## Need Help?

When you're ready to deploy Esther's Garden, I'll help you with:
1. Setting up the new database
2. Configuring Nginx for the second site
3. Setting up the domain
4. Installing SSL certificate
5. Troubleshooting any issues

Just let me know when you're ready! 🌱

---

**Summary:**
- Clone project → Rename files → Update branding → Deploy
- Can host both on same server ($6/month) or separate servers ($12/month)
- Follow this guide step-by-step when you're ready
