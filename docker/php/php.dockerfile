FROM php:8-fpm-alpine
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk --no-cache add \
            zlib-dev libpng libpng-dev \
    && docker-php-ext-install pdo_mysql gd

WORKDIR /var/www/html

RUN echo 'xdebug.mode=develop,debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.output_dir=/var/www/ratehub/data/trace' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    #echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    #echo "xdebug.remote_addr_header=${DOCKER_XDEBUG_HEADER}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.start_with_request=trigger' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY composer.json composer.lock ./

RUN composer install -n

RUN chown --silent --no-dereference --recursive www-data:www-data /var/www

USER www-data

COPY --chown=www-data . /var/www/html