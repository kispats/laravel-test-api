# Stage 1 - Build
FROM composer:2 AS builder

WORKDIR /app
COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

RUN php artisan config:cache

# Stage 2 - Runtime
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y nginx supervisor \
    && docker-php-ext-install pdo pdo_mysql opcache

WORKDIR /var/www

COPY --from=builder /app /var/www

COPY docker/nginx.conf /etc/nginx/sites-enabled/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN chown -R www-data:www-data /var/www

EXPOSE 80

CMD ["/usr/bin/supervisord"]