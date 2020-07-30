FROM php:7.4-apache-buster

RUN pecl install xdebug

RUN apt-get update && apt-get install -y zlib1g-dev git libpq-dev libzip-dev unzip

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

ADD virtual_host.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www/html