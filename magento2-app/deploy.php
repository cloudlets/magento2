<?php
namespace Deployer;

desc('Create env.php');
task('magento:create:env', function () {

    $envFileContent = file_get_contents('config/env_template.php');

    $envVariables = [
        'BACKEND_FRONTNAME',
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASS',
        'CRYPT_KEY',
        'DB_PREFIX',
        'REDIS_HOST',
        'CACHE_DB',
        'REDIS_PORT',
        'PAGE_CACHE_DB',
        'SESS_DB',
        'VARNISH_HOST',
    ];

    $defaults = [
        'BACKEND_FRONTNAME' => 'beheer',
        'DB_HOST'           => 'Please define me in Gitlab CI Secret Variables',
        'DB_NAME'           => 'Please define me in Gitlab CI Secret Variables',
        'DB_USER'           => 'Please define me in Gitlab CI Secret Variables',
        'CRYPT_KEY'         => 'Please define me in Gitlab CI Secret Variables',
        'DB_PASS'           => 'set in config',
        'DB_PREFIX'         => '',
        'REDIS_HOST'        => 'set in configmap',
        'REDIS_PORT'        => 'set in configmap',
        'CACHE_DB'          => '0',
        'PAGE_CACHE_DB'     => '1',
        'SESS_DB'           => '2',
        'VARNISH_HOST'      => '10.43.187.246',

    ];

    foreach ($envVariables as $envVariable) {
        $envFileContent = str_replace('{% raw %}{{' . $envVariable . '}}{% endraw %}', (empty(getenv($envVariable)) ? $defaults[$envVariable] : getenv($envVariable)), $envFileContent);
    }

    $envFilename = 'env.php';
    file_put_contents($envFilename, $envFileContent);
    upload($envFilename, '/var/www/html/pub/shared/env.php');
    unlink($envFilename);
});


desc('Compile magento di');
task('magento:compile', function () {
    run("php /var/www/html/bin/magento setup:di:compile");
});

desc('Deploy assets');
task('magento:deploy:assets', function () {
    run("php /var/www/html/bin/magento setup:static-content:deploy");
});

desc('Enable maintenance mode');
task('magento:maintenance:enable', function () {
    run("php /var/www/html/bin/magento maintenance:enable");
});

desc('Upgrade magento database');
task('magento:upgrade:db', function () {
    run("php /var/www/html/bin/magento setup:upgrade");
});

desc('Disable maintenance mode');
task('magento:maintenance:disable', function () {
    run("php /var/www/html/bin/magento maintenance:disable"); 
});

desc('Flush Magento Cache');
task('magento:cache:flush', function () {
    run("php /var/www/html/bin/magento cache:clean");
});

desc('Fix Magento2 File Permissions');
task('magento:fix:permissions', function () {
    run("find /var/www/html/ -type f -exec chmod 644 {} \; ");
    run("find /var/www/html/ -type d -exec chmod 755 {} \; ");
    run("find /var/www/html/var -type d -exec chmod 777 {} \; ");
    run("find /var/www/html/pub/media -type d -exec chmod 777 {} \;");
    run("find /var/www/html/pub/static -type d -exec chmod 777 {} \;");
    run("chmod 777 /var/www/html/app/etc");
    run("chmod 644 /var/www/html/app/etc/*.xml");
    run("rm -rf /var/www/html/var/*");
    run("chgrp www-data /var/www/html/var/");
    run("chmod g+s /var/www/html/var/");     
    run("chmod 777 /var/www/html/var/");
});

desc('Deploy Cron');
task('magento:cron:deploy', function () {
    run("crontab /etc/cron.d/magento_cron");
});

desc('Magento2 deployment operations');
task('deploy:magento', [
    'magento:create:env',
    'magento:maintenance:enable',
    'magento:upgrade:db',
    'magento:maintenance:disable',
    'magento:compile',
    'magento:deploy:assets',
    'magento:cache:flush',
    'magento:fix:permissions'
    
]);

desc('Magento2 deployment operations tbv cron');
task('deploy:magento:cron', [
    'magento:create:env',
    'magento:compile'
]);
