FROM php:8.3.11-fpm
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql