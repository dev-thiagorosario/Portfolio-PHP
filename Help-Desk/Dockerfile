# Runtime PHP-FPM 8.4 (Alpine)
FROM php:8.4-fpm-alpine

# UID/GID configuráveis para alinhar com o usuário do host
ARG APP_UID=1000
ARG APP_GID=1000


# Atualiza pacotes base (corrige CVEs conhecidos)
RUN apk update && apk upgrade --no-cache

# Pacotes de runtime (somente o necessário em produção)
# - postgresql-libs: libpq (runtime)
# - icu-libs: runtime do intl
# - tzdata: timezone
RUN apk add --no-cache \
    postgresql-libs \
    icu-libs \
    tzdata

# Pacotes de build temporários para compilar extensões (serão removidos)
# - libpq-dev, icu-dev e $PHPIZE_DEPS são necessários só pra build das ext
RUN apk add --no-cache --virtual .build-deps \
    libpq-dev \
    icu-dev \
    $PHPIZE_DEPS \
 && docker-php-ext-configure intl \
 && docker-php-ext-install -j"$(nproc)" intl pdo pdo_pgsql \
 && apk del .build-deps

# Timezone (Alpine way) + também seta no PHP
ENV TZ=America/Bahia
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
 && echo "$TZ" > /etc/timezone \
 && echo "date.timezone=${TZ}" > /usr/local/etc/php/conf.d/timezone.ini

# OPcache recomendado para produção
RUN { \
      echo 'opcache.enable=1'; \
      echo 'opcache.enable_cli=0'; \
      echo 'opcache.validate_timestamps=0'; \
      echo 'opcache.max_accelerated_files=20000'; \
      echo 'opcache.memory_consumption=128'; \
      echo 'opcache.interned_strings_buffer=16'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

# Usuário não-root (opcional, recomendado)
# (pode usar www-data se preferir)
RUN addgroup -S -g "$APP_GID" app \
 && adduser -S -u "$APP_UID" -G app app \
 && chown -R app:app /var/www/html
USER app

EXPOSE 9000
# CMD padrão do php:fpm já é ["php-fpm"]
