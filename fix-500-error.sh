#!/bin/bash
# Salenga Farm - 500 Error Fix Script
# Run this on your DigitalOcean server

echo "=========================================="
echo "Salenga Farm - 500 Error Troubleshooting"
echo "=========================================="
echo ""

# Navigate to project directory
cd /var/www/salengafarm

echo "1. Checking Laravel logs..."
echo "=========================================="
tail -50 /var/www/salengafarm/storage/logs/laravel.log
echo ""

echo "2. Checking PHP-FPM logs..."
echo "=========================================="
tail -50 /var/log/php8.3-fpm.log
echo ""

echo "3. Checking Nginx error logs..."
echo "=========================================="
tail -50 /var/log/nginx/error.log
echo ""

echo "4. Pulling latest changes from GitHub..."
echo "=========================================="
git pull origin main
echo ""

echo "5. Installing/updating Composer dependencies..."
echo "=========================================="
composer install --optimize-autoloader --no-dev
echo ""

echo "6. Setting proper permissions..."
echo "=========================================="
chown -R www-data:www-data /var/www/salengafarm
chmod -R 755 /var/www/salengafarm
chmod -R 775 /var/www/salengafarm/storage
chmod -R 775 /var/www/salengafarm/bootstrap/cache
echo "Permissions set!"
echo ""

echo "7. Clearing all caches..."
echo "=========================================="
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
echo "Caches cleared!"
echo ""

echo "8. Rebuilding caches..."
echo "=========================================="
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Caches rebuilt!"
echo ""

echo "9. Restarting services..."
echo "=========================================="
systemctl restart php8.3-fpm
systemctl restart nginx
echo "Services restarted!"
echo ""

echo "=========================================="
echo "Fix script completed!"
echo "=========================================="
echo ""
echo "Please check your website now: https://salengafarm.page"
echo ""
echo "If still getting 500 error, check the logs above for specific errors."
echo "Look for lines with 'ERROR' or 'CRITICAL' or 'Fatal error'"
echo ""
