# Encrypting Existing Files Guide

## ⚠️ Important: Existing Files Are NOT Automatically Encrypted

### Current Status:
- ✅ **NEW uploads** will be encrypted automatically
- ❌ **EXISTING files** remain unencrypted until you run the encryption command

---

## 🔍 Check for Unencrypted Files

### Preview what would be encrypted (dry run):
```bash
php artisan files:encrypt-existing --dry-run
```

**This will show you:**
- How many files need encryption
- Breakdown by type (Site Visits, RFQ PDFs, Inquiry PDFs)
- No actual encryption happens (safe to run)

### Example Output:
```
🔍 Scanning for unencrypted files...

+------------------------------+-------+
| File Type                    | Count |
+------------------------------+-------+
| Site Visit - Client Data     | 15    |
| Site Visit - Proposals       | 8     |
| Site Visit - Media Files     | 23    |
| RFQ PDFs                     | 42    |
| Inquiry PDFs                 | 17    |
| TOTAL                        | 105   |
+------------------------------+-------+

🔍 DRY RUN MODE - No files were encrypted
```

---

## 🔐 Encrypt Existing Files

### Option 1: Encrypt ALL existing files (Recommended)
```bash
php artisan files:encrypt-existing
```

**Interactive mode:**
- Shows summary of files to encrypt
- Asks for confirmation
- Progress bar during encryption
- Reports success/failures

### Option 2: Encrypt specific file types only

**Site visit documents only:**
```bash
php artisan files:encrypt-existing --type=site-visits
```

**PDF files only (RFQ + Inquiry PDFs):**
```bash
php artisan files:encrypt-existing --type=pdfs
```

### Option 3: Force without confirmation (for scripts)
```bash
php artisan files:encrypt-existing --force
```

⚠️ **Use with caution** - skips confirmation prompt

---

## 📊 What Gets Encrypted

### Site Visit Files:
1. **Client Data Uploads**
   - PDFs, images uploaded by clients
   - Contract documents
   - Site survey data
   - Stored in: `storage/app/public/site-visits/`

2. **Proposal Documents**
   - Quotations from admin
   - Design proposals
   - Excel budgets
   - Stored in: `storage/app/public/site-visits/`

3. **Site Visit Media**
   - Photos from site inspections
   - Drone footage videos
   - Before/after images
   - Stored in: `storage/app/public/site-visits/`

### Plant Request PDFs:
1. **RFQ PDFs** (Request for Quotation)
   - Generated quotation PDFs
   - Pricing proposals
   - Stored in: `storage/app/pdfs/`

2. **Inquiry PDFs** (User Inquiries)
   - Simple plant inquiry PDFs
   - Client request summaries
   - Stored in: `storage/app/pdfs/`

---

## 🔄 What Happens During Encryption

### Step-by-Step Process:
```
1. Scan database for file references
   ↓
2. Check if file exists on disk
   ↓
3. Check if already encrypted (skip if yes)
   ↓
4. Read original file contents
   ↓
5. Encrypt using AES-256-CBC
   ↓
6. Save to storage/app/encrypted/
   ↓
7. Create encrypted_files database record
   ↓
8. Update original database reference (point to encrypted file)
   ↓
9. Delete original unencrypted file
   ↓
10. Continue to next file
```

### Database Updates:
The command automatically updates these tables:
- `site_visits` → Updates paths in JSON fields
- `plant_requests` → Updates `pdf_path` column
- `encrypted_files` → Creates new tracking records

---

## ✅ After Encryption

### Verify Encryption Was Successful:
```bash
# Check encrypted files directory
ls -lah storage/app/encrypted/

# Check database records
php artisan tinker
App\Models\EncryptedFile::count()
```

### Old Files Should Be Gone:
```bash
# These should be empty or have very few files
ls storage/app/public/site-visits/
ls storage/app/pdfs/
```

### Test File Access:
1. Log in as a client
2. Go to "Client Data" or "My Requests"
3. Try downloading a file
4. File should download normally (decrypted automatically)

---

## 🚨 Important Warnings

### 1. **Backup First!**
```bash
# Backup encrypted files directory
tar -czf encrypted_files_backup.tar.gz storage/app/

# Backup database
mysqldump -u root -p your_database > database_backup.sql
```

