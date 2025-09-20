FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
      icu-dev libpq-dev ${PHPIZE_DEPS} tzdata \
 && docker-php-ext-configure intl \
 && docker-php-ext-install -j"$(nproc)" intl pdo pdo_pgsql \
 && apk del ${PHPIZE_DEPS}

ENV TZ=America/Bahia
RUN echo "date.timezone=${TZ}" > /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/html

EXPOSE 9000
