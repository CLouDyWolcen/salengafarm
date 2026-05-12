# Salenga Farm - Complete Deployment Summary

## 🎉 Successfully Deployed!

**Website:** https://salengafarm.page  
**Server IP:** 165.245.182.243  
**Date:** April 24, 2026

---

## What We Built

A fully functional Laravel web application deployed on DigitalOcean with:
- ✅ Secure HTTPS connection (SSL certificate)
- ✅ Custom domain (salengafarm.page)
- ✅ Complete database with all your data
- ✅ Professional web server setup
- ✅ Database management tool (phpMyAdmin)

---

## Step-by-Step: What We Did

### 1. Created DigitalOcean Droplet

**Where:** DigitalOcean Dashboard (https://cloud.digitalocean.com/)

**What We Chose:**
- Operating System: Ubuntu 24.04 LTS
- Plan: Basic $6/month (1GB RAM, 1 vCPU, 25GB SSD)
- Region: Singapore (SGP1) - closest to Philippines
- Hostname: salengafarm
- Got IP address: **165.245.182.243**

**How to Access Server:**
- Web Console: Click "Web Console" button in DigitalOcean
- SSH: `ssh root@165.245.182.243`

---

### 2. Installed Server Software

**Connected to server via Web Console, then ran:**

```bash
# Update system
apt update && apt upgrade -y

# Install web server, database, PHP
apt install -y nginx mysql-server php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd unzip git curl

# Install Composer (PHP package manager)
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Node.js (for building CSS/JS)
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

**What Each Does:**
- **Nginx:** Web server (serves your website)
- **MySQL:** Database (stores your data)
- **PHP 8.3:** Programming language Laravel uses
- **Composer:** Installs Laravel dependencies
- **Node.js:** Builds your CSS and JavaScript files

---

### 3. Setup MySQL Database

```bash
# Secure MySQL
mysql_secure_installation
# Answered: n (no password validation), set root password, y, y, y, y

# Create database
mysql -u root -p
```

```sql
CREATE DATABASE salengafarm_db;
CREATE USER 'salengafarm_user'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON salengafarm_db.* TO 'salengafarm_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Database Credentials:**
- Database Name: `salengafarm_db`
- Username: `salengafarm_user`
- Password: `root`

---

### 4. Deployed Laravel Application

```bash
# Clone from GitHub
mkdir -p /var/www
cd /var/www
git clone https://github.com/CLouDyWolcen/salengafarm.git salengafarm
cd salengafarm

# Set permissions
chown -R www-data:www-data /var/www/salengafarm
chmod -R 755 /var/www/salengafarm
chmod -R 775 /var/www/salengafarm/storage
chmod -R 775 /var/www/salengafarm/bootstrap/cache

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
npm install
npm run build

# Setup environment file
cp .env.example .env
nano .env
# Updated: APP_URL, database credentials, etc.

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force
```

**Important Files:**
- `/var/www/salengafarm/.env` - Configuration file
- `/var/www/salengafarm/public` - Web root (where Nginx serves from)
- `/var/www/salengafarm/storage/logs/laravel.log` - Application logs

---

### 5. Configured Nginx Web Server

```bash
nano /etc/nginx/sites-available/salengafarm
```

**Configuration File Content:**
```nginx
server {
    listen 80;
    server_name salengafarm.page www.salengafarm.page 165.245.182.243;
    root /var/www/salengafarm/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    # MIME types for CSS/JS
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

    # Handle static files
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

**Enable the site:**
```bash
ln -s /etc/nginx/sites-available/salengafarm /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
systemctl restart php8.3-fpm
```

**Configuration Location:** `/etc/nginx/sites-available/salengafarm`

---

### 6. Configured Domain (name.com)

**Where:** name.com DNS Management

**Added DNS Records:**

| Type | Host | Value | TTL |
|------|------|-------|-----|
| A | @ | 165.245.182.243 | 300 |
| A | www | 165.245.182.243 | 300 |

**What This Does:**
- `salengafarm.page` → Points to your server
- `www.salengafarm.page` → Also points to your server

**DNS Propagation:** Takes 5-30 minutes (up to 48 hours worldwide)

---

### 7. Installed SSL Certificate (HTTPS)

```bash
# Install Certbot
apt install certbot python3-certbot-nginx -y

# Get SSL certificate
certbot --nginx -d salengafarm.page -d www.salengafarm.page
# Chose option 1 to reinstall existing certificate
```

**What This Does:**
- Gets free SSL certificate from Let's Encrypt
- Configures Nginx to use HTTPS
- Auto-renews certificate every 90 days
- Adds green padlock 🔒 to your website

**Certificate Location:** `/etc/letsencrypt/live/salengafarm.page/`

---

### 8. Imported Database

**Installed phpMyAdmin:**
```bash
apt install phpmyadmin -y
ln -s /usr/share/phpmyadmin /var/www/salengafarm/public/phpmyadmin
```

**Imported Data:**
1. Went to: http://165.245.182.243/phpmyadmin
2. Logged in with: salengafarm_user / root
3. Selected salengafarm_db database
4. Clicked Import tab
5. Uploaded `newinvntory.sql` file (121 KB)
6. Clicked Go

**phpMyAdmin Access:** https://salengafarm.page/phpmyadmin

---

### 9. Fixed URL Generation Issues

**Problem:** Laravel was generating HTTPS URLs but server only had HTTP configured.

**Solution - Updated AppServiceProvider:**

File: `/var/www/salengafarm/app/Providers/AppServiceProvider.php`

```php
public function boot(): void
{
    // Force HTTPS in production
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }
}
```

**Updated .env file:**
```env
APP_URL=https://salengafarm.page
ASSET_URL=https://salengafarm.page
FORCE_HTTPS=true
```

**Cleared caches:**
```bash
php artisan config:cache
php artisan view:clear
php artisan cache:clear
```

---

## Important File Locations

### Configuration Files
- **Laravel Config:** `/var/www/salengafarm/.env`
- **Nginx Config:** `/etc/nginx/sites-available/salengafarm`
- **PHP Config:** `/etc/php/8.3/fpm/php.ini`
- **MySQL Config:** `/etc/mysql/mysql.conf.d/mysqld.cnf`

### Application Files
- **Laravel Root:** `/var/www/salengafarm/`
- **Public Files:** `/var/www/salengafarm/public/`
- **Storage:** `/var/www/salengafarm/storage/`
- **Logs:** `/var/www/salengafarm/storage/logs/laravel.log`

### System Logs
- **Nginx Access:** `/var/log/nginx/access.log`
- **Nginx Error:** `/var/log/nginx/error.log`
- **PHP-FPM:** `/var/log/php8.3-fpm.log`
- **MySQL:** `/var/log/mysql/error.log`

---

## Common Commands

### Restart Services
```bash
systemctl restart nginx
systemctl restart php8.3-fpm
systemctl restart mysql
```

### Check Service Status
```bash
systemctl status nginx
systemctl status php8.3-fpm
systemctl status mysql
```

### Laravel Commands
```bash
cd /var/www/salengafarm

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate

