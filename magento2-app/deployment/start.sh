#symlink env.php
ln -s /var/www/html/pub/shared/env.php /var/www/html/app/etc/env.php

#start web+php
/usr/sbin/nginx
/usr/local/sbin/php-fpm
/usr/sbin/cron
