# Railway Deployment - Quick Checklist

## ⚡ Quick Start (30 minutes)

### 1️⃣ GitHub Setup (5 min)
```bash
# Remove old remote
git remote remove origin

# Add new remote (replace YOUR-USERNAME)
git remote add origin https://github.com/YOUR-USERNAME/salenga-farm-system.git

# Push to new repo
git branch -M main
git push -u origin main
```

### 2️⃣ Railway Account (5 min)
- [ ] Go to https://railway.app
- [ ] Login with GitHub
- [ ] Add payment method (for verification)
- [ ] Get $5 free credit

### 3️⃣ Deploy Project (5 min)
- [ ] Click "New Project"
- [ ] Select "Deploy from GitHub repo"
- [ ] Choose your repository
- [ ] Wait for build to complete

### 4️⃣ Add Database (2 min)
- [ ] Click "New" → "Database" → "Add MySQL"
- [ ] Wait for provisioning

### 5️⃣ Configure Variables (10 min)
Click Laravel service → Variables → Raw Editor:

```env
APP_NAME="Salenga Farm System"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email
MAIL_PASSWORD=your_brevo_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@salengafarm.com"
MAIL_FROM_NAME="${APP_NAME}"

BREVO_API_KEY=your_brevo_api_key

FILESYSTEM_DISK=public
LOG_CHANNEL=stack
LOG_LEVEL=error
```

**Get APP_KEY:**
```bash
php artisan key:generate --show
```

### 6️⃣ Generate Domain (2 min)
- [ ] Settings → Networking → Generate Domain
- [ ] Copy domain URL
- [ ] Update APP_URL in variables

### 7️⃣ Import Database (5 min)
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login and link
railway login
railway link

# Import database
railway connect MySQL
source salenga_farm_backup.sql
exit
```

### 8️⃣ Test Application (5 min)
- [ ] Visit your Railway URL
- [ ] Test login
- [ ] Check dashboard
- [ ] Verify database works
- [ ] Test email (optional)

---

## 🔧 Essential Commands

```bash
# View logs
railway logs

# Run migrations
railway run php artisan migrate --force

# Clear cache
railway run php artisan optimize:clear

# Create admin user
railway run php artisan tinker

# Connect to database
railway connect MySQL

# Check status
railway status
```

---

## 🚨 Quick Troubleshooting

**500 Error?**
```bash
railway run php artisan config:clear
railway run php artisan optimize:clear
```

**Database Error?**
- Check MySQL service is running (green)
- Verify DB variables use `${{MySQL.VARIABLE}}`

**Images Not Loading?**
```bash
railway run php artisan storage:link
```

**CSS/JS Not Loading?**
- Check APP_URL matches Railway domain
- Rebuild: `npm run build && git push`

---

## 📝 Important URLs

- **Railway Dashboard:** https://railway.app/dashboard
- **Your App:** https://your-app-name.up.railway.app
- **Brevo Dashboard:** https://app.brevo.com
- **GitHub Repo:** https://github.com/YOUR-USERNAME/salenga-farm-system

---

## ✅ Final Verification

- [ ] App loads without errors
- [ ] Login works
- [ ] Database connected
- [ ] Images display
- [ ] Email works
- [ ] All features tested
- [ ] No console errors

---

**Time to Complete:** ~30 minutes  
**Cost:** Free ($5 monthly credit)

For detailed instructions, see: `RAILWAY_SETUP_NEW_ACCOUNT.md`