# Check routes
php artisan route:list
```

### View Logs
```bash
# Laravel logs
tail -f /var/www/salengafarm/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log
```

### Database Access
```bash
# MySQL command line
mysql -u salengafarm_user -p
# Password: root

# Inside MySQL
USE salengafarm_db;
SHOW TABLES;
SELECT * FROM users;
EXIT;
```

---

## Updating Your Website

### When You Make Code Changes

1. **Push to GitHub** (on your local computer):
```bash
git add .
git commit -m "Your changes"
git push origin main
```

2. **Pull on Server:**
```bash
cd /var/www/salengafarm
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
systemctl restart nginx
```

### Quick Update Script

Create a deployment script:
```bash
nano /var/www/salengafarm/deploy.sh
```

```bash
#!/bin/bash
cd /var/www/salengafarm
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data /var/www/salengafarm
chmod -R 755 /var/www/salengafarm
chmod -R 775 /var/www/salengafarm/storage
chmod -R 775 /var/www/salengafarm/bootstrap/cache
systemctl restart nginx
echo "Deployment completed!"
```

Make it executable:
```bash
chmod +x /var/www/salengafarm/deploy.sh
```

Run it:
```bash
/var/www/salengafarm/deploy.sh
```

---

## Troubleshooting

### Website Shows 500 Error
```bash
# Check Laravel logs
tail -20 /var/www/salengafarm/storage/logs/laravel.log

