# Railway Deployment - New GitHub Account Setup Guide

## 🎯 Quick Overview

This guide will help you deploy your Salenga Farm system to Railway using your new GitHub account.

**Time Required:** 30-45 minutes  
**Cost:** Free (Railway provides $5 monthly credit)

---

## 📋 Prerequisites Checklist

Before starting, make sure you have:

- [ ] New GitHub account created and logged in
- [ ] Railway account (we'll create this)
- [ ] Credit card for Railway verification (won't be charged)
- [ ] Your local project is working correctly
- [ ] Database backup ready (if you have existing data)

---

## Phase 1: Prepare Your Project (10 minutes)

### Step 1.1: Check Your Current Git Status

Open your terminal in the project directory and run:

```bash
# Check current git status
git status

# Check current remote
git remote -v
```

### Step 1.2: Remove Old Git Remote (if exists)

If you see an old GitHub remote, remove it:

```bash
# Remove old remote
git remote remove origin

# Verify it's removed
git remote -v
```

### Step 1.3: Create New GitHub Repository

1. Go to your new GitHub account: https://github.com
2. Click the "+" icon (top right) → "New repository"
3. Repository settings:
   - **Name:** `salenga-farm-system` (or your preferred name)
   - **Description:** "Plant Nursery Management System for Salenga Farm"
   - **Visibility:** Private (recommended) or Public
   - **DO NOT** initialize with README, .gitignore, or license
4. Click "Create repository"

### Step 1.4: Connect Your Local Project to New GitHub Repo

Copy the commands from GitHub (they'll look like this):

```bash
# Add new remote
git remote add origin https://github.com/YOUR-NEW-USERNAME/salenga-farm-system.git

# Verify remote is added
git remote -v

# Push to new repository
git branch -M main
git push -u origin main
```

**Note:** Replace `YOUR-NEW-USERNAME` with your actual GitHub username.

### Step 1.5: Verify Files Are Pushed

1. Refresh your GitHub repository page
2. You should see all your files (except those in .gitignore)
3. Check that these files are present:
   - ✅ `railway.json`
   - ✅ `nixpacks.toml`
   - ✅ `Procfile`
   - ✅ `composer.json`
   - ✅ `package.json`

---

## Phase 2: Create Railway Account (5 minutes)

### Step 2.1: Sign Up for Railway

1. Go to https://railway.app
2. Click "Login" or "Start a New Project"
3. Choose "Login with GitHub"
4. Authorize Railway to access your GitHub account
5. Select "All repositories" or just your Laravel repository

### Step 2.2: Add Payment Method

Railway requires a credit card for verification (you won't be charged):

1. After logging in, click your profile (bottom left)
2. Go to "Account Settings"
3. Click "Billing"
4. Click "Add Payment Method"
5. Enter your credit card details
6. You'll receive $5 free credit monthly

**Free Tier Limits:**
- $5 credit per month
- ~500 hours execution time
- 100 GB bandwidth
- 1 GB database storage

---

## Phase 3: Deploy to Railway (15 minutes)

### Step 3.1: Create New Project

1. Click "New Project" (top right)
2. Select "Deploy from GitHub repo"
3. Choose your repository: `salenga-farm-system`
4. Railway will automatically detect it's a Laravel project
5. Click "Deploy Now"

**What Railway Does Automatically:**
- ✅ Detects PHP 8.2 from nixpacks.toml
- ✅ Installs Composer dependencies
- ✅ Installs npm dependencies
- ✅ Builds frontend assets (npm run build)
- ✅ Starts the application

### Step 3.2: Wait for Initial Build

- First deployment takes 3-5 minutes
- Watch the build logs in real-time
- You'll see: Building → Deploying → Success ✅

**If build fails:** Check the logs for errors (usually missing dependencies or configuration issues)

### Step 3.3: Add MySQL Database

1. In your Railway project dashboard, click "New"
2. Select "Database"
3. Choose "Add MySQL"
4. Railway will provision a MySQL database (takes ~1 minute)
5. The database will appear as a new service in your project

---

## Phase 4: Configure Environment Variables (10 minutes)

### Step 4.1: Get Database Credentials

1. Click on the **MySQL** service (not your Laravel app)
2. Go to "Variables" tab
3. You'll see these variables (keep this tab open):
   - `MYSQLHOST`
   - `MYSQLPORT`
   - `MYSQLDATABASE`
   - `MYSQLUSER`
   - `MYSQLPASSWORD`

### Step 4.2: Configure Laravel Service Variables

1. Click on your **Laravel service** (the one with your app name)
2. Go to "Variables" tab
3. Click "Raw Editor" (top right)
4. Copy and paste this configuration:

```env
# Application
APP_NAME="Salenga Farm System"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
APP_TIMEZONE=Asia/Manila

# Database - Reference MySQL service
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail Configuration (Brevo)
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email@example.com
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@salengafarm.com"
MAIL_FROM_NAME="${APP_NAME}"

# Brevo API Key
BREVO_API_KEY=your_brevo_api_key_here

# File Storage
FILESYSTEM_DISK=public

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
LOG_STACK=single

# Broadcasting
BROADCAST_CONNECTION=log

# Other
BCRYPT_ROUNDS=12
```

### Step 4.3: Update Required Values

**CRITICAL - Update these values:**

1. **APP_KEY:** 
   - Run locally: `php artisan key:generate --show`
   - Copy the output (starts with `base64:`)
   - Paste into Railway

2. **APP_URL:** 
   - Leave as placeholder for now
   - We'll update this after generating domain

3. **Brevo Email Credentials:**
   - Get from https://app.brevo.com
   - Go to SMTP & API → SMTP
   - Copy your SMTP credentials
   - Update `MAIL_USERNAME`, `MAIL_PASSWORD`, and `BREVO_API_KEY`

4. **Save Variables:**
   - Railway auto-saves
   - This will trigger a new deployment

---

## Phase 5: Generate Public Domain (2 minutes)

### Step 5.1: Create Railway Domain

1. In your Laravel service, go to "Settings" tab
2. Scroll to "Networking" section
3. Click "Generate Domain"
4. Railway will give you a URL like: `salenga-farm-production-xxxx.up.railway.app`
5. **Copy this URL**

### Step 5.2: Update APP_URL

1. Go back to "Variables" tab
2. Find `APP_URL`
3. Update it to your Railway domain:
   ```env
   APP_URL=https://salenga-farm-production-xxxx.up.railway.app
   ```
4. Save (will trigger redeploy)

---

## Phase 6: Import Your Database (10 minutes)

You have two options: Import existing data OR run fresh migrations.

### Option A: Import Existing Database (Recommended if you have data)

#### Step 6.1: Export Your Local Database

```bash
# Option 1: Using mysqldump (if you have MySQL locally)
mysqldump -u root -p inventory > salenga_farm_backup.sql

# Option 2: Using Laravel (if using SQLite)
# Just copy your database file
```

#### Step 6.2: Install Railway CLI

```bash
# Install Railway CLI
npm install -g @railway/cli

# Verify installation
railway --version
```

#### Step 6.3: Login and Link Project

```bash
# Login to Railway
railway login

# This will open a browser for authentication
# Click "Authorize" to connect CLI to your account

# Link to your project
railway link

# Select your project from the list
```

#### Step 6.4: Import Database

```bash
# Connect to MySQL
railway connect MySQL

# You're now in MySQL prompt
# Import your database
source salenga_farm_backup.sql

# Or if file is in different location
source /path/to/salenga_farm_backup.sql

# Exit MySQL
exit
```

### Option B: Run Fresh Migrations (If starting fresh)

```bash
# Using Railway CLI
railway run php artisan migrate --force

# Run seeders if you have them
railway run php artisan db:seed --force
```

---

## Phase 7: Verify Deployment (5 minutes)

### Step 7.1: Check Deployment Status

1. Go to your Laravel service
2. Click "Deployments" tab
3. Check latest deployment status
4. Should show: ✅ Success

### Step 7.2: View Deployment Logs

Click on the latest deployment to see logs:

```
✅ composer install --no-dev --optimize-autoloader
✅ npm install
✅ npm run build
✅ php artisan migrate --force
✅ php artisan storage:link
✅ Starting server...
```

### Step 7.3: Test Your Application

Visit your Railway URL: `https://your-app-name.up.railway.app`

**Test these features:**

- [ ] Homepage loads correctly
- [ ] Login page works
- [ ] Can log in with admin account
- [ ] Dashboard displays
- [ ] Plant catalog shows (with images if you have them)
- [ ] Database queries work
- [ ] No 500 errors

### Step 7.4: Check for Errors

If something doesn't work:

1. Click "Deployments" → Latest deployment
2. View logs for errors
3. Common issues:
   - Missing APP_KEY
   - Wrong database credentials
   - Storage permissions
   - Missing migrations

---

## Phase 8: Upload Files (If Needed)

If you have existing files (plant photos, avatars, etc.):

### Option 1: Re-upload Through Application

The easiest way is to re-upload files through your application interface.

### Option 2: Use Railway Volumes (Advanced)

Railway supports persistent storage volumes. See: https://docs.railway.app/guides/volumes

### Option 3: Commit Files to Git (For Display Images)

Your .gitignore already allows some images:

```bash
# These directories are tracked in git
storage/app/public/avatars
storage/app/public/plant-photos
storage/app/public/display-plant-photos
```

If you want to include existing images:

```bash
# Add images to git
git add storage/app/public/display-plant-photos/*
git commit -m "Add display plant photos"
git push

# Railway will automatically redeploy with images
```

---

## Phase 9: Test Email Functionality

### Step 9.1: Verify Brevo Configuration

1. Go to https://app.brevo.com
2. Check your SMTP settings
3. Verify sender email is verified

### Step 9.2: Test Email Sending

```bash
# Using Railway CLI
railway run php artisan tinker

# In tinker, run:
Mail::raw('Test email from Railway', function($msg) {
    $msg->to('your-email@example.com')->subject('Railway Test');
});

# Check your email inbox
```

### Step 9.3: Test Through Application

1. Go to your Railway URL
2. Submit a plant request
3. Check if email is received
4. Check Railway logs for email sending status

---

## Phase 10: Create Admin Account

If you're starting fresh, create an admin account:

```bash
# Using Railway CLI
railway run php artisan tinker

# In tinker:
$user = new App\Models\User();
$user->first_name = 'Admin';
$user->last_name = 'User';
$user->name = 'Admin User';
$user->email = 'admin@salengafarm.com';
$user->password = bcrypt('your-secure-password');
$user->role = 'super_admin';
$user->email_verified_at = now();
$user->save();

# Exit tinker
exit
```

Now you can login with:
- Email: `admin@salengafarm.com`
- Password: `your-secure-password`

---

## Phase 11: Monitoring & Maintenance

### Monitor Usage

1. Go to Railway dashboard
2. Click "Usage" tab
3. Monitor:
   - Execution time
   - Memory usage
   - Database size
   - Network bandwidth

### View Live Logs

```bash
# Using Railway CLI
railway logs

# Or in Railway dashboard
# Click service → Deployments → View logs
```

### Automatic Deployments

Railway automatically deploys when you push to GitHub:

```bash
# Make changes locally
git add .
git commit -m "Update feature"
git push origin main

# Railway automatically detects and deploys
# Check deployment status in Railway dashboard
```

---

## 🚨 Troubleshooting Common Issues

### Issue 1: 500 Internal Server Error

**Check logs:**
```bash
railway logs
```

**Common causes:**
- Missing or invalid APP_KEY
- Wrong database credentials
- Missing storage permissions

**Fix:**
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan optimize:clear
```

### Issue 2: Database Connection Error

**Verify variables:**
1. Check MySQL service is running (green status)
2. Verify DB_* variables use Railway's reference syntax:
   ```env
   DB_HOST=${{MySQL.MYSQLHOST}}
   ```
3. Check MySQL service variables are populated

**Test connection:**
```bash
railway run php artisan tinker

# In tinker:
DB::connection()->getPdo();
# Should not throw error
```

### Issue 3: Images Not Loading

**Check storage link:**
```bash
railway run php artisan storage:link
railway run ls -la public/storage
```

**Ensure directories exist:**
```bash
railway run mkdir -p storage/app/public/avatars
railway run mkdir -p storage/app/public/plant-photos
railway run mkdir -p storage/app/public/display-plant-photos
railway run chmod -R 775 storage
```

### Issue 4: CSS/JS Not Loading

**Check APP_URL:**
```env
APP_URL=https://your-actual-railway-domain.up.railway.app
```

**Rebuild assets locally and push:**
```bash
npm run build
git add public/build
git commit -m "Rebuild assets for production"
git push
```

### Issue 5: Email Not Sending

**Check Brevo credentials:**
```bash
railway run php artisan config:clear

# Test email
railway run php artisan tinker
Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });
```

**Check Brevo dashboard:**
- Verify sender email is verified
- Check sending limits
- View email logs

### Issue 6: Migrations Not Running

**Run manually:**
```bash
railway run php artisan migrate --force
```

**Check migration status:**
```bash
railway run php artisan migrate:status
```

**Reset and re-run (CAUTION: Deletes all data):**
```bash
railway run php artisan migrate:fresh --force
```

---

## 📊 Railway CLI Quick Reference

```bash
# Installation
npm install -g @railway/cli

# Authentication
railway login
railway logout

# Project Management
railway link                    # Link to project
railway unlink                  # Unlink project
railway status                  # Check project status

# Deployments
railway up                      # Deploy manually
railway logs                    # View logs
railway logs --follow           # Follow logs in real-time

# Running Commands
railway run [command]           # Run any command
railway run php artisan migrate # Run migrations
railway run php artisan tinker  # Open tinker

# Database
railway connect MySQL           # Connect to MySQL
railway variables               # View all variables

# Services
railway service                 # Select service
railway open                    # Open service in browser
```

---

## 💰 Cost Management Tips

### Stay Within Free Tier ($5/month):

1. **Optimize Performance:**
   ```bash
   railway run php artisan config:cache
   railway run php artisan route:cache
   railway run php artisan view:cache
   ```

2. **Use Production Settings:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   LOG_LEVEL=error
   ```

3. **Monitor Usage:**
   - Check Railway dashboard weekly
   - Set up usage alerts
   - Optimize database queries

4. **Reduce Execution Time:**
   - Enable caching
   - Optimize images
   - Use CDN for static assets (if needed)

### Typical Costs:
- **Free tier:** $5 credit/month (sufficient for small projects)
- **If exceeded:** ~$5-10/month for small apps
- **Database:** Included in free tier (1GB)

---

## ✅ Final Checklist

Before considering deployment complete:

- [ ] Application loads without errors
- [ ] Can login with admin account
- [ ] Database queries work correctly
- [ ] All pages load properly
- [ ] Images display correctly
- [ ] Email sending works
- [ ] PDF generation works
- [ ] File uploads work
- [ ] All features tested
- [ ] No console errors
- [ ] Mobile responsive
- [ ] Railway domain is accessible
- [ ] Automatic deployments work (test with a small change)

---

## 🎓 For Your Thesis Documentation

Include these in your documentation:

1. **Deployment Architecture Diagram:**
   ```
   GitHub Repository
         ↓
   Railway Platform
         ↓
   ├─ Laravel Application (PHP 8.2)
   ├─ MySQL Database
   └─ File Storage
   ```

2. **Technology Stack:**
   - Hosting: Railway
   - Framework: Laravel 11
   - Database: MySQL 8.0
   - Email: Brevo API
   - Frontend: Bootstrap 5, Chart.js
   - Build: Nixpacks

3. **Deployment Process:**
   - Automatic deployment via GitHub integration
   - Build time: ~3-5 minutes
   - Zero-downtime deployments
   - Automatic HTTPS

4. **Screenshots to Include:**
   - Railway dashboard
   - Deployment logs
   - Application running on Railway domain
   - Database connection

---

## 📞 Support Resources

- **Railway Docs:** https://docs.railway.app
- **Railway Discord:** https://discord.gg/railway
- **Laravel Docs:** https://laravel.com/docs/11.x
- **Your Railway Dashboard:** https://railway.app/dashboard

---

## 🎉 Success!

Your Salenga Farm system is now deployed on Railway!

**Your URLs:**
- Application: `https://your-app-name.up.railway.app`
- Railway Dashboard: `https://railway.app/project/your-project-id`

**Next Steps:**
1. Test all features thoroughly
2. Set up regular database backups
3. Monitor usage and performance
4. Document for your thesis
5. Prepare for final defense

Good luck with your thesis defense! 🚀

---

**Document Version:** 2.0  
**Created:** March 25, 2026  
**For:** Salenga Farm System Deployment
