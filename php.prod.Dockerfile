FROM php:8.3.11-fpm
RUN pecl install mongodb
RUN docker-php-ext-enable mongodb
RUN apt-get update && apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql