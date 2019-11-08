#start magento deployment
ln -s /var/www/html/pub/shared/env.php /var/www/html/app/etc/env.php && dep deploy:magento:cron
