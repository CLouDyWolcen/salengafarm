# File Encryption Testing Guide

## 🎯 Complete Step-by-Step Testing Instructions

This guide will help you test the file encryption system thoroughly and correctly.

---

## 📋 Pre-Testing Checklist

### 1. **Run the Migration**
```bash
# Local
php artisan migrate

# Production
ssh root@your-server
cd /var/www/salengafarm
php artisan migrate
```

**Expected Output:**
```
INFO  Running migrations.
  2026_06_06_000000_create_encrypted_files_table ....... 5.23ms DONE
```

### 2. **Verify Table Created**
```bash
php artisan tinker
App\Models\EncryptedFile::count()  # Should return 0 (no files yet)
exit
```

### 3. **Create encrypted Directory**
```bash
mkdir -p storage/app/encrypted
chmod 775 storage/app/encrypted
```

---

## 🧪 Test Plan Overview

We'll test these scenarios in order:
1. ✅ **NEW RFQ PDF Generation** (Admin creates quotation)
2. ✅ **NEW User Inquiry PDF** (Client submits inquiry)
3. ✅ **PDF Download** (Decrypt and download)
4. ✅ **PDF View in Browser** (Decrypt and display)
5. ✅ **Authorization** (Verify access control)
6. ✅ **Encrypt Existing Files** (Migrate old files)
7. ✅ **Audit Logs** (Verify logging)

---

## TEST 1: New RFQ PDF Generation 🔐

### Objective:
Verify that newly generated RFQ PDFs are automatically encrypted.

### Steps:

#### 1.1 Create a New Plant Request (as Admin)
```
1. Log in as Admin/Super Admin
2. Navigate to: Dashboard > Requests
3. Click "New Request" or use the request form
4. Fill in:
   - Email: test@example.com
   - Name: Test Client
   - Add some plants to the request
5. Click "Submit"
```

#### 1.2 Check Encrypted File Created
```bash
# On terminal
ls -lah storage/app/encrypted/

# Expected: You should see a file like:
# 20260606123456_abc123def456.enc
```

#### 1.3 Verify Database Record
```bash
php artisan tinker

# Check encrypted_files table
$file = App\Models\EncryptedFile::latest()->first();
$file->original_filename  # Should show "rfq_X_timestamp.pdf"
$file->encrypted_path     # Should show "encrypted/20260606_*.enc"
$file->file_type          # Should show "application/pdf"
$file->uploaded_by        # Should show 1 (system)
```

#### 1.4 Verify Old Location is Empty
```bash
ls storage/app/pdfs/

# Expected: File should NOT exist here (deleted after encryption)
# If you see files here, they're old unencrypted files
```

#### 1.5 Check Laravel Log
```bash
tail -30 storage/logs/laravel.log

# Look for:
# "PDF encrypted successfully"
# with request_id and encrypted_file_id
```

### ✅ Pass Criteria:
- [ ] Encrypted file exists in `storage/app/encrypted/`
- [ ] Database record in `encrypted_files` table
- [ ] Original PDF deleted from `storage/app/pdfs/`
- [ ] Log shows "PDF encrypted successfully"

---

## TEST 2: New User Inquiry PDF 🔐

### Objective:
Verify user inquiry PDFs are encrypted.

### Steps:

#### 2.1 Submit User Inquiry (as Client)
```
1. Log out from admin
2. Log in as a regular client
3. Navigate to: Plants > Request for Quotation
   OR: My Requests > New Inquiry
4. Fill in inquiry form:
   - Select some plants
   - Add contact details
5. Submit
```

#### 2.2 Verify Encryption
```bash
# Check for new encrypted file
ls -lah storage/app/encrypted/ | tail -1

# Check database
php artisan tinker
$inquiry = App\Models\EncryptedFile::where('original_filename', 'like', 'user_request_%')->latest()->first();
$inquiry->original_filename
```

### ✅ Pass Criteria:
- [ ] New encrypted file created
- [ ] Database record exists
- [ ] Filename starts with "user_request_"

---

## TEST 3: Download Encrypted PDF 📥

### Objective:
Verify encrypted PDFs can be downloaded and decrypted automatically.

### Steps:

#### 3.1 Download as Admin
```
1. Log in as Admin
2. Go to: Requests
3. Find the test request you created
4. Click "Download PDF" button
5. PDF should download normally
6. Open the downloaded PDF
```

**Expected Result:**
- ✅ PDF downloads with normal filename (RFQ_X.pdf)
- ✅ PDF opens correctly in PDF reader
- ✅ All content is readable (not encrypted gibberish)
- ✅ No errors in browser console

#### 3.2 Download as Client (Own Request)
```
1. Log in as the client (use email from request)
2. Go to: My Requests
3. Find your inquiry
4. Click "Download" or "View PDF"
5. PDF should download/display
```

**Expected Result:**
- ✅ Client can download their own request
- ✅ PDF decrypts and displays correctly

