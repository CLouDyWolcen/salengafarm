# File Encryption System Guide

## 📁 Where Encrypted Files Are Stored

### Storage Location:
```
storage/
└── app/
    ├── encrypted/           ← 🔒 ALL ENCRYPTED FILES HERE
    │   ├── 20260606120000_abc123def456.enc
    │   ├── 20260606120100_def789ghi012.enc
    │   └── ...
    │
    ├── public/              ← ⚠️ OLD unencrypted files (legacy)
    │   ├── site-visits/     (will be empty after encryption)
    │   └── pdfs/            (will be empty after encryption)
    │
    └── logs/
```

### Important Notes:
- **Encrypted files** are stored in `storage/app/encrypted/`
- **This directory is NOT publicly accessible** (secured by Laravel)
- Files have `.enc` extension and random names
- Original filenames are stored in database (`encrypted_files` table)

---

## 🗄️ Database Tracking

### Table: `encrypted_files`

Every encrypted file has a record in this table:

| Column | Description | Example |
|--------|-------------|---------|
| `id` | Unique file ID | 123 |
| `original_path` | Path before encryption | `site-visits/document.pdf` |
| `encrypted_path` | Path to encrypted file | `encrypted/20260606120000_abc123.enc` |
| `original_filename` | Original file name | `Client_Contract.pdf` |
| `file_type` | MIME type | `application/pdf` |
| `file_size` | Size in bytes | 524288 |
| `uploaded_by` | User ID who uploaded | 5 |
| `encryption_algorithm` | Encryption method | `AES-256-CBC` |
| `created_at` | Upload timestamp | `2026-06-06 12:00:00` |

---

## 🔐 What Files Are Encrypted?

### ✅ ENCRYPTED (High Security):
1. **Site Visit Documents**
   - Client data uploads (PDFs, images)
   - Drone maps and videos
   - Site inspection photos
   - Proposal documents
   - **Original Location:** `storage/app/public/site-visits/`
   - **Encrypted Location:** `storage/app/encrypted/`

2. **RFQ PDFs (Request for Quotation)**
   - Generated quotation PDFs
   - Pricing proposals
   - **Original Location:** `storage/app/pdfs/`
   - **Encrypted Location:** `storage/app/encrypted/`

3. **User Inquiry PDFs**
   - Simple plant inquiry PDFs
   - Client request documents
   - **Original Location:** `storage/app/pdfs/`
   - **Encrypted Location:** `storage/app/encrypted/`

### ❌ NOT ENCRYPTED (Public):
- Plant photos (`storage/app/public/plant-photos/`) - Public catalog
- User avatars (`storage/app/public/avatars/`) - Profile pictures

---

## 🔄 How Encryption Works

### Upload Process:
```
1. User uploads file (e.g., "Contract.pdf")
   ↓
2. File temporarily stored in original location
   ↓
3. EncryptionService::encryptFile() called
   ↓
4. File contents encrypted using AES-256-CBC
   ↓
5. Encrypted file saved to storage/app/encrypted/
   ↓
6. Database record created in encrypted_files table
   ↓
7. Original unencrypted file DELETED for security
   ↓
8. User sees: "File uploaded successfully" ✅
```

### Download/View Process:
```
1. User clicks "Download" or "View"
   ↓
2. Request goes to SecureFileController
   ↓
3. Authorization check (Can user access this file?)
   ↓
4. Read encrypted file from storage/app/encrypted/
   ↓
5. Decrypt contents in memory (AES-256-CBC)
   ↓
6. Stream decrypted content to browser
   ↓
7. Audit log: "File Downloaded: Contract.pdf"
   ↓
8. User downloads/views normal PDF ✅
```

---

## 🛡️ Security Features

### 1. **Encryption Algorithm:**
- **AES-256-CBC** (Advanced Encryption Standard, 256-bit key)
- Industry-standard encryption
- Same as used by banks and government

### 2. **Access Control:**
- **Admins/Super Admins:** Can access all files
- **Clients:** Can only access their own site visit files
- **Users:** Can only access their own RFQ/inquiry files
- **Unauthorized access:** Blocked with 403 error + audit log

### 3. **Audit Logging:**
Every file access is logged:
- Who accessed the file (user ID, email)
- When (timestamp)
- What action (Downloaded/Viewed)
- File name
- IP address
- User agent

### 4. **No Temporary Files:**
- Files decrypted **in memory only**
- No unencrypted copies on disk
- Streamed directly to browser
- Memory cleared after download

---

## 🔍 How to Find Encrypted Files

### Method 1: Database Query
```sql
SELECT 
    id,
    original_filename,
    encrypted_path,
    file_size,
    uploaded_by,
    created_at
FROM encrypted_files
WHERE original_filename LIKE '%contract%';
```

### Method 2: Laravel Tinker
```bash
php artisan tinker

# Find all encrypted files
App\Models\EncryptedFile::all();

# Find by uploader
App\Models\EncryptedFile::where('uploaded_by', 5)->get();

# Find by filename
App\Models\EncryptedFile::where('original_filename', 'like', '%contract%')->get();
```

