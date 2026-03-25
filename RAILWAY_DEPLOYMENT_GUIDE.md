# Railway Deployment Guide for Laravel

## Overview

Railway is an excellent platform for deploying Laravel applications. It provides:
- ✅ Automatic deployments from GitHub
- ✅ Built-in MySQL database
- ✅ Environment variable management
- ✅ Free $5 credit monthly (enough for small projects)
- ✅ Easy scaling
- ✅ Automatic HTTPS

---

## Prerequisites

Before starting:
- [ ] GitHub account
- [ ] Your Laravel project pushed to GitHub
- [ ] Railway account (new account for fresh $5 credit)
- [ ] Credit card (for verification, won't be charged with free tier)

---

## Step 1: Prepare Your Laravel Project

### 1.1 Check Your Procfile

Your current `Procfile` looks good, but let's optimize it:

```procfile
web: php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan storage:link && php -S 0.0.0.0:$PORT -t public
```

This is fine, but we can improve it. Update to:

```procfile
web: php artisan optimize:clear && php artisan migrate --force && php artisan storage:link && php artisan config:cache && php artisan route:cache && php artisan view:cache && php -S 0.0.0.0:$PORT -t public
```

### 1.2 Create nixpacks.toml (Railway Configuration)

Check if you have `nixpacks.toml`. If not, create it:

```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer']

[phases.install]
cmds = ['composer install --no-dev --optimize-autoloader']

[phases.build]
cmds = ['npm ci && npm run build']

[start]
cmd = 'php artisan serve --host=0.0.0.0 --port=$PORT'
```

### 1.3 Update .gitignore

Ensure these are in `.gitignore`:

```gitignore
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/.idea
/.vscode
```

### 1.4 Commit and Push to GitHub

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

---

## Step 2: Create Railway Account

### 2.1 Sign Up
1. Go to https://railway.app
2. Click "Start a New Project"
3. Sign up with GitHub (recommended) or email
4. Verify your email

### 2.2 Add Payment Method (Required)
1. Go to Account Settings
2. Add credit card (for verification)
3. You get $5 free credit monthly
4. Won't be charged unless you exceed free tier

---

## Step 3: Create New Project on Railway

### 3.1 Deploy from GitHub

1. Click "New Project"
2. Select "Deploy from GitHub repo"
3. Authorize Railway to access your GitHub
4. Select your Laravel repository
5. Click "Deploy Now"

Railway will automatically:
- Detect it's a Laravel project
- Install PHP and Composer
- Run `composer install`
- Start the application

### 3.2 Wait for Initial Deployment

First deployment takes 2-5 minutes. You'll see:
- ✅ Building...
- ✅ Deploying...
- ✅ Success!

---

## Step 4: Add MySQL Database

### 4.1 Add Database Service

1. In your project, click "New"
2. Select "Database"
3. Choose "Add MySQL"
4. Railway will provision a MySQL database

### 4.2 Get Database Credentials

1. Click on the MySQL service
2. Go to "Variables" tab
3. You'll see:
   - `MYSQL_URL`
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`
   - `MYSQL_DATABASE`

Keep this tab open, you'll need these values.

---

## Step 5: Configure Environment Variables

### 5.1 Go to Your Laravel Service

1. Click on your Laravel service (not the database)
2. Go to "Variables" tab
3. Click "Raw Editor"

### 5.2 Add Environment Variables

Copy and paste this, then update the values:

```env
APP_NAME="Salenga Farm System"
APP_ENV=production
APP_KEY=base64:YOUR_EXISTING_APP_KEY_FROM_LOCAL_ENV
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

# Database - Copy from MySQL service variables
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail Configuration (Brevo/Sendinblue)
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@salengafarm.com"
MAIL_FROM_NAME="${APP_NAME}"

# Brevo API (if using)
BREVO_API_KEY=your_brevo_api_key

# File Storage
FILESYSTEM_DISK=public

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Other
BROADCAST_CONNECTION=log
```

**Important Notes:**
- Replace `YOUR_EXISTING_APP_KEY_FROM_LOCAL_ENV` with your actual APP_KEY from local `.env`
- The `${{MySQL.MYSQL_HOST}}` syntax automatically references the MySQL service variables
- Update Brevo credentials if you want email to work

### 5.3 Save Variables

Click "Save" or it auto-saves. Railway will automatically redeploy.

---

## Step 6: Generate Domain

### 6.1 Add Public Domain

1. In your Laravel service, go to "Settings" tab
2. Scroll to "Networking"
3. Click "Generate Domain"
4. Railway will give you a URL like: `your-app-name.up.railway.app`
5. Copy this URL

### 6.2 Update APP_URL

1. Go back to "Variables" tab
2. Update `APP_URL` to your Railway domain:
   ```env
   APP_URL=https://your-app-name.up.railway.app
   ```
3. Save (will trigger redeploy)

---

## Step 7: Import Your Database

### 7.1 Export Local Database

```bash
# Export your local database
php artisan db:export database_backup.sql

# Or use mysqldump
mysqldump -u root -p your_database_name > database_backup.sql
```

### 7.2 Connect to Railway MySQL

**Option A: Using MySQL Workbench**

1. Open MySQL Workbench
2. Create new connection:
   - Hostname: (from Railway MySQL variables)
   - Port: (from Railway MySQL variables)
   - Username: (from Railway MySQL variables)
   - Password: (from Railway MySQL variables)
3. Connect
4. Import your SQL file

**Option B: Using Command Line**

```bash
# Get connection string from Railway MySQL service
# It looks like: mysql://user:password@host:port/database

mysql -h host -P port -u user -p database < database_backup.sql
```

**Option C: Using Railway CLI (Recommended)**

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link to your project
railway link

# Connect to MySQL
railway connect MySQL

# Then import
source database_backup.sql
```

### 7.3 Verify Database Import

1. Go to Railway MySQL service
2. Click "Data" tab
3. Check if tables exist

---

## Step 8: Run Migrations (If Needed)

If you prefer to run migrations instead of importing:

### 8.1 Using Railway CLI

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link to your project
railway link

# Run migrations
railway run php artisan migrate --force

# Seed database (if needed)
railway run php artisan db:seed --force
```

### 8.2 Using Deployment Logs

Migrations should run automatically from your Procfile, but you can check:

1. Go to your Laravel service
2. Click "Deployments" tab
3. Click latest deployment
4. Check logs for migration output

---

## Step 9: Storage Setup

### 9.1 Create Storage Link

This should happen automatically from your Procfile, but verify:

```bash
# Using Railway CLI
railway run php artisan storage:link
```

### 9.2 Upload Existing Files (If Any)

If you have existing files (plant photos, avatars, etc.):

**Option A: Re-upload through the app**
- Upload files again through your application interface

**Option B: Use Railway Volumes (Advanced)**
- Railway supports persistent volumes
- See: https://docs.railway.app/reference/volumes

---

## Step 10: Test Your Application

### 10.1 Access Your Site

Visit your Railway URL: `https://your-app-name.up.railway.app`

### 10.2 Test Key Features

- [ ] Homepage loads
- [ ] Login works
- [ ] Database queries work
- [ ] File uploads work
- [ ] Email sending works (if configured)
- [ ] PDF generation works
- [ ] All modules function correctly

### 10.3 Check Logs

If something doesn't work:

1. Go to your Laravel service
2. Click "Deployments" tab
3. Click latest deployment
4. View logs for errors

---

## Step 11: Configure Email (Brevo)

### 11.1 Get Brevo Credentials

1. Go to https://www.brevo.com
2. Sign up / Log in
3. Go to "SMTP & API" settings
4. Get your SMTP credentials

### 11.2 Update Railway Variables

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email@example.com
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@salengafarm.com"
MAIL_FROM_NAME="Salenga Farm System"

BREVO_API_KEY=your_brevo_api_key
```

### 11.3 Test Email

```bash
# Using Railway CLI
railway run php artisan tinker

# In tinker:
Mail::raw('Test email from Railway', function($msg) {
    $msg->to('your-email@example.com')->subject('Test');
});
```

---

## Step 12: Monitoring and Maintenance

### 12.1 Monitor Usage

1. Go to Railway dashboard
2. Check "Usage" tab
3. Monitor:
   - Execution time
   - Memory usage
   - Database size
   - Network bandwidth

Free tier includes:
- $5 credit per month
- 500 hours execution time
- 100 GB bandwidth

### 12.2 View Logs

```bash
# Using Railway CLI
railway logs

# Or in Railway dashboard
# Click service → Deployments → View logs
```

### 12.3 Automatic Deployments

Railway automatically deploys when you push to GitHub:

```bash
# Make changes locally
git add .
git commit -m "Update feature"
git push origin main

# Railway automatically detects and deploys
```

---

## Step 13: Troubleshooting

### Issue 1: 500 Internal Server Error

**Check logs:**
```bash
railway logs
```

**Common causes:**
- Missing APP_KEY
- Wrong database credentials
- Missing storage permissions

**Fix:**
```bash
railway run php artisan optimize:clear
railway run php artisan config:cache
```

### Issue 2: Database Connection Error

**Verify variables:**
1. Check MySQL service is running
2. Verify DB_* variables reference MySQL service
3. Use Railway's variable reference syntax:
   ```env
   DB_HOST=${{MySQL.MYSQL_HOST}}
   ```

### Issue 3: File Uploads Not Working

**Check storage:**
```bash
railway run php artisan storage:link
railway run ls -la storage/app/public
```

**Ensure directories exist:**
```bash
railway run mkdir -p storage/app/public/avatars
railway run mkdir -p storage/app/public/plant-photos
railway run mkdir -p storage/app/public/site-visits
```

### Issue 4: Email Not Sending

**Test configuration:**
```bash
railway run php artisan config:clear
railway run php artisan tinker
```

**Check Brevo credentials:**
- Verify SMTP key is correct
- Check Brevo dashboard for sending limits
- Ensure sender email is verified

### Issue 5: CSS/JS Not Loading

**Rebuild assets:**
```bash
npm run build
git add public/build
git commit -m "Rebuild assets"
git push
```

**Check APP_URL:**
```env
APP_URL=https://your-actual-railway-domain.up.railway.app
```

### Issue 6: Migrations Not Running

**Run manually:**
```bash
railway run php artisan migrate --force
```

**Check migration status:**
```bash
railway run php artisan migrate:status
```

---

## Step 14: Custom Domain (Optional)

### 14.1 Add Custom Domain

1. Go to your Laravel service
2. Settings → Networking
3. Click "Custom Domain"
4. Enter your domain (e.g., `salengafarm.com`)

### 14.2 Configure DNS

Add these records to your domain DNS:

**For root domain:**
```
Type: A
Name: @
Value: (Railway provides IP)
```

**For subdomain:**
```
Type: CNAME
Name: www
Value: your-app-name.up.railway.app
```

### 14.3 Update APP_URL

```env
APP_URL=https://salengafarm.com
```

---

## Step 15: Backup Strategy

### 15.1 Database Backups

**Manual backup:**
```bash
# Using Railway CLI
railway connect MySQL

# In MySQL prompt
mysqldump database_name > backup_$(date +%Y%m%d).sql
```

**Automated backup (using GitHub Actions):**

Create `.github/workflows/backup.yml`:

```yaml
name: Database Backup

on:
  schedule:
    - cron: '0 0 * * 0'  # Weekly on Sunday
  workflow_dispatch:

jobs:
  backup:
    runs-on: ubuntu-latest
    steps:
      - name: Backup Database
        run: |
          # Add backup script here
          echo "Backup completed"
```

### 15.2 Code Backups

Your code is already backed up on GitHub!

---

## Step 16: Performance Optimization

### 16.1 Enable Caching

```bash
railway run php artisan config:cache
railway run php artisan route:cache
railway run php artisan view:cache
```

### 16.2 Optimize Composer

Update `composer.json`:
```json
{
  "config": {
    "optimize-autoloader": true,
    "preferred-install": "dist"
  }
}
```

### 16.3 Use Production Environment

Ensure in Railway variables:
```env
APP_ENV=production
APP_DEBUG=false
```

---

## Quick Reference Commands

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link project
railway link

# View logs
railway logs

# Run artisan commands
railway run php artisan [command]

# Connect to database
railway connect MySQL

# Deploy manually
railway up

# Check status
railway status
```

---

## Cost Management

### Free Tier Limits:
- $5 credit per month
- ~500 hours execution time
- 100 GB bandwidth
- 1 GB database storage

### Tips to Stay Within Free Tier:
1. Use `APP_DEBUG=false` in production
2. Enable caching (config, routes, views)
3. Optimize database queries
4. Use CDN for static assets (if needed)
5. Monitor usage regularly

### If You Exceed Free Tier:
- Add payment method (charges only for overage)
- Typical cost: $5-10/month for small projects
- Much cheaper than traditional hosting

---

## Summary Checklist

- [ ] GitHub repository ready
- [ ] Railway account created
- [ ] Project deployed from GitHub
- [ ] MySQL database added
- [ ] Environment variables configured
- [ ] Domain generated
- [ ] Database imported/migrated
- [ ] Storage linked
- [ ] Email configured (Brevo)
- [ ] All features tested
- [ ] Monitoring set up
- [ ] Backup strategy in place

---

## Support Resources

- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Laravel Docs: https://laravel.com/docs
- Your deployment URL: `https://your-app-name.up.railway.app`

---

**Congratulations! Your Laravel app is now deployed on Railway! 🚀**

For any issues, check the logs first:
```bash
railway logs
```

Good luck with your final defense!
