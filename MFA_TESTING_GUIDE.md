# MFA (Multi-Factor Authentication) Testing Guide

## ✅ CRITICAL FIXES APPLIED

### 1. ✅ 403 Unauthorized Error - FIXED
**Problem**: After entering valid MFA code, users got "403 | THIS ACTION IS UNAUTHORIZED" error.

**Root Cause**: MFA verification redirected ALL users to `/dashboard` which requires admin access (`can:access-admin` gate). Non-admin users (clients) don't have permission → 403 error.

**Solution**: Updated `MfaController` to redirect based on user role:
- **Admin/Manager users** → `/dashboard` (admin dashboard)
- **Client/Customer users** → `/client-dashboard` (user dashboard)

### 2. ✅ Design Mismatch - FIXED
**Problem**: MFA verification page had **purple gradient background**, didn't match login/register page design.

**Solution**: Updated `resources/views/auth/mfa/verify.blade.php`:
- ✅ Same blurred plant image background (`salengap.png` with blur effect)
- ✅ Same white card styling with rounded corners
- ✅ Same Poppins fonts and spacing
- ✅ Black verify button (matches login button style)
- ✅ Mobile responsive design

---

## 🎯 Priority Tests (Start Here!)

### ⭐ TEST 1: Verify 403 Error is Fixed
**This is the most critical test!**

1. **Enable MFA for your account**:
   ```sql
   UPDATE users 
   SET mfa_enabled = 1, mfa_enabled_at = NOW() 
   WHERE email = 'your-email@example.com';
   ```

2. **Logout** if currently logged in

3. **Login** with your credentials

4. **Check email** for 6-digit code

5. **Enter the code** on verification page

6. **Expected Result**:
   - ✅ NO 403 error!
   - ✅ Redirect to correct dashboard based on role:
     - Admin → `/dashboard`
     - Client → `/client-dashboard`
   - ✅ Success message: "Verification successful! Welcome back."

**If you still see 403**: Hard refresh browser (Ctrl+F5), clear Laravel cache

---

### ⭐ TEST 2: Verify Design is Fixed
**Check the visual design matches login page**

1. Follow Test 1 steps 1-3 to reach MFA verification page

2. **Visual Checklist**:
   - ✅ Background: Blurred plant image (NOT purple gradient!)
   - ✅ Card: White with rounded corners
   - ✅ Header: Green gradient with shield icon
   - ✅ Code input: Large with letter spacing
   - ✅ Button: Black "Verify & Continue" (NOT green gradient!)
   - ✅ Links: Blue "Resend Code"
   - ✅ Fonts: Poppins (same as login)

3. **Mobile Test**: Resize browser to 375px width
   - ✅ Card should be 90% width
   - ✅ Text should be readable
   - ✅ Button should fit properly

**If you still see purple**: Hard refresh (Ctrl+F5), clear browser cache

---

## 📋 Complete Test Suite

### Test 3: Role-Based Redirect (Admin)
1. Enable MFA for admin user (`role` = 'super_admin' or 'admin')
2. Login and verify MFA
3. **Expected**: Redirect to `/dashboard` (admin view with plants, analytics)

### Test 4: Role-Based Redirect (Client)
1. Enable MFA for client user (`role` = 'client' or 'partner')
2. Login and verify MFA
3. **Expected**: Redirect to `/client-dashboard` (client view)

### Test 5: Invalid Code
1. Login → MFA page
2. Enter wrong code: `000000`
3. **Expected**: Error "Invalid verification code"

### Test 6: Account Lockout
1. Login → MFA page
2. Enter 5 wrong codes
3. **Expected**: Lockout message, 15-minute wait time

### Test 7: Resend Code
1. Login → MFA page
2. Click "Resend Code"
3. **Expected**: New email, old code invalid
4. Try 3+ resends → rate limit message

### Test 8: Code Expiration
1. Login → MFA page
2. Wait 6 minutes
3. Enter original code
4. **Expected**: Invalid (codes expire after 5 minutes)

### Test 9: Session Persistence
1. Complete MFA verification
2. Navigate around the site
3. **Expected**: No MFA prompt for 2 hours

### Test 10: Logout & Re-login
1. Complete MFA → Logout
2. Login again
3. **Expected**: MFA required again

---

## 🛠️ How to Enable MFA

### Method 1: Direct SQL
```sql
UPDATE users 
SET mfa_enabled = 1, mfa_enabled_at = NOW() 
WHERE email = 'your-email@example.com';
```

### Method 2: Tinker Console
```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'test@example.com')->first();
>>> $user->update(['mfa_enabled' => true, 'mfa_enabled_at' => now()]);
>>> exit
```

### Method 3: phpMyAdmin
1. Open `users` table
2. Find your user
3. Set `mfa_enabled` = 1
4. Save

---

## 🔍 Troubleshooting

### ⚠️ Still Getting 403 Error?

**Step 1: Clear All Caches**
```bash
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
```

**Step 2: Check User Role**
```sql
SELECT id, email, role, mfa_enabled FROM users WHERE email = 'your-email@example.com';
```

**Step 3: Verify User Model Method**
```bash
php artisan tinker
>>> $user = \App\Models\User::find(1);
>>> $user->hasAdminAccess(); // Should return true/false
>>> $user->hasClientAccess(); // Should return true/false
```

