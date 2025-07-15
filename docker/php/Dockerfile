FROM php:8.4.8-cli-alpine3.22

ARG GID=1000
ARG UID=1000

ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN set -eux; \
    apk add --no-cache \
        bash \
        git \
        tzdata; \
    \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        mysql-dev; \
    \
    docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql; \
    \
    apk del --purge .build-deps; \
    rm -rf /tmp/pear /var/cache/apk/*; \
    docker-php-source delete

COPY --from=composer:2.8.9 /usr/bin/composer /usr/bin/composer
COPY ./php.ini ${PHP_INI_DIR}/conf.d/99-php.ini
RUN addgroup -g $GID appgroup && \
    adduser -u $UID -G appgroup -s /bin/sh -D appuser

ENV WORKDIR=/var/www/app
RUN mkdir -p ${WORKDIR} && chown appuser:appgroup ${WORKDIR}
WORKDIR ${WORKDIR}

USER appuser