### 2. **Run in Maintenance Mode** (Optional for large sites)
```bash
# Put site in maintenance mode
php artisan down

# Encrypt files
php artisan files:encrypt-existing

# Bring site back up
php artisan up
```

### 3. **Monitor Disk Space**
During encryption, you need space for:
- Original files (will be deleted after encryption)
- Encrypted files (slightly larger due to encryption overhead)
- Encrypted overhead: ~10-20% more space temporarily

### 4. **Large File Sets**
If you have 1000+ files:
- Consider running in batches
- Monitor memory usage
- Run during low-traffic hours

---

## 🔧 Troubleshooting

### "File not found" Errors
**Cause:** Database has reference but file doesn't exist on disk

**Solution:** 
- Check if files were moved/deleted manually
- Verify storage paths are correct
- Clean up orphaned database records

### "Encryption failed" Errors
**Cause:** Permission issues, disk space, or corrupted files

**Solution:**
```bash
# Check storage permissions
chmod -R 775 storage/

# Check disk space
df -h

# Check Laravel logs
tail -f storage/logs/laravel.log
```

### "Memory limit exceeded"
**Cause:** Encrypting very large files

**Solution:**
```bash
# Increase PHP memory limit temporarily
php -d memory_limit=512M artisan files:encrypt-existing --type=pdfs
```

---

## 📈 Performance Considerations

### Encryption Speed:
- Small files (<1MB): ~0.1 seconds per file
- Medium files (1-5MB): ~0.5 seconds per file
- Large files (>5MB): ~2-5 seconds per file

### Example Timings:
- 100 files (mixed sizes): ~2-5 minutes
- 500 files (mixed sizes): ~10-20 minutes
- 1000 files (mixed sizes): ~30-45 minutes

### Running in Background:
```bash
# Run in background with nohup
nohup php artisan files:encrypt-existing --force > encryption.log 2>&1 &

# Check progress
tail -f encryption.log

# Check if still running
ps aux | grep "files:encrypt-existing"
```

---

## 🎯 Recommended Workflow

### For Local/Staging:
```bash
# 1. Dry run first
php artisan files:encrypt-existing --dry-run

# 2. Backup everything
tar -czf backup_before_encryption.tar.gz storage/

# 3. Encrypt
php artisan files:encrypt-existing

# 4. Test downloads
# (manually test file access)

# 5. Verify
App\Models\EncryptedFile::count()
```

### For Production:
```bash
# 1. Backup production database and files
mysqldump -u user -p database > backup.sql
tar -czf storage_backup.tar.gz storage/

# 2. Put in maintenance mode (optional)
php artisan down

# 3. Dry run to estimate time
php artisan files:encrypt-existing --dry-run

# 4. Run encryption
php artisan files:encrypt-existing --force

# 5. Test critical paths
# (download test files)

# 6. Bring back online
php artisan up

# 7. Monitor logs
tail -f storage/logs/laravel.log
```

---

## 📝 FAQ

### Q: Will users notice any difference?
**A:** No. Files download exactly the same way. Decryption happens automatically and transparently.

### Q: Can I encrypt files gradually over time?
**A:** Yes! New files are encrypted automatically. You can run the command later for old files, or not encrypt them at all if they're not sensitive.

### Q: What if I change my APP_KEY?
**A:** ⚠️ **CRITICAL:** All encrypted files become unrecoverable. NEVER change APP_KEY after encryption.

### Q: Can I decrypt files back to normal?
**A:** Not directly. You'd need to download each file through the app (which decrypts it), then save it manually.

### Q: How much space do encrypted files take?
**A:** ~10-15% more than the original due to encryption overhead.

### Q: Are old unencrypted files still accessible?
**A:** Yes! The system handles both encrypted and unencrypted files automatically until you run the encryption command.

---

## 📞 Support

If you encounter issues:
1. Check `storage/logs/laravel.log`
2. Run with `--dry-run` to preview
3. Verify file permissions: `chmod -R 775 storage/`
4. Check disk space: `df -h`
5. Test with small batch first: `--type=pdfs`

---

**Last Updated:** June 6, 2026  
**Version:** 1.0
