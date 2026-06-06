# 🚀 Deploy File Encryption System - Complete Guide

## ✅ What Has Been Implemented

### Core Infrastructure:
- ✅ `app/Services/EncryptionService.php` - Encryption/decryption service
- ✅ `app/Models/EncryptedFile.php` - Database model for tracking
- ✅ `app/Http/Controllers/SecureFileController.php` - Secure download/view
- ✅ `app/Console/Commands/EncryptExistingFiles.php` - Migrate old files
- ✅ `database/migrations/2026_06_06_000000_create_encrypted_files_table.php`

### Controllers Updated:
- ✅ `ClientRequestController.php` - RFQ PDFs now encrypted
- ✅ `UserPlantRequestController.php` - Inquiry PDFs now encrypted
- ✅ Both support backward compatibility (old files still work)

### Routes Added:
- ✅ `/secure-file/download/{id}` - Download encrypted files
- ✅ `/secure-file/view/{id}` - View encrypted files in browser

### Documentation:
- ✅ `FILE_ENCRYPTION_GUIDE.md` - Complete user guide
- ✅ `ENCRYPTION_TESTING_GUIDE.md` - Step-by-step testing instructions
- ✅ `EXISTING_FILES_ENCRYPTION.md` - How to encrypt old files
- ✅ `ENCRYPTION_IMPLEMENTATION_SUMMARY.md` - Technical details
- ✅ This file - Deployment guide

---

## 📦 Files to Commit

```bash
# New Files:
app/Services/EncryptionService.php
app/Models/EncryptedFile.php
app/Http/Controllers/SecureFileController.php
app/Console/Commands/EncryptExistingFiles.php
database/migrations/2026_06_06_000000_create_encrypted_files_table.php

# Modified Files:
app/Http/Controllers/ClientRequestController.php
app/Http/Controllers/UserPlantRequestController.php
app/Services/AuditService.php
routes/web.php

# Documentation:
FILE_ENCRYPTION_GUIDE.md
ENCRYPTION_TESTING_GUIDE.md
EXISTING_FILES_ENCRYPTION.md
ENCRYPTION_IMPLEMENTATION_SUMMARY.md
ENCRYPTION_STATUS_SUMMARY.md
DEPLOY_ENCRYPTION_NOW.md
```

---

## 🎯 Deployment Steps

### STEP 1: Local Testing (Required First!)

```bash
# 1. Run migration
php artisan migrate

# 2. Create encrypted directory
mkdir -p storage/app/encrypted
chmod 775 storage/app/encrypted

# 3. Test RFQ PDF generation
# (Create a new plant request through the UI)
# Check: ls storage/app/encrypted/

# 4. Test download
# (Download the PDF through the UI)
# Verify: PDF opens and is readable

# 5. Check database
php artisan tinker
App\Models\EncryptedFile::count()  # Should be > 0
App\Models\EncryptedFile::latest()->first()  # View details
exit

# 6. Check logs
tail -30 storage/logs/laravel.log
# Look for: "PDF encrypted successfully"
```

### STEP 2: Encrypt Existing Files (Optional but Recommended)

```bash
# Preview what will be encrypted
php artisan files:encrypt-existing --dry-run

# Encrypt PDF files only (safest to start)
php artisan files:encrypt-existing --type=pdfs

# Or encrypt everything
php artisan files:encrypt-existing

# Verify
App\Models\EncryptedFile::count()
ls storage/app/encrypted/ | wc -l
```

### STEP 3: Commit to GitHub

```bash
# Stage all changes
git add -A

# Commit
git commit -m "Implement file encryption system for confidential documents

Features:
- AES-256-CBC encryption for RFQ PDFs, inquiry PDFs, and site visit docs
- EncryptionService handles encryption/decryption transparently
- SecureFileController manages authorized access
- Audit logging for all file access (download/view)
- Backward compatible with existing unencrypted files
- Command to migrate existing files: php artisan files:encrypt-existing

Security:
- Files stored in non-public directory (storage/app/encrypted/)
- Access control: admins access all, users access own files only
- No temporary unencrypted copies (memory-only decryption)
- Encrypted files tracked in encrypted_files database table

Documentation:
- FILE_ENCRYPTION_GUIDE.md - Complete user guide
- ENCRYPTION_TESTING_GUIDE.md - Testing procedures
- EXISTING_FILES_ENCRYPTION.md - Migration guide"

# Push to GitHub
git push origin main
```