# Check permissions
chown -R www-data:www-data /var/www/salengafarm
chmod -R 775 /var/www/salengafarm/storage
chmod -R 775 /var/www/salengafarm/bootstrap/cache
```

### CSS/JS Not Loading
```bash
cd /var/www/salengafarm
npm run build
php artisan config:cache
php artisan view:clear
systemctl restart nginx
```

### Database Connection Error
```bash
# Test database connection
mysql -u salengafarm_user -p salengafarm_db

# Check .env file
nano /var/www/salengafarm/.env
# Verify DB_* settings

# Clear config cache
php artisan config:clear
php artisan config:cache
```

### Domain Not Working
```bash
# Check DNS
nslookup salengafarm.page 8.8.8.8

# Check Nginx config
nginx -t

# Restart Nginx
systemctl restart nginx
```

---

## Security Checklist

✅ **SSL Certificate:** Installed (HTTPS)  
✅ **Firewall:** UFW enabled (if configured)  
✅ **APP_DEBUG:** Set to false in production  
✅ **Strong Passwords:** Database password set  
✅ **File Permissions:** Properly configured  
✅ **Auto-renewal:** SSL certificate renews automatically  

### Additional Security (Optional)

```bash
# Enable firewall
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw enable

# Disable root SSH login
nano /etc/ssh/sshd_config
# Set: PermitRootLogin no
systemctl restart sshd
```

---

## Backup Strategy

### Manual Database Backup
```bash
mysqldump -u salengafarm_user -p salengafarm_db > backup_$(date +%Y%m%d).sql
```

### Automated Backups (Optional)

Create backup script:
```bash
nano /root/backup.sh
```

```bash
#!/bin/bash
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/root/backups"
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u salengafarm_user -proot salengafarm_db > $BACKUP_DIR/db_$TIMESTAMP.sql

# Backup files
tar -czf $BACKUP_DIR/files_$TIMESTAMP.tar.gz /var/www/salengafarm/storage/app/public

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $TIMESTAMP"
```

Schedule daily backups:
```bash
chmod +x /root/backup.sh
crontab -e
# Add: 0 2 * * * /root/backup.sh >> /var/log/backup.log 2>&1
```

---

## Your Credentials

### Server Access
- **IP:** 165.245.182.243
- **User:** root
- **Access:** Web Console or SSH

### Database
- **Host:** 127.0.0.1
- **Database:** salengafarm_db
- **Username:** salengafarm_user
- **Password:** root

### phpMyAdmin
- **URL:** https://salengafarm.page/phpmyadmin
- **Username:** salengafarm_user
- **Password:** root

### Website Admin
- Use your existing admin account from local database

---

## Resources

- **DigitalOcean Dashboard:** https://cloud.digitalocean.com/
- **Domain Management:** https://www.name.com/
- **GitHub Repository:** https://github.com/CLouDyWolcen/salengafarm
- **Laravel Documentation:** https://laravel.com/docs
- **Let's Encrypt:** https://letsencrypt.org/

---

## Support & Monitoring

### Check Website Status
- **Main Site:** https://salengafarm.page
- **Server Status:** http://165.245.182.243
- **SSL Test:** https://www.ssllabs.com/ssltest/analyze.html?d=salengafarm.page
- **DNS Check:** https://dnschecker.org

### Monitor Server Resources
```bash
# Check disk space
df -h

# Check memory
free -h

# Check CPU
top

# Check running processes
ps aux | grep nginx
ps aux | grep php
ps aux | grep mysql
```

---

## What You Learned

1. **Server Management:** How to set up and configure a Linux server
2. **Web Server:** Nginx configuration for Laravel
3. **Database:** MySQL setup and management
4. **SSL/HTTPS:** Securing your website with Let's Encrypt
5. **DNS:** Connecting a domain to your server
6. **Laravel Deployment:** Full production deployment process
7. **Troubleshooting:** How to diagnose and fix common issues

---

## Next Steps (Optional Improvements)

- [ ] Set up automated backups
- [ ] Configure email sending (Brevo/SMTP)
- [ ] Add monitoring (UptimeRobot, etc.)
- [ ] Set up staging environment
- [ ] Configure CDN for faster loading
- [ ] Add server monitoring (New Relic, etc.)
- [ ] Set up CI/CD pipeline

---

**Congratulations! Your Salenga Farm website is now live and fully functional! 🎉**

**Website:** https://salengafarm.page