#### 3.3 Check Audit Log
```bash
php artisan tinker

# Check audit logs for file access
App\Models\AuditLog::where('action', 'like', 'File %')->latest()->first()

# Should show:
# action: "File Downloaded"
# entity_type: "EncryptedFile"
# user_email: (who downloaded)
```

### ✅ Pass Criteria:
- [ ] PDF downloads successfully
- [ ] PDF content is readable (decryption worked)
- [ ] Audit log records the download
- [ ] No errors in browser or Laravel log

---

## TEST 4: View PDF in Browser 👁️

### Objective:
Verify PDFs can be viewed inline in browser.

### Steps:

#### 4.1 View in Browser
```
1. Log in as Admin
2. Go to: Requests
3. Click "View PDF" (not Download)
4. PDF should open in new browser tab
```

**Expected Result:**
- ✅ PDF displays inline in browser
- ✅ Content is readable
- ✅ URL shows: `/requests/view-pdf/{id}`

#### 4.2 Check Network Tab
```
1. Open browser DevTools (F12)
2. Go to Network tab
3. Click "View PDF"
4. Look at the request
```

**Expected:**
- Response Headers should show: `Content-Type: application/pdf`
- File loads without errors

### ✅ Pass Criteria:
- [ ] PDF displays in browser
- [ ] No decryption errors
- [ ] Audit log shows "File Viewed"

---

## TEST 5: Authorization Testing 🔒

### Objective:
Verify users cannot access files they don't own.

### Steps:

#### 5.1 Try Unauthorized Access
```
1. Create a request as Admin (email: admin@test.com)
2. Log out
3. Log in as different client (email: client@test.com)
4. Try to access the admin's request PDF directly:
   URL: http://yoursite.com/requests/download-pdf/{id}
```

**Expected Result:**
- ❌ Access denied (403 Forbidden)
- ✅ Audit log shows failed access attempt
- ✅ Error message: "Unauthorized access to this PDF"

#### 5.2 Admin Access All Files
```
1. Log in as Admin/Super Admin
2. Try downloading any request's PDF
```

**Expected Result:**
- ✅ Admin can access ALL files
- ✅ No authorization errors

### ✅ Pass Criteria:
- [ ] Clients blocked from accessing other users' files
- [ ] Admins can access all files
- [ ] Authorization failures are logged

---

## TEST 6: Encrypt Existing Files 📦

### Objective:
Migrate old unencrypted files to encrypted storage.

### Steps:

#### 6.1 Check for Existing Files (Dry Run)
```bash
php artisan files:encrypt-existing --dry-run
```

**Expected Output:**
```
🔍 Scanning for unencrypted files...

+------------------------------+-------+
| File Type                    | Count |
+------------------------------+-------+
| Site Visit - Client Data     | X     |
| Site Visit - Proposals       | X     |
| Site Visit - Media Files     | X     |
| RFQ PDFs                     | X     |
| Inquiry PDFs                 | X     |
| TOTAL                        | X     |
+------------------------------+-------+

🔍 DRY RUN MODE - No files were encrypted
```

#### 6.2 Encrypt PDF Files Only (Test First)
```bash
php artisan files:encrypt-existing --type=pdfs
```

**Expected Output:**
```
🔐 Starting encryption...
[====================] 100%

✅ Successfully encrypted X file(s)
📍 Encrypted files location: storage/app/encrypted/
```

#### 6.3 Verify Encryption Worked
```bash
# Check encrypted directory
ls -lah storage/app/encrypted/ | wc -l

# Check database
php artisan tinker
App\Models\EncryptedFile::count()

# Check old PDFs are gone
ls storage/app/pdfs/  # Should be empty or only have old files
```

#### 6.4 Test Downloading Old File
```
1. Find a plant request that existed BEFORE encryption
2. Try downloading its PDF
3. Should still work (backward compatible)
```

### ✅ Pass Criteria:
- [ ] Command runs without errors
- [ ] Files moved to encrypted directory
- [ ] Database records created
- [ ] Old files still downloadable
- [ ] No data loss

---

## TEST 7: Audit Log Verification 📊

### Objective:
Verify all file access is properly logged.

### Steps:

#### 7.1 View Audit Trail
```
1. Log in as Super Admin
2. Click "Audit Trail" in Quick Actions
3. Look for file-related entries
```

**Expected Entries:**
- "File Downloaded: rfq_X.pdf"
- "File Viewed: user_request_Y.pdf"
- User email, timestamp, IP address

#### 7.2 Query Audit Logs
```bash
php artisan tinker

# Get all file access logs
$logs = App\Models\AuditLog::where('entity_type', 'EncryptedFile')->get();

# Check details
$logs->each(function($log) {
    echo $log->action . " by " . $log->user_email . " at " . $log->created_at . "\n";
});
```

#### 7.3 Export Audit Logs
```
1. In Audit Trail modal
2. Select date range
3. Click "Export CSV"
4. Open CSV file
5. Verify file access entries are present
```

