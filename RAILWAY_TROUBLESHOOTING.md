# Railway Deployment - Troubleshooting Guide

## 🔍 Common Issues & Solutions

---

## Issue 1: Build Failed

### Symptoms
- Deployment shows "Failed" status
- Build logs show errors
- Application doesn't start

### Solutions

**Check Build Logs:**
1. Go to Deployments tab
2. Click failed deployment
3. Read error messages

**Common Causes:**

**A. Composer Install Failed**
```
Error: Your requirements could not be resolved
```
**Fix:**
```bash
# Locally, update composer.lock
composer update
git add composer.lock
git commit -m "Update composer.lock"
git push
```

**B. NPM Install Failed**
```
Error: Cannot find module
```
**Fix:**
```bash
# Locally, rebuild node_modules
rm -rf node_modules package-lock.json
npm install
git add package-lock.json
git commit -m "Update package-lock"
git push
```

**C. Build Assets Failed**
```
Error: npm run build failed
```
**Fix:**
```bash
# Test locally first
npm run build

# If works, commit and push
git add public/build
git commit -m "Rebuild assets"
git push
```

---

## Issue 2: 500 Internal Server Error

### Symptoms
- White page with "500 | Server Error"
- Application crashes on load

### Solutions

**Step 1: Check Logs**
```bash
railway logs
```

**Step 2: Clear All Caches**
```bash
railway run php artisan optimize:clear
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan route:clear
railway run php artisan view:clear
```

**Step 3: Check APP_KEY**
```bash
# Generate new key locally
php artisan key:generate --show

# Copy output (starts with base64:)
# Add to Railway variables
```

**Step 4: Check File Permissions**
```bash
railway run chmod -R 775 storage
railway run chmod -R 775 bootstrap/cache
```

**Step 5: Check .env Variables**
Ensure these are set in Railway:
- `APP_KEY` (must start with `base64:`)
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` (must match Railway domain)

---

## Issue 3: Database Connection Error

### Symptoms
```
SQLSTATE[HY000] [2002] Connection refused
SQLSTATE[HY000] [1045] Access denied
```

### Solutions

**Step 1: Verify MySQL Service is Running**
1. Go to Railway dashboard
2. Check MySQL service has green status
3. If red, restart it

**Step 2: Check Database Variables**

In Laravel service variables, ensure you're using Railway's reference syntax:

```env
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

**NOT this:**
```env
# ❌ WRONG - Don't use hardcoded values
DB_HOST=containers-us-west-123.railway.app
```

**Step 3: Test Connection**
```bash
railway run php artisan tinker

# In tinker:
DB::connection()->getPdo();
# Should return PDO object, not error
```

**Step 4: Check MySQL Service Variables**
1. Click MySQL service
2. Go to Variables tab
3. Verify these exist:
   - MYSQLHOST
   - MYSQLPORT
   - MYSQLDATABASE
   - MYSQLUSER
   - MYSQLPASSWORD

**Step 5: Restart Services**
1. Restart MySQL service
2. Restart Laravel service
3. Wait for both to be green

---

## Issue 4: Images Not Loading / 404 on Images

### Symptoms
- Plant photos show broken image icon
- Avatars don't display
- 404 errors in console for `/storage/` URLs

### Solutions

**Step 1: Create Storage Link**
```bash
railway run php artisan storage:link
```

**Step 2: Verify Link Exists**
```bash
railway run ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

**Step 3: Check Directory Permissions**
```bash
railway run chmod -R 775 storage
railway run chmod -R 775 storage/app/public
```

**Step 4: Verify Directories Exist**
```bash
railway run mkdir -p storage/app/public/avatars
railway run mkdir -p storage/app/public/plant-photos
railway run mkdir -p storage/app/public/display-plant-photos
railway run mkdir -p storage/app/public/site-visits
```

**Step 5: Check APP_URL**
Ensure APP_URL in Railway variables matches your domain:
```env
APP_URL=https://your-actual-domain.up.railway.app
```

**Step 6: Re-upload Images**
If images still don't show, you may need to re-upload them through the application.

---

## Issue 5: CSS/JS Not Loading (Blank Page)

### Symptoms
- Page loads but no styling
- Console shows 404 for CSS/JS files
- Mix manifest not found error

### Solutions

**Step 1: Check APP_URL**
```env
# Must match your Railway domain exactly
APP_URL=https://your-app-name.up.railway.app
```

**Step 2: Rebuild Assets Locally**
```bash
# Clean build
rm -rf public/build
npm run build

# Commit and push
git add public/build
git commit -m "Rebuild production assets"
git push
```

**Step 3: Clear Laravel Caches**
```bash
railway run php artisan optimize:clear
railway run php artisan config:cache
```

**Step 4: Check Vite Configuration**
Ensure `vite.config.js` has correct settings:
```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

**Step 5: Verify Build Files Exist**
```bash
railway run ls -la public/build
# Should show manifest.json and assets
```

---

## Issue 6: Email Not Sending

### Symptoms
- Emails not received
- "Failed to send email" errors
- Timeout errors

### Solutions

**Step 1: Verify Brevo Credentials**

Check Railway variables:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_actual_email@example.com
MAIL_PASSWORD=your_actual_smtp_key
MAIL_ENCRYPTION=tls
BREVO_API_KEY=your_actual_api_key
```

**Step 2: Check Brevo Dashboard**
1. Go to https://app.brevo.com
2. Check SMTP & API settings
3. Verify sender email is verified
4. Check sending limits (300 emails/day free)

**Step 3: Test Email Sending**
```bash
railway run php artisan tinker

# In tinker:
Mail::raw('Test email from Railway', function($message) {
    $message->to('your-email@example.com')
            ->subject('Railway Email Test');
});