### STEP 4: Deploy to Production

```bash
# SSH into production server
ssh root@your-server-ip
cd /var/www/salengafarm

# Pull latest changes
git pull origin main

# Create encrypted directory
mkdir -p storage/app/encrypted
chmod 775 storage/app/encrypted
chown www-data:www-data storage/app/encrypted

# Run migration
php artisan migrate

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Restart PHP-FPM (if needed)
systemctl restart php8.3-fpm  # or your PHP version

# Optional: Encrypt existing files
php artisan files:encrypt-existing --dry-run  # Preview first
php artisan files:encrypt-existing            # Then encrypt
```

### STEP 5: Verify Production

```bash
# Check migration ran
php artisan tinker
App\Models\EncryptedFile::count()
exit

# Check logs
tail -50 storage/logs/laravel.log

# Test through website
# 1. Create new request
# 2. Download PDF
# 3. Verify it works
```

---

## 🧪 Quick Testing Checklist

After deployment, test these:

### Test 1: Create New Request
```
✓ Log in as admin
✓ Create new plant request
✓ PDF generates successfully
✓ Check: ls storage/app/encrypted/  (should have new file)
```

### Test 2: Download PDF
```
✓ Click "Download PDF" button
✓ PDF downloads
✓ PDF opens and is readable
✓ No errors in browser console
```

### Test 3: View PDF in Browser
```
✓ Click "View PDF" button
✓ PDF displays inline
✓ Content is readable
✓ No decryption errors
```

### Test 4: Check Audit Log
```
✓ Open Audit Trail (green button in dashboard)
✓ Look for "File Downloaded" entries
✓ Verify user, file, timestamp are logged
```

### Test 5: Authorization
```
✓ Log out
✓ Log in as different user
✓ Try accessing another user's PDF
✓ Should be blocked (403 Forbidden)
```

---

## 📂 Where Encrypted Files Are Stored

### Physical Location:
```
storage/app/encrypted/
├── 20260606120000_abc123def456.enc
├── 20260606120100_def789ghi012.enc
└── ...
```

### Database Tracking:
```sql
SELECT * FROM encrypted_files;
```

### Checking via Tinker:
```bash
php artisan tinker
App\Models\EncryptedFile::all()
App\Models\EncryptedFile::count()
App\Models\EncryptedFile::latest()->first()
```

---

## ⚠️ CRITICAL Warnings

### 1. **NEVER Change APP_KEY After Encryption**
```bash
# ❌ DO NOT DO THIS:
# Change APP_KEY in .env

# If you change it, ALL encrypted files become permanently unrecoverable!
```

**Backup APP_KEY:**
```bash
# Backup .env file
cp .env .env.backup

# Store APP_KEY in password manager
# Document in secure location
```

### 2. **Backup Before Migrating Existing Files**
```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/

# Backup database
mysqldump -u root -p database_name > backup_$(date +%Y%m%d).sql
```

### 3. **Test Locally First**
- Never test encryption directly on production
- Always run on local/staging first
- Use `--dry-run` to preview changes

---

## 🔍 Verification Commands

### Check Encryption Status:
```bash
# Count encrypted files
php artisan tinker
App\Models\EncryptedFile::count()

# List recent encrypted files
App\Models\EncryptedFile::latest()->take(5)->get()

# Check specific file
App\Models\EncryptedFile::where('original_filename', 'like', '%rfq%')->first()
```

### Check File System:
```bash
# List encrypted files
ls -lah storage/app/encrypted/

# Count files
ls storage/app/encrypted/ | wc -l

# Check disk usage
du -sh storage/app/encrypted/
```

### Check Logs:
```bash
# Recent encryption logs
tail -50 storage/logs/laravel.log | grep -i encrypt

# Recent file access logs
tail -50 storage/logs/laravel.log | grep -i "File Downloaded\|File Viewed"
```

---

## 🐛 Troubleshooting

### Issue: "Failed to encrypt file"

