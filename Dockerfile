FROM php:7.3-apache-buster

RUN apt-get update && apt-get install -y zlib1g-dev git libpq-dev libzip-dev unzip

ADD docker/virtual_host.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN a2enmod expires

# opcache
RUN docker-php-ext-install opcache
ADD docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

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

RUN composer install --no-dev --optimize-autoloader