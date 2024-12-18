FROM php:8.3.11-fpm
RUN apt-get update && apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev autoconf
RUN pecl install mongodb
RUN pecl install xdebug-3.3.2
RUN docker-php-ext-enable mongodb
RUN docker-php-ext-enable xdebug
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql