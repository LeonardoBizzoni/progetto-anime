FROM php:8.1.4-fpm-alpine3.14
WORKDIR /var/www/html

RUN apk update && apk add --no-cache \
    php8 \
    php8-fpm \
    php8-mysqli \
    nginx

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN php-fpm &

COPY nginx.conf /etc/nginx/nginx.conf
CMD php-fpm -D && nginx -g "daemon off;"
