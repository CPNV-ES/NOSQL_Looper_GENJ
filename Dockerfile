FROM php:8.3.11-fpm
RUN pecl install xdebug-3.3.2
RUN docker-php-ext-enable xdebug