**Check:**
1. File permissions: `chmod -R 775 storage/`
2. Disk space: `df -h`
3. Laravel log: `tail -50 storage/logs/laravel.log`

**Fix:**
```bash
chmod -R 775 storage/
chown -R www-data:www-data storage/  # Linux
```

### Issue: Downloaded PDF is garbled/corrupted

**Cause:** Decryption failed

**Check:**
1. APP_KEY unchanged: `grep APP_KEY .env`
2. Encrypted file exists: `ls storage/app/encrypted/`
3. Database record exists: Check `encrypted_files` table

**Fix:**
- If APP_KEY changed: Restore from backup
- If file missing: Check backups
- If database record missing: Re-upload file

### Issue: "Unauthorized access"

**Cause:** User doesn't have permission

**Check:**
- User owns the file (email matches)
- User has admin role (for all files)
- Check audit logs for access attempt

### Issue: Command hangs during migration

**Cause:** Too many files or memory limit

**Fix:**
```bash
# Increase memory limit
php -d memory_limit=512M artisan files:encrypt-existing --type=pdfs

# Or run in batches
php artisan files:encrypt-existing --type=pdfs
php artisan files:encrypt-existing --type=site-visits
```

---

## 📊 Monitoring After Deployment

### First Hour:
```bash
# Watch logs continuously
tail -f storage/logs/laravel.log

# Check for errors
grep -i error storage/logs/laravel.log | tail -20
```

### First Day:
```bash
# Count encrypted files
App\Models\EncryptedFile::count()

# Check audit logs
App\Models\AuditLog::where('entity_type', 'EncryptedFile')->count()

# Monitor disk space
df -h
```

### First Week:
- Review error logs daily
- Check user feedback
- Monitor server performance
- Verify backups working

---

## 🎓 What Users Will See

### Users Will NOT Notice:
- ✅ Upload process same as before
- ✅ Download process same as before
- ✅ Files look and work exactly the same
- ✅ No "encryption" mentioned in UI

### Behind the Scenes:
- 🔐 Files encrypted on upload
- 🔓 Files decrypted on download
- 📊 All access logged
- 🔒 Stored securely

**This is intentional - encryption should be transparent to users!**

---

## 📞 Support & Help

### For Technical Issues:
1. Check `storage/logs/laravel.log`
2. Review `ENCRYPTION_TESTING_GUIDE.md`
3. Verify APP_KEY unchanged
4. Check file permissions
5. Review database records

### For Testing:
- Follow `ENCRYPTION_TESTING_GUIDE.md` step-by-step
- Use the test results template
- Document any issues

### For Migration:
- Follow `EXISTING_FILES_ENCRYPTION.md`
- Start with `--dry-run`
- Run in batches if needed
- Monitor logs during migration

---

## ✅ Final Checklist Before Deployment

- [ ] Tested locally and everything works
- [ ] Migration runs without errors
- [ ] Backup created (.env, storage/, database)
- [ ] APP_KEY backed up securely
- [ ] Documentation reviewed
- [ ] Testing plan ready
- [ ] Rollback plan prepared
- [ ] Team notified of deployment
- [ ] Maintenance window scheduled (if needed)
- [ ] Monitoring tools ready

---

## 🎯 Success Criteria

Deployment is successful when:
- ✅ New PDFs are encrypted automatically
- ✅ Users can download PDFs normally
- ✅ Files decrypt transparently
- ✅ Authorization works correctly
- ✅ Audit logs show file access
- ✅ No errors in logs
- ✅ No user complaints
- ✅ Old files still work (if not migrated)

---

## 🚀 Ready to Deploy!

**You have everything you need:**
1. ✅ Complete implementation
2. ✅ Comprehensive testing guide
3. ✅ Step-by-step deployment instructions
4. ✅ Troubleshooting documentation
5. ✅ Monitoring guidelines

**Next Steps:**
1. Test locally using `ENCRYPTION_TESTING_GUIDE.md`
2. Commit changes to GitHub
3. Deploy to production
4. Run tests on production
5. Monitor for first 24 hours

**Good luck! 🎉**

---

**Version:** 1.0  
**Created:** June 6, 2026  
**Status:** 🟢 READY FOR DEPLOYMENT
