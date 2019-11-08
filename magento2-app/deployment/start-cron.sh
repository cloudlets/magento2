#symlink env.php
ln -s /var/www/html/pub/shared/env.php /var/www/html/app/etc/env.php

#install cronjob
dep magento:cron:deploy

#start cron
/usr/sbin/cron -f
