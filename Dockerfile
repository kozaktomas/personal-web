FROM php:8.2-fpm-bookworm

RUN apt-get update && apt-get install -y zlib1g-dev git libpq-dev libzip-dev unzip libicu-dev

# opcache
RUN docker-php-ext-install opcache
ADD docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# FPM settings
ADD docker/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# intl
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html

ADD Dockerfile /Dockerfile
ADD . /var/www/html

RUN mkdir -p /var/www/html/temp/cache \
    && mkdir -p /var/www/html/temp/sessions \
    && mkdir -p /var/www/html/temp/data \
    && mkdir -p /var/www/html/log \
    && chmod -R 777 /var/www/html/temp \
    && chmod -R 777 /var/www/html/log

RUN composer install --no-dev --optimize-autoloader && php -v