# Check your inbox
```

**Step 4: Check Logs**
```bash
railway logs | grep -i mail
railway logs | grep -i brevo
```

**Step 5: Clear Config Cache**
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
```

**Step 6: Verify Email Service**
```bash
railway run php artisan tinker

# Test Brevo service
$service = new \App\Services\BrevoEmailService();
$result = $service->sendEmail(
    'test@example.com',
    'Test Subject',
    '<p>Test email</p>'
);
print_r($result);
```

---

## Issue 7: Migrations Not Running

### Symptoms
- Tables don't exist
- Migration errors in logs
- "Table not found" errors

### Solutions

**Step 1: Check Migration Status**
```bash
railway run php artisan migrate:status
```

**Step 2: Run Migrations Manually**
```bash
railway run php artisan migrate --force
```

**Step 3: Check Database Connection**
```bash
railway run php artisan tinker

# Test connection
DB::connection()->getPdo();
```

**Step 4: Check Procfile/railway.json**

Ensure migrations run on deployment:

`railway.json`:
```json
{
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
  }
}
```

**Step 5: Fresh Migration (CAUTION: Deletes all data)**
```bash
# Only if you want to start fresh
railway run php artisan migrate:fresh --force
```

---

## Issue 8: Session/Login Issues

### Symptoms
- Can't stay logged in
- "Session expired" errors
- CSRF token mismatch

### Solutions

**Step 1: Check Session Configuration**
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

**Step 2: Ensure Session Table Exists**
```bash
railway run php artisan session:table
railway run php artisan migrate --force
```

**Step 3: Clear All Caches**
```bash
railway run php artisan optimize:clear
```

**Step 4: Check APP_URL**
```env
# Must match your domain exactly (no trailing slash)
APP_URL=https://your-app-name.up.railway.app
```

**Step 5: Check Cookie Settings**

In `config/session.php`, ensure:
```php
'domain' => env('SESSION_DOMAIN', null),
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => 'lax',
```

---

## Issue 9: PDF Generation Fails

### Symptoms
- "Failed to generate PDF" errors
- Blank PDFs
- Timeout errors

### Solutions

**Step 1: Check DomPDF Package**
```bash
# Verify package is installed
railway run composer show barryvdh/laravel-dompdf
```

**Step 2: Increase Memory Limit**

Add to Railway variables:
```env
PHP_MEMORY_LIMIT=512M
```

**Step 3: Test PDF Generation**
```bash
railway run php artisan tinker

# Test PDF
$pdf = PDF::loadView('pdf.rfq', ['request' => App\Models\PlantRequest::first()]);
$pdf->save(storage_path('test.pdf'));
```

**Step 4: Check Font Files**
```bash
railway run ls -la vendor/dompdf/dompdf/lib/fonts
```

**Step 5: Clear Cache**
```bash
railway run php artisan view:clear
railway run php artisan config:clear
```

---

## Issue 10: High Memory Usage / Slow Performance

### Symptoms
- Application is slow
- Timeouts
- Railway usage exceeds free tier

### Solutions

**Step 1: Enable Production Caching**
```bash
railway run php artisan config:cache
railway run php artisan route:cache
railway run php artisan view:cache
```

**Step 2: Optimize Composer Autoloader**
```bash
railway run composer dump-autoload --optimize
```

**Step 3: Check Database Queries**

Add to Railway variables:
```env
DB_SLOW_QUERY_LOG=true
```

**Step 4: Reduce Logging**
```env
LOG_LEVEL=error
LOG_CHANNEL=stack
```

**Step 5: Optimize Images**
- Compress images before uploading
- Use appropriate image sizes
- Consider using image optimization service

**Step 6: Monitor Usage**
1. Go to Railway dashboard
2. Check "Usage" tab
3. Identify high-usage periods
4. Optimize accordingly

---

## 🆘 Emergency Commands

### Complete Reset (Use with caution)
```bash
# Clear all caches
railway run php artisan optimize:clear

# Restart application
# Go to Railway dashboard → Service → Settings → Restart

# Fresh migration (DELETES ALL DATA)
railway run php artisan migrate:fresh --force

# Recreate storage link
railway run php artisan storage:link
```

### Check Everything
```bash
# Check PHP version
railway run php -v

# Check Laravel version
railway run php artisan --version

# Check environment
railway run php artisan env

# Check database connection
railway run php artisan tinker
DB::connection()->getPdo();

# Check storage permissions
railway run ls -la storage
railway run ls -la bootstrap/cache

# Check routes
railway run php artisan route:list

# Check config
railway run php artisan config:show
```

---

## 📞 Getting Help

### Railway Support
- **Docs:** https://docs.railway.app
- **Discord:** https://discord.gg/railway
- **Status:** https://status.railway.app

### Laravel Support
- **Docs:** https://laravel.com/docs/11.x
- **Forums:** https://laracasts.com/discuss
- **Discord:** https://discord.gg/laravel

### Check Logs First
```bash
# View recent logs
railway logs

# Follow logs in real-time
railway logs --follow

# Filter logs
railway logs | grep -i error
railway logs | grep -i exception
```

---

## 📋 Diagnostic Checklist

When something goes wrong, check these in order:

- [ ] Railway services are running (green status)
- [ ] Latest deployment succeeded
- [ ] APP_KEY is set correctly
- [ ] Database variables use `${{MySQL.VARIABLE}}` syntax
- [ ] APP_URL matches Railway domain
- [ ] Storage link exists
- [ ] Migrations have run
- [ ] Logs show no errors
- [ ] Browser console shows no errors
- [ ] Email credentials are correct (if using email)

---

**Last Updated:** March 25, 2026  
**For:** Salenga Farm System on Railway
