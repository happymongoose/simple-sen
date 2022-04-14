(crontab -l 2>/dev/null; echo "0 * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1") | crontab -
