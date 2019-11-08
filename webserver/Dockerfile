FROM php:7.2-fpm

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN apt-get update && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libicu-dev \
        libxml2 \
        libxml2-dev \
        libxslt1.1 \
        libxslt1-dev \
        g++ \
        nginx \
        wget \
        nano \
        git \
        unzip \ 
        libmemcached-dev \
        redis-tools \
        rsync \
        cron \
        procps \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-configure opcache --enable-opcache \
&& docker-php-ext-install opcache && docker-php-ext-install bcmath && docker-php-ext-install intl && docker-php-ext-install soap && docker-php-ext-install xsl

RUN pecl install zip-1.15.4 \
    && pecl install redis-4.0.1 \
    && pecl install memcached-3.1.3 \
    && docker-php-ext-enable zip redis memcached

RUN docker-php-ext-install pdo pdo_mysql

# Use the default production configuration + set config voor opcache and apcu
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
  { \
      echo 'zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20170718/opcache.so'; \
      echo 'opcache.memory_consumption=256'; \
      echo 'opcache.interned_strings_buffer=8'; \
      echo 'opcache.max_accelerated_files=8000'; \
      echo 'opcache.revalidate_freq=86400'; \
      echo 'opcache.fast_shutdown=1'; \
      echo 'opcache.enable_cli=1'; \
      echo 'opcache.enable=1'; \
  } > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_input_vars = 10000M' >> /usr/local/etc/php/conf.d/docker-php-maxinputvars.ini;
RUN echo 'session.gc_maxlifetime = 86400' >> /usr/local/etc/php/conf.d/docker-php-sessionlifetime.ini;

RUN composer global require hirak/prestissimo

RUN wget https://files.magerun.net/n98-magerun2.phar && chmod +x n98-magerun2.phar && mv n98-magerun2.phar /usr/local/bin/n98magerun2

RUN curl -LO https://deployer.org/deployer.phar && mv deployer.phar /usr/local/bin/dep && chmod +x /usr/local/bin/dep

RUN usermod -s /bin/bash www-data
RUN chown -Rf www-data:www-data /var/www

USER root
RUN touch /run/nginx.pid && chown -R www-data:www-data /var/lib/nginx && chown -R www-data:www-data /run/nginx.pid
COPY site.conf /etc/nginx/conf.d/default.conf
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
RUN rm -rf /etc/nginx/sites-enabled/default

COPY start.sh /start.sh
RUN chmod +x /start.sh
EXPOSE 8888
CMD ["/start.sh"]
USER root