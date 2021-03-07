FROM php:8-fpm-alpine
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN docker-php-ext-install pdo gd

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install -n

RUN chown --silent --no-dereference --recursive www-data:www-data /var/www

USER www-data

COPY --chown=www-data . /var/www/html