### Method 3: Physical File System
```bash
# List all encrypted files
ls -lah storage/app/encrypted/

# Count encrypted files
ls storage/app/encrypted/ | wc -l

# Check disk usage
du -sh storage/app/encrypted/
```

---

## 🔗 Download URLs

### Secure Download Routes:
```
GET /secure-file/download/{id}  - Download encrypted file
GET /secure-file/view/{id}      - View encrypted file in browser
```

### Example:
```html
<!-- Download link -->
<a href="{{ route('secure-file.download', $encryptedFileId) }}">
    Download Document
</a>

<!-- View in browser link -->
<a href="{{ route('secure-file.view', $encryptedFileId) }}" target="_blank">
    View Document
</a>
```

---

## 🚨 Troubleshooting

### Problem: "Failed to decrypt file"
**Causes:**
1. Encryption key changed (APP_KEY in .env)
2. Encrypted file corrupted
3. Database record doesn't match file

**Solution:**
- Check APP_KEY is unchanged
- Verify file exists: `ls storage/app/encrypted/`
- Check database record matches

### Problem: "Unauthorized access"
**Causes:**
1. User doesn't own the file
2. File not linked to user's site visit/request

**Solution:**
- Check user_id in request
- Verify file ownership in database
- Check audit logs for access attempts

### Problem: Encrypted files taking too much space
**Solution:**
```bash
# Check encrypted storage size
du -sh storage/app/encrypted/

# Clean up old encrypted files (90+ days)
php artisan encrypted-files:cleanup --days=90
```

---

## 🔧 Admin Tools

### View All Encrypted Files:
```bash
php artisan tinker

# List all with details
App\Models\EncryptedFile::with('uploader')
    ->orderBy('created_at', 'desc')
    ->get()
    ->map(function($file) {
        return [
            'id' => $file->id,
            'filename' => $file->original_filename,
            'size' => $file->file_size_human,
            'uploader' => $file->uploader->email ?? 'Unknown',
            'uploaded' => $file->created_at->diffForHumans(),
        ];
    });
```

### Manually Decrypt a File:
```bash
php artisan tinker

$service = new App\Services\EncryptionService();
$encryptedFile = App\Models\EncryptedFile::find(123);
$response = $service->viewDecryptedFile($encryptedFile->id);
```

---

## 📊 Monitoring

### Check Encryption Status:
```bash
# Count encrypted files
php artisan tinker
App\Models\EncryptedFile::count();

# Check recent uploads
App\Models\EncryptedFile::where('created_at', '>', now()->subDays(7))->count();

# Check by file type
App\Models\EncryptedFile::where('file_type', 'application/pdf')->count();
```

### Audit Log Review:
```sql
SELECT 
    action,
    entity_id,
    new_values->>'$.filename' as filename,
    user_email,
    created_at
FROM audit_logs
WHERE action LIKE 'File %'
ORDER BY created_at DESC
LIMIT 50;
```

---

## ⚙️ Configuration

### Environment Variables (.env):
```bash
# Application key (used for encryption)
APP_KEY=base64:YourLaravel AppKeyHere

# File encryption (optional - uses APP_KEY by default)
FILE_ENCRYPTION_ENABLED=true
FILE_ENCRYPTION_ALGORITHM=AES-256-CBC
```

⚠️ **WARNING:** Never change APP_KEY after files are encrypted! All encrypted files will become unreadable.

---

## 📝 Backup Recommendations

### What to Backup:
1. **Encrypted files directory:**
   ```bash
   tar -czf encrypted_files_backup.tar.gz storage/app/encrypted/
   ```

2. **encrypted_files table:**
   ```bash
   mysqldump -u root -p your_database encrypted_files > encrypted_files_backup.sql
   ```

3. **APP_KEY (.env file):**
   ```bash
   # CRITICAL: Store securely, never commit to Git
   cp .env .env.backup
   ```

### Backup Schedule:
- **Daily:** Encrypted files directory
- **Daily:** Database (including encrypted_files table)
- **Immediately:** After any APP_KEY change (before encryption)

---

## 🆘 Emergency Recovery

### If APP_KEY is Lost:
**BAD NEWS:** All encrypted files are **permanently unrecoverable**. There is no way to decrypt them without the original key.

**Prevention:**
- ✅ Backup .env file securely
- ✅ Store APP_KEY in password manager
- ✅ Document key in secure vault
- ❌ Never commit .env to Git

### If Encrypted Files Directory is Deleted:
**Solution:** Restore from backup

```bash
# Restore encrypted files
tar -xzf encrypted_files_backup.tar.gz -C /

# Restore database table
mysql -u root -p your_database < encrypted_files_backup.sql
```

---

## 📞 Support

For issues with file encryption:
1. Check audit logs: `audit_logs` table
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify APP_KEY unchanged
4. Test with new file upload
5. Contact system administrator

---

**Last Updated:** June 6, 2026  
**Version:** 1.0
