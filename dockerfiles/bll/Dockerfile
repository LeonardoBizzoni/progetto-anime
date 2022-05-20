FROM php:8.1.6RC1-fpm-alpine3.15
WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN php /var/www/html/vtuberIsLive.php
