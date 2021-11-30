FROM php:8.1-apache-buster

RUN apt-get update

RUN pecl install xdebug

RUN pecl install -o -f redis \
  && rm -rf /tmp/pear \
  && docker-php-ext-enable redis
RUN apt-get install redis-tools -y

RUN apt-get install -y zlib1g-dev git libpq-dev libzip-dev unzip libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

ADD virtual_host.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www/html