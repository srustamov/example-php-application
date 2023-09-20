FROM php:8.2-fpm
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV USER="www-data"

WORKDIR /app

RUN apt update -y

# Installing dependencies
RUN apt install -y \
    libzip-dev \
    zip \
    unzip \
    curl \
    libcurl4-gnutls-dev

RUN docker-php-ext-install zip pdo pdo_mysql curl opcache

# Installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

COPY ./.docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./.docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

RUN chown -R $USER:$USER /app

RUN composer dump-autoload

EXPOSE 9000