# Setting Up esthers.salengafarm.page Subdomain

## Overview
This guide will help you set up `esthers.salengafarm.page` as a subdomain pointing to Esther's Flower Garden content on the same server.

---

## Step 1: Configure DNS (Name.com)

1. **Log in to Name.com**
2. **Go to your domain**: salengafarm.page
3. **Click "DNS Records"**
4. **Add a new A Record:**
   - **Type**: A
   - **Host**: `esthers`
   - **Answer**: `165.245.182.243` (your DigitalOcean IP)
   - **TTL**: 300 (5 minutes)
5. **Save the record**

**DNS will take 5-30 minutes to propagate.**

---

## Step 2: Update Nginx Configuration on Server

SSH into your server:
```bash
ssh root@165.245.182.243
```

Edit the Nginx configuration:
```bash
nano /etc/nginx/sites-available/salengafarm
```

Update the `server_name` line to include the subdomain:
```nginx
server_name salengafarm.page www.salengafarm.page esthers.salengafarm.page;
```

**Full configuration should look like:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name salengafarm.page www.salengafarm.page esthers.salengafarm.page;
    
    root /var/www/salengafarm/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Test and reload Nginx:
```bash
nginx -t
systemctl reload nginx
```

---

## Step 3: Get SSL Certificate for Subdomain

Run Certbot to add SSL for the subdomain:
```bash
certbot --nginx -d salengafarm.page -d www.salengafarm.page -d esthers.salengafarm.page
```

Follow the prompts:
- Select option to expand the existing certificate
- Choose redirect HTTP to HTTPS (option 2)

---

## Step 4: Deploy Code Changes

The code changes have already been made locally. Deploy them:

```bash
# On local machine
cd C:\CODING\my_Inventory
git add -A
git commit -m "Add support for esthers.salengafarm.page subdomain"
git push origin main

# On server
cd /var/www/salengafarm
git pull origin main
php artisan view:clear
php artisan cache:clear
systemctl restart nginx
```

---

## Step 5: Test the Subdomain

After DNS propagates (5-30 minutes), test:

1. **Visit**: https://esthers.salengafarm.page
   - Should show Esther's Flower Garden content
   
2. **Visit**: https://salengafarm.page
   - Should show Salenga Farm content

---

## Local Testing (Before DNS Setup)

1. **Run the add-hosts.bat** (as Administrator)
2. **Start Laravel server**: `php artisan serve`
3. **Test URLs:**
   - http://salengafarm.local:8000 (Salenga Farm)
   - http://esthers.local:8000 (Esther's Flower Garden)

---

## Troubleshooting

### DNS not working
- Wait 30 minutes for propagation
- Check DNS with: `nslookup esthers.salengafarm.page`
- Should return: `165.245.182.243`

### Wrong content showing
- Clear browser cache (Ctrl+Shift+Delete)
- Clear Laravel cache: `php artisan cache:clear`
- Check BrandHelper detection logic

### SSL certificate error
- Re-run certbot: `certbot --nginx -d salengafarm.page -d www.salengafarm.page -d esthers.salengafarm.page`
- Check certificate: `certbot certificates`

---

## Summary

**URLs:**
- Main site: https://salengafarm.page (Salenga Farm)
- Subdomain: https://esthers.salengafarm.page (Esther's Flower Garden)

**Both sites:**
- Run on the same server
- Use the same codebase
- Automatically detect which brand to show based on domain
- Share the same database

**Cost:** FREE (uses existing domain and server)
