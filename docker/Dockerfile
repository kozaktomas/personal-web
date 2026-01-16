FROM php:8.5-fpm-bookworm

RUN apt-get update && apt-get install -y unzip libicu-dev

# xdebug
#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug

# intl
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html

RUN mkdir -p /var/www/html/temp/cache \
    && mkdir -p /var/www/html/temp/sessions \
    && mkdir -p /var/www/html/temp/data \
    && mkdir -p /var/www/html/log \
    && chmod -R 777 /var/www/html/temp \
    && chmod -R 777 /var/www/html/log \
    && apt-get purge -y libicu-dev \
    && rm -r /var/lib/apt/lists
