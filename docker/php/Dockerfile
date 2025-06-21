FROM php:8.2.14-fpm-alpine3.19

ARG GID=1000
ARG UID=1000

# Set timezone
ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN set -eux; \
    \
    apk add --no-cache \
        bash \
        git; \
    \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        mysql-dev; \
    \
    docker-php-ext-install \
        mysqli \
        pdo_mysql; \
    \
    apk del --purge .build-deps; \
    \
    rm -rf /tmp/pear \
           /var/cache/apk/*; \
    docker-php-source delete

COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer
COPY ./php.ini ${PHP_INI_DIR}/conf.d/99-php.ini
COPY ./entrypoint /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

RUN addgroup -g $GID appgroup && \
    adduser -u $UID -G appgroup -s /bin/sh -D appuser

ENV WORKDIR=/var/www/app
RUN mkdir -p ${WORKDIR} && chown appuser:appgroup ${WORKDIR}
WORKDIR ${WORKDIR}

EXPOSE 9000

USER appuser

CMD ["docker-entrypoint"]
