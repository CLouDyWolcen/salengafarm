# Quick Deployment Guide
## Salenga Farm System - DigitalOcean

---

## 🚀 HOW TO DEPLOY CHANGES (Every Time You Update Code)

### On Your Computer (Windows):
```bash
cd C:\CODING\my_Inventory
git add -A
git commit -m "Description of changes"
git push origin main
```

### On DigitalOcean Server Console:
```bash
cd /var/www/salengafarm && git pull origin main && php artisan view:clear && php artisan cache:clear && systemctl restart nginx
```

**That's it!** Your changes are live at https://salengafarm.page

---

## 📊 DATABASE UPDATES

### Export from Local MySQL:
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Select `salengafarm_db` database
3. Click "Export" tab
4. Click "Go" button
5. Save the `.sql` file

### Import to Server:
1. Go to: https://165.245.182.243/phpmyadmin
2. Login: `salengafarm_user` / `root`
3. Select `salengafarm_db` database
4. Click "Import" tab
5. Choose your `.sql` file
6. Click "Go" button

---

## 🌐 SERVER INFORMATION

**Droplet Details:**
- IP Address: `165.245.182.243`
- Domain: `salengafarm.page`
- Location: Singapore
- Plan: $6/month (Basic Droplet)
- OS: Ubuntu 24.04

**Installed Software:**
- Nginx (Web Server)
- PHP 8.3 + PHP-FPM
- MySQL 8.0
- Composer
- Node.js 20.x
- Let's Encrypt SSL (HTTPS)

**File Locations:**
- Website: `/var/www/salengafarm`
- Nginx Config: `/etc/nginx/sites-available/salengafarm`
- Environment: `/var/www/salengafarm/.env`
- Logs: `/var/log/nginx/`

**Database:**
- Name: `salengafarm_db`
- User: `salengafarm_user`
- Password: `root`
- Host: `127.0.0.1`

---

## 🎨 MULTI-BRAND SETUP (Future Feature)

**Status:** Code is ready, waiting for domain purchase

**What It Does:**
- One website, two domains, different branding
- `salengafarm.page` → Shows "Salenga Farm"
- `esthersgarden.page` → Shows "Esther's Garden"
- Same database, same code, just different names/logos

**Already Implemented:**
- ✅ `app/Helpers/BrandHelper.php` (deployed)
- ✅ Auto-detects domain and shows correct branding
- ✅ Methods: `getName()`, `getLogo()`, `isEsthersGarden()`

**To Activate (When Ready):**

### Step 1: Buy Domain
- Go to name.com or any registrar
- Buy: `esthersgarden.page` (~$10-15/year)

### Step 2: Point DNS to Server
Add these DNS records:
```
Type: A, Host: @, Value: 165.245.182.243
Type: A, Host: www, Value: 165.245.182.243
```

### Step 3: Update Nginx Config
```bash
nano /etc/nginx/sites-available/salengafarm
```

Change `server_name` line to:
```nginx
server_name salengafarm.page www.salengafarm.page esthersgarden.page www.esthersgarden.page 165.245.182.243;
```

Save and reload:
```bash
nginx -t
systemctl reload nginx
```

### Step 4: Add SSL Certificate
```bash
certbot --nginx -d esthersgarden.page -d www.esthersgarden.page
```

### Step 5: Create Esther's Garden Logo
Upload logo to: `public/images/esthersgarden-modified.png`

### Step 6: Test Both Sites
- Visit: https://salengafarm.page (shows Salenga Farm)
- Visit: https://esthersgarden.page (shows Esther's Garden)

**Cost:** Same $6/month server + $10-15/year for domain

---

## 🔧 COMMON COMMANDS

### Clear All Caches:
```bash
cd /var/www/salengafarm
php artisan config:cache
php artisan route:cache
php artisan view:clear
php artisan cache:clear
```

### Restart Services:
```bash
systemctl restart php8.3-fpm
systemctl restart nginx
systemctl restart mysql
```

### Check Service Status:
```bash
systemctl status nginx
systemctl status php8.3-fpm
systemctl status mysql
```

### View Error Logs:
```bash
tail -50 /var/log/nginx/error.log
tail -50 /var/www/salengafarm/storage/logs/laravel.log
```

### Fix Permissions:
```bash
cd /var/www/salengafarm
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Reset Server Code (Discard Server Changes):
```bash
cd /var/www/salengafarm
git reset --hard origin/main
git pull origin main
```

---

## 📝 GIT WORKFLOW

### Check What Changed:
```bash
git status
```

### Stage All Changes:
```bash
git add -A
```

### Commit Changes:
```bash
git commit -m "Your message here"
```

### Push to GitHub:
```bash
git push origin main
```

### Pull Latest Code (Server):
```bash
cd /var/www/salengafarm
git pull origin main
```

### View Recent Commits:
```bash
git log -5 --oneline
```

---

## 🔐 ACCESS INFORMATION

### SSH Access:
```bash
ssh root@165.245.182.243
```

### phpMyAdmin:
- URL: https://165.245.182.243/phpmyadmin
- Username: `salengafarm_user`
- Password: `root`

### Website:
- Public: https://salengafarm.page
- Admin Login: https://salengafarm.page/login

### GitHub Repository:
- URL: https://github.com/CLouDyWolcen/salengafarm.git

---

## ⚡ QUICK TROUBLESHOOTING

### Website Not Updating?
```bash
cd /var/www/salengafarm
git pull origin main
php artisan view:clear
php artisan cache:clear
systemctl restart nginx
```

### CSS/JS Not Loading?
```bash
php artisan config:cache
systemctl restart nginx
```

### Database Connection Error?
Check `/var/www/salengafarm/.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=salengafarm_db
DB_USERNAME=salengafarm_user
DB_PASSWORD=root
```

### 500 Error?
```bash
tail -50 /var/www/salengafarm/storage/logs/laravel.log
```

### Nginx Not Starting?
```bash
nginx -t
systemctl status nginx
```

---

## 📚 RELATED DOCUMENTATION

- `DEPLOYMENT_SUMMARY.md` - Initial deployment details
- `DIGITALOCEAN_DEPLOYMENT_GUIDE.md` - Full setup guide
- `MULTI_BRAND_SETUP_GUIDE.md` - Multi-brand feature guide
- `app/Helpers/BrandHelper.php` - Brand detection code

---

## 💡 REMEMBER

1. **Always commit locally first**, then push to GitHub, then pull on server
2. **Database changes** need manual export/import via phpMyAdmin
3. **Clear caches** after pulling code changes
4. **Multi-brand is ready** - just need to buy esthersgarden.page domain
5. **One server hosts both domains** - no extra hosting cost

---

**Last Updated:** May 19, 2026
**Server IP:** 165.245.182.243
**Domain:** salengafarm.page
**Status:** ✅ Live and Running
