# Deployment Checklist for DigitalOcean

## ✅ Pre-Deployment (Completed)
- [x] Code committed to Git
- [x] Code pushed to GitHub/remote repository
- [x] Comprehensive commit message created

## 📋 Deployment Steps on DigitalOcean Server

### 1. Connect to Your Server
```bash
ssh your-user@your-server-ip
# OR use DigitalOcean console
```

### 2. Navigate to Project Directory
```bash
cd /path/to/salengafarm
# Example: cd /var/www/salengafarm
```

### 3. Pull Latest Changes
```bash
git pull origin main
```

### 4. Run Database Migrations
```bash
php artisan migrate
```

**This will create:**
- `mfa_enabled` and `mfa_enabled_at` columns in users table
- `encrypted_files` table
- `mfa_attempts` table

### 5. Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 6. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Set Proper Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Restart Services (if needed)
```bash
# For PHP-FPM
sudo systemctl restart php8.2-fpm

# For Nginx
sudo systemctl restart nginx

# For Apache
sudo systemctl restart apache2
```

## ✅ Post-Deployment Verification

### 1. Check Website Loads
- Open https://salengafarm.com
- Verify homepage loads correctly

### 2. Test User Management
- Login as admin
- Go to User Management
- Check if table displays correctly
- Verify checkboxes are square (not triangular)
- Test on mobile device/responsive mode

### 3. Test Export Functions
- Go to Audit Logs
- Click Export dropdown
- Verify Excel and CSV options appear
- Test downloading both formats

### 4. Test Encryption (Existing Files)
- Go to Requests or Site Visits
- View any record with uploaded files
- Verify files download correctly
- Check encrypted_files table has entries:
```bash
php artisan tinker
>>> \App\Models\EncryptedFile::count()
```

### 5. Test Sidebar
- Verify logout button displays correctly
- Click logout
- Confirm SweetAlert2 dialog appears
- Verify logout works

### 6. Mobile Testing
- Open site on mobile or use browser dev tools (F12 → Toggle Device Toolbar)
- Check User Management table scrolls horizontally
- Verify statistics cards are compact
- Test all button interactions

## 🔧 Troubleshooting

### If migrations fail:
```bash
# Check migration status
php artisan migrate:status

# If table already exists, skip specific migration
php artisan migrate --skip
```

### If views don't update:
```bash
php artisan view:clear
php artisan cache:clear
```

### If routes give 404:
```bash
php artisan route:clear
php artisan route:cache
```

### If permissions are wrong:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
```

### Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

## 📝 What Was Deployed

### Security Features (Active):
✅ File encryption for uploads (RFQ, Inquiry, Site Visits)
✅ Encrypted file storage and retrieval
✅ Audit log Excel export
✅ Enhanced security logging

### UI Improvements (Active):
✅ Fixed sidebar logout button
✅ User Management responsive table
✅ Perfect square checkboxes on all devices
✅ Compact mobile statistics cards
✅ Optimized spacing and layout

### MFA Foundation (Prepared, Not Active Yet):
⚠️ Database tables created (mfa_enabled columns, mfa_attempts table)
⚠️ MFA is NOT yet active for users
⚠️ Will implement in next phase today

## 🎯 Next Phase: MFA Implementation

After confirming deployment successful:
1. Test all existing features work
2. Begin MFA implementation following `EMAIL_MFA_IMPLEMENTATION_GUIDE.md`
3. Implement step-by-step carefully
4. Test thoroughly before enabling for users

## 📊 Expected Changes Visible Immediately

1. **User Management Page:**
   - Checkboxes look perfect (squares, not triangles)
   - Table scrolls on mobile
   - Statistics cards are compact on mobile
   - Proper spacing

2. **Audit Logs:**
   - Export dropdown shows Excel and CSV
   - Both export formats work

3. **Requests/Site Visits:**
   - Files automatically encrypt on upload
   - Files decrypt on download (transparent to users)
   - Check database: `encrypted_files` table has entries

4. **Sidebar:**
   - Logout button positioned correctly
   - Logout dialog appears (SweetAlert2)

5. **Mobile Experience:**
   - All pages responsive
   - Tables scroll horizontally
   - Touch-friendly elements

## ⏱️ Estimated Deployment Time

- SSH connection: 1 minute
- Git pull: 1 minute
- Migrations: 1 minute
- Cache clear/rebuild: 2 minutes
- Verification testing: 5-10 minutes

**Total: ~10-15 minutes**

## 🚨 Rollback Plan (If Something Goes Wrong)

### Option 1: Revert to Previous Commit
```bash
git log --oneline -5  # See last 5 commits
git reset --hard <previous-commit-hash>
php artisan migrate:rollback
php artisan cache:clear
```

### Option 2: Restore from Backup
```bash
# Restore database from DigitalOcean backup
# Restore files from snapshot
```

## ✅ Success Criteria

Deployment is successful when:
- [ ] Website loads without errors
- [ ] User Management table works perfectly
- [ ] Checkboxes are square on mobile
- [ ] Export (Excel/CSV) works
- [ ] File uploads/downloads work
- [ ] Logout functionality works
- [ ] No error logs in `storage/logs/laravel.log`
- [ ] Mobile experience is smooth

## 📞 After Deployment

Once verified successful:
1. ✅ Mark deployment complete
2. 🎯 Begin MFA implementation
3. 📝 Follow `EMAIL_MFA_IMPLEMENTATION_GUIDE.md`
4. 🧪 Test each MFA component before moving to next
5. ✅ Complete MFA in one focused session today

---

**Ready to deploy! Follow steps 1-8, then verify with post-deployment checks.**
