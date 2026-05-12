# Salenga Farm - DigitalOcean Deployment Guide

## ✅ DEPLOYMENT COMPLETED!

**Live Website:** https://salengafarm.page  
**Server IP:** 165.245.182.243  
**Deployment Date:** April 24, 2026

---

## What We Accomplished

✅ Created DigitalOcean droplet (Ubuntu 24.04)  
✅ Installed Nginx, MySQL, PHP 8.3, Composer, Node.js  
✅ Deployed Laravel application from GitHub  
✅ Configured domain with SSL (HTTPS)  
✅ Imported database with all your data  
✅ Set up phpMyAdmin for database management  

---

## Prerequisites
- ✅ GitHub repository: https://github.com/CLouDyWolcen/salengafarm.git
- ✅ DigitalOcean account with $200 credit (GitHub Education Pack)
- ✅ Domain: salengafarm.page (from name.com)
- ✅ Payment method added to DigitalOcean

## Step 1: Create DigitalOcean Droplet ✅ COMPLETED

**What We Did:**
1. Logged in to DigitalOcean (https://cloud.digitalocean.com/)
2. Created droplet with these settings:
   - **OS:** Ubuntu 24.04 LTS
   - **Plan:** Basic $6/month (1GB RAM, 1 vCPU, 25GB SSD)
   - **Region:** Singapore (SGP1)
   - **Authentication:** Password
   - **Hostname:** salengafarm
3. Got droplet IP: **165.245.182.243**

**How to Access:**
- Click "Web Console" button in DigitalOcean dashboard
- Or SSH: `ssh root@165.245.182.243`

## Step 2: Connect to Your Droplet ✅ COMPLETED

**What We Did:**
- Used Web Console from DigitalOcean dashboard
- Logged in as root user

**Commands Used:**
```bash
# Check system info
uname -a
# Shows: Ubuntu 24.04.3 LTS
```

## Step 3: Initial Server Setup ✅ COMPLETED

**What We Did:**

### 3.1 Update System
```bash
apt update && apt upgrade -y
```

### 3.2 Install Web Server, Database, and PHP
```bash
apt install -y nginx mysql-server php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd unzip git curl
```

**Installed:**
- Nginx (web server)
- MySQL 8.0 (database)
- PHP 8.3 with extensions
- Git (for cloning repository)

### 3.3 Install Composer (PHP Package Manager)
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### 3.4 Install Node.js 20.x (for building assets)
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

**Verify Installations:**
```bash
nginx -v          # nginx version: nginx/1.24.0
mysql --version   # mysql  Ver 8.0.45
php -v            # PHP 8.3.6
composer --version # Composer version 2.x
node -v           # v20.x
npm -v            # 10.x
```

## Step 4: Setup MySQL Database

```bash
# Secure MySQL installation
mysql_secure_installation
# Answer: Y, set root password, Y, Y, Y, Y

# Create database and user
mysql -u root -p
```

```sql
CREATE DATABASE salengafarm_db;
CREATE USER 'salengafarm_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON salengafarm_db.* TO 'salengafarm_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Step 5: Clone and Setup Laravel Application

```bash
# Create web directory
mkdir -p /var/www
cd /var/www

# Clone repository
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

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

## Step 6: Configure Environment File

```bash
nano .env
```

Update these values:

```env
APP_NAME="Salenga Farm"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://salengafarm.page

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=salengafarm_db
DB_USERNAME=salengafarm_user
DB_PASSWORD=YOUR_STRONG_PASSWORD

# Email Configuration (if using Brevo)
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@salengafarm.page
MAIL_FROM_NAME="${APP_NAME}"

# Session and Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

Save and exit (Ctrl+X, Y, Enter)

## Step 7: Run Database Migrations

```bash
# Run migrations
php artisan migrate --force

# Seed database (if needed)
php artisan db:seed --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 8: Configure Nginx

```bash
nano /etc/nginx/sites-available/salengafarm
```

Paste this configuration:

```nginx
server {
    listen 80;
    server_name salengafarm.page www.salengafarm.page;
    root /var/www/salengafarm/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 20M;
}
```

Save and exit, then:

```bash
# Enable site
ln -s /etc/nginx/sites-available/salengafarm /etc/nginx/sites-enabled/

# Remove default site
rm /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Restart Nginx
systemctl restart nginx
```

## Step 9: Configure Domain (name.com)

1. **Log in to name.com**
2. **Go to your domain: salengafarm.page**
3. **Manage DNS Records**
4. **Add these records:**

```
Type: A
Host: @
Answer: YOUR_DROPLET_IP
TTL: 300

Type: A
Host: www
Answer: YOUR_DROPLET_IP
TTL: 300
```

5. **Save changes** (DNS propagation takes 5-30 minutes)

## Step 10: Install SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
apt install -y certbot python3-certbot-nginx

# Get SSL certificate
certbot --nginx -d salengafarm.page -d www.salengafarm.page

# Follow prompts:
# - Enter email address
# - Agree to terms
# - Choose to redirect HTTP to HTTPS (option 2)

# Test auto-renewal
certbot renew --dry-run
```

## Step 11: Setup Firewall

```bash
# Enable UFW firewall
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw enable

# Check status
ufw status
```

## Step 12: Create Deployment Script

```bash
nano /var/www/salengafarm/deploy.sh
```

Paste this:

```bash
#!/bin/bash
cd /var/www/salengafarm

# Pull latest changes
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Set permissions
chown -R www-data:www-data /var/www/salengafarm
chmod -R 755 /var/www/salengafarm
chmod -R 775 /var/www/salengafarm/storage
chmod -R 775 /var/www/salengafarm/bootstrap/cache

echo "Deployment completed!"
```

Make it executable:

```bash
chmod +x /var/www/salengafarm/deploy.sh
```

## Step 13: Setup Automatic Backups

```bash
# Create backup script
nano /root/backup-salengafarm.sh
```

Paste this:

```bash
#!/bin/bash
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/root/backups"
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u salengafarm_user -pYOUR_STRONG_PASSWORD salengafarm_db > $BACKUP_DIR/db_$TIMESTAMP.sql

# Backup files
tar -czf $BACKUP_DIR/files_$TIMESTAMP.tar.gz /var/www/salengafarm/storage/app/public

# Keep only last 7 days of backups
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $TIMESTAMP"
```

Make it executable and schedule:

```bash
chmod +x /root/backup-salengafarm.sh

# Add to crontab (daily at 2 AM)
crontab -e
```

Add this line:

```
0 2 * * * /root/backup-salengafarm.sh >> /var/log/backup.log 2>&1
```

## Step 14: Setup Laravel Scheduler (if needed)

```bash
crontab -e
```

Add this line:

```
* * * * * cd /var/www/salengafarm && php artisan schedule:run >> /dev/null 2>&1
```

## Step 15: Monitoring and Maintenance

### Check Application Logs
```bash
tail -f /var/www/salengafarm/storage/logs/laravel.log
```

### Check Nginx Logs
```bash
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log
```

### Restart Services
```bash
systemctl restart nginx
systemctl restart php8.1-fpm
systemctl restart mysql
```

## Future Deployments

To deploy updates:

```bash
ssh root@YOUR_DROPLET_IP
cd /var/www/salengafarm
./deploy.sh
```

## Troubleshooting

### 500 Error
```bash
# Check permissions
chown -R www-data:www-data /var/www/salengafarm
chmod -R 775 /var/www/salengafarm/storage
chmod -R 775 /var/www/salengafarm/bootstrap/cache

# Check logs
tail -f /var/www/salengafarm/storage/logs/laravel.log
```

### Database Connection Error
```bash
# Test database connection
mysql -u salengafarm_user -p salengafarm_db

# Check .env file
nano /var/www/salengafarm/.env
```

### CSS/JS Not Loading
```bash
# Rebuild assets
cd /var/www/salengafarm
npm run build
php artisan optimize
```

## Security Checklist

- ✅ SSL certificate installed
- ✅ Firewall enabled
- ✅ APP_DEBUG=false in production
- ✅ Strong database password
- ✅ Regular backups scheduled
- ✅ SSH key authentication (recommended)
- ✅ Keep system updated: `apt update && apt upgrade`

## Your Application URLs

- **Website:** https://salengafarm.page
- **Admin Login:** https://salengafarm.page/login
- **GitHub Repo:** https://github.com/CLouDyWolcen/salengafarm

## Support

If you encounter issues:
1. Check application logs: `/var/www/salengafarm/storage/logs/laravel.log`
2. Check Nginx logs: `/var/log/nginx/error.log`
3. Verify DNS propagation: https://dnschecker.org
4. Test SSL: https://www.ssllabs.com/ssltest/

---

**Deployment Date:** $(date)
**Server IP:** YOUR_DROPLET_IP
**Domain:** salengafarm.page
