FROM php:8.1.10-cli

RUN apt-get update && apt-get install -y --no-install-recommends libzip-dev unzip

RUN docker-php-ext-install zip pdo pdo_mysql sockets

RUN pecl install xdebug && docker-php-ext-enable xdebug

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ./app /var/www

WORKDIR /var/www

RUN composer dump-autoload --optimize && \
  composer check-platform-reqs && \
  php bin/console cache:warmup

RUN ./vendor/bin/rr get-binary --location /usr/local/bin

CMD ["rr", "serve"]