### ✅ Pass Criteria:
- [ ] Every download is logged
- [ ] Every view is logged
- [ ] Logs include: user, file, timestamp, IP
- [ ] CSV export includes file access logs

---

## TEST 8: Edge Cases & Error Handling 🐛

### Objective:
Test error scenarios and edge cases.

### Steps:

#### 8.1 Try to Download Non-Existent File
```bash
# Go to URL directly
http://yoursite.com/requests/download-pdf/99999
```

**Expected:**
- 404 Not Found or proper error message

#### 8.2 Test with Corrupted Encrypted File
```bash
# Corrupt an encrypted file
echo "corrupted" > storage/app/encrypted/test_corrupted.enc

# Try accessing through system
```

**Expected:**
- Graceful error handling
- Error logged
- User sees friendly error message

#### 8.3 Test with Large PDF (>5MB)
```
1. Create request with many plants (large PDF)
2. Generate PDF
3. Download
```

**Expected:**
- Encryption still works
- Download may be slower but succeeds
- No memory errors

### ✅ Pass Criteria:
- [ ] Errors handled gracefully
- [ ] No system crashes
- [ ] Error messages are user-friendly
- [ ] Errors are logged for debugging

---

## 🔍 Verification Checklist

After all tests, verify:

### Files & Storage:
- [ ] Encrypted files in `storage/app/encrypted/`
- [ ] Old unencrypted files removed (after migration)
- [ ] File permissions correct (775)
- [ ] No orphaned files

### Database:
- [ ] `encrypted_files` table has records
- [ ] `plant_requests.pdf_path` points to encrypted files
- [ ] No broken references

### Functionality:
- [ ] New uploads are encrypted
- [ ] Downloads work (decrypt automatically)
- [ ] Viewing works (inline display)
- [ ] Authorization works (access control)
- [ ] Old files still work (backward compatible)

### Logs & Audit:
- [ ] Laravel log shows encryption success
- [ ] Audit log shows file access
- [ ] No error messages in logs

### Performance:
- [ ] Upload time acceptable
- [ ] Download time acceptable
- [ ] No memory issues
- [ ] Server load normal

---

## 🚨 Troubleshooting Guide

### Problem: "Failed to encrypt file"

**Check:**
```bash
# Permissions
chmod -R 775 storage/

# Disk space
df -h

# Laravel log
tail -50 storage/logs/laravel.log
```

### Problem: Download shows garbled text

**Cause:** Decryption failed

**Fix:**
```bash
# Check APP_KEY is unchanged
grep APP_KEY .env

# Check encrypted_files record exists
php artisan tinker
App\Models\EncryptedFile::where('encrypted_path', 'path/to/file')->first()
```

### Problem: "Unauthorized access"

**Cause:** User doesn't own the file

**Check:**
- User email matches request email
- Admin role is correct
- Check audit logs for access attempt

### Problem: Migration command hangs

**Cause:** Too many files or memory limit

**Fix:**
```bash
# Increase memory and run in batches
php -d memory_limit=512M artisan files:encrypt-existing --type=pdfs
php -d memory_limit=512M artisan files:encrypt-existing --type=site-visits
```

---

## 📊 Test Results Template

Use this to track your testing:

```
┌──────────────────────────────────────────────────┐
│          ENCRYPTION TESTING RESULTS              │
├──────────────────────────────────────────────────┤
│ Test 1: New RFQ PDF          [ ] Pass [ ] Fail  │
│ Test 2: New Inquiry PDF      [ ] Pass [ ] Fail  │
│ Test 3: Download PDF         [ ] Pass [ ] Fail  │
│ Test 4: View PDF             [ ] Pass [ ] Fail  │
│ Test 5: Authorization        [ ] Pass [ ] Fail  │
│ Test 6: Encrypt Existing     [ ] Pass [ ] Fail  │
│ Test 7: Audit Logs           [ ] Pass [ ] Fail  │
│ Test 8: Edge Cases           [ ] Pass [ ] Fail  │
├──────────────────────────────────────────────────┤
│ Overall:                     [ ] PASS [ ] FAIL   │
└──────────────────────────────────────────────────┘

Notes:
_________________________________________________
_________________________________________________
_________________________________________________
```

---

## 🎓 Testing Best Practices

1. **Test locally FIRST** - Never test encryption directly on production
2. **Backup before migrating** - Always backup before encrypting existing files
3. **Test in order** - Follow the test plan sequence
4. **Document issues** - Note any errors or unexpected behavior
5. **Verify audit logs** - Check logging after each test
6. **Test as different users** - Admin, client, unauthorized user
7. **Check browser console** - Look for JavaScript errors
8. **Monitor Laravel log** - Watch for PHP errors
9. **Test edge cases** - Large files, special characters, etc.
10. **Verify cleanup** - No orphaned files or database records

---

## 📞 Support

If tests fail:
1. Check `storage/logs/laravel.log`
2. Verify `APP_KEY` unchanged
3. Check file permissions
4. Review error messages
5. Contact system administrator

---

**Version:** 1.0  
**Last Updated:** June 6, 2026
