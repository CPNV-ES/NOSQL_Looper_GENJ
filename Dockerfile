FROM php:8.3.11-fpm
RUN pecl install xdebug-3.3.2
RUN docker-php-ext-enable xdebug
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql