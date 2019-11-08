FROM cloudlets/m2-web-php AS builder
COPY deployment/auth.json /var/www/.composer/auth.json
COPY deployment/auth.json /root/.composer/auth.json
COPY --chown=www-data:www-data deployment/auth.json /var/www/html/var/composer_home/auth.json
USER root
COPY . /var/www/html
WORKDIR /var/www/html/
COPY --chown=www-data:www-data deployment/auth.json /var/www/html/var/composer_home/auth.json
RUN composer install

FROM cloudlets/m2-web-php
USER root
COPY --chown=www-data:www-data --from=builder /var/www/html/ /var/www/html/

COPY --chown=www-data:www-data deployment/init.sh /var/www/init.sh
COPY --chown=www-data:www-data deployment/start.sh /var/www/start.sh
COPY --chown=www-data:www-data deployment/start-cron.sh /var/www/start-cron.sh
COPY --chown=www-data:www-data deployment/init-cron.sh /var/www/init-cron.sh
RUN chmod +x /var/www/start.sh
RUN chown www-data:www-data /var/www/html/pub

CMD ["/var/www/start.sh"]
USER www-data