**Step 4: Check Laravel Logs**
```bash
tail -50 storage/logs/laravel.log
```

### ⚠️ Still Seeing Purple Background?

**Step 1: Hard Refresh Browser**
- Windows: `Ctrl + F5`
- Mac: `Cmd + Shift + R`

**Step 2: Clear Browser Cache**
- Chrome: Settings → Privacy → Clear browsing data
- Check image exists: `public/images/salengap.png`

**Step 3: Verify File Updated**
```bash
# Check the verify.blade.php file contains:
grep "salengap.png" resources/views/auth/mfa/verify.blade.php
grep "blur(8px)" resources/views/auth/mfa/verify.blade.php
```

### ⚠️ Email Not Received?

**Check Brevo Credentials** (`.env`):
```
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-email
MAIL_PASSWORD=your-brevo-smtp-key
MAIL_ENCRYPTION=tls
```

**Test Email Manually**:
```bash
php artisan tinker
>>> $service = new \App\Services\BrevoEmailService();
>>> $service->sendMfaCode('your-email@example.com', '123456', 'Test');
```

---

## 📊 Database Monitoring

### Check MFA-Enabled Users
```sql
SELECT id, email, first_name, last_name, role, mfa_enabled, mfa_enabled_at
FROM users 
WHERE mfa_enabled = 1;
```

### Check Recent MFA Attempts
```sql
SELECT u.email, u.role, ma.success, ma.ip_address, ma.attempted_at
FROM mfa_attempts ma
JOIN users u ON ma.user_id = u.id
ORDER BY ma.attempted_at DESC
LIMIT 20;
```

### Check Failed Attempts (Last Hour)
```sql
SELECT u.email, COUNT(*) as failed_count
FROM mfa_attempts ma
JOIN users u ON ma.user_id = u.id
WHERE ma.success = 0 
  AND ma.attempted_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
GROUP BY u.email;
```

### Disable MFA for User
```sql
UPDATE users 
SET mfa_enabled = 0, mfa_enabled_at = NULL 
WHERE email = 'user@example.com';
```

---

## 🔒 Security Features

### Lockout Protection
- **Trigger**: 5 failed attempts within 15 minutes
- **Duration**: 15 minutes
- **Tracking**: Per user (cached)

### Rate Limiting
- **Resend Limit**: 3 times per 15 minutes
- **Code Validity**: 5 minutes
- **Session Timeout**: 2 hours

### Logging
All events logged to `storage/logs/laravel.log`:
- Code generation
- Successful verifications
- Failed attempts
- Lockout events

---

## ⚙️ Configuration

Edit `config/mfa.php`:
```php
return [
    'enabled' => true,
    'code_length' => 6,
    'code_expiry_minutes' => 5,
    'max_attempts' => 5,
    'lockout_minutes' => 15,
    'session_timeout' => 120, // 2 hours
];
```

---

## 📝 Implementation Summary

### Files Modified/Created:
- ✅ `app/Models/MfaAttempt.php` - Track attempts
- ✅ `app/Models/User.php` - MFA fields & methods
- ✅ `app/Services/MfaService.php` - Core logic
- ✅ `app/Services/BrevoEmailService.php` - Email sending
- ✅ `app/Http/Controllers/Auth/MfaController.php` - **Fixed redirects**
- ✅ `app/Http/Middleware/RequireMfaVerification.php` - Check verification
- ✅ `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Send code on login
- ✅ `resources/views/auth/mfa/verify.blade.php` - **Fixed design**
- ✅ `resources/views/emails/mfa-code.blade.php` - Email template
- ✅ `config/mfa.php` - Configuration
- ✅ `routes/auth.php` - MFA routes
- ✅ `bootstrap/app.php` - Middleware registration

---

## ✅ Ready to Test!

**Start with Priority Tests 1 & 2** to verify the critical fixes:
1. ✅ No more 403 errors (role-based redirects working)
2. ✅ Design matches login page (blurred background)

Then proceed with the complete test suite (Tests 3-10).

---

## 🚀 Deployment to DigitalOcean

**DO NOT COMMIT UNTIL:**
- [ ] All tests pass locally
- [ ] No 403 errors
- [ ] Design looks correct
- [ ] Email sending works
- [ ] No errors in logs

**When Ready:**
```bash
# Commit changes
git add .
git commit -m "feat: Add email-based MFA with role-based redirects and matching UI design"
git push origin main

# Deploy to DigitalOcean
cd /var/www/salengafarm
git pull origin main
php artisan migrate --force
php artisan view:clear
php artisan cache:clear
php artisan config:cache
systemctl restart nginx
```

---

## 📞 Support

If issues persist after all fixes:
1. Check `storage/logs/laravel.log`
2. Check browser console (F12)
3. Check network tab for 403/500 errors
4. Verify user role in database
5. Test with different user accounts (admin vs client)

**Common Issues & Solutions:**
- 403 Error → Clear cache, verify role-based redirects working
- Purple background → Hard refresh browser, verify file updated
- No email → Check Brevo credentials, test manually
- Infinite redirect → Check middleware excludes MFA routes
