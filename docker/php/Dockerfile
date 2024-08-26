# From
FROM php:8.2.14-fpm-alpine3.19

ARG GID=1000
ARG UID=1000

# Set timezone
ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install non-dev dependencies
RUN set -eux \
  && apk add --no-cache \
  bash git
#  vim zip unzip curl tzdata icu-libs \
#  c-client make ca-certificates imap gmp gettext libssh2 \
#  libintl libxslt libpng libwebp libjpeg-turbo freetype imap \
#  linux-headers oniguruma libxslt libpq vips \
#  gmp libzip libxml2 freetds

#RUN DEBIAN_FRONTEND=noninteractive apt-get update -q \
#    && apt-get install --assume-yes --no-install-recommends apt-utils | grep -v "debconf: delaying package configuration, since apt-utils is not installed" \
#    && apt-get -y upgrade \
#    && apt-get -y install --no-install-recommends --no-install-suggests curl zip unzip telnet iputils-ping default-mysql-client \
#        libssl-dev webp libwebp-dev redis-tools libfcgi0ldbl libfcgi-bin whois dnsutils \
#    && install-php-extensions sockets pcntl \
#    && cleanimage \
#    && php -m

# Install dependencies
RUN set -eux \
  && apk add --no-cache --virtual .build_deps \
#  libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev imap-dev \
#  linux-headers oniguruma-dev libxslt-dev postgresql-dev vips-dev \
#  libssh2-dev gmp-dev libzip-dev libxml2-dev freetds-dev \
  $PHPIZE_DEPS \
  \
  # Php extensions
  && docker-php-ext-install \
  mysqli \
  pdo_mysql \
#  pdo_pgsql \
#  pgsql\
#  bcmath \
#  mbstring \
#  xml \
#  gd \
#  exif \
#  zip \
#  soap \
#  intl \
#  xsl \
#  pcntl \
#  sockets \
#  sysvmsg \
#  sysvsem \
#  sysvshm \
#  opcache \
#  imap \
#  gmp \
#  \
  # Install xdebug
#  && pecl install -o -f xdebug \
#  && docker-php-ext-enable xdebug
#  \
  # Install redis
#  && pecl install -o -f redis \
#  && docker-php-ext-enable redis \
#  \
  # Install mongodb
#  && pecl install -o -f mongodb \
#  && docker-php-ext-enable mongodb \
#  \
  # Install apcu
#  && pecl install -o -f apcu \
#  && docker-php-ext-enable apcu \
#  \
  # Install ssh2 \
#  && pecl install -o -f ssh2 \
#  && docker-php-ext-enable ssh2 \
  \
#   Clean php extensions
  && rm -rf /tmp/pear \
  && docker-php-source delete \
  \
#   Clean up
  && apk del --purge .build_deps \
  && rm -rf /var/cache/apk/*

#RUN if [ ${PHP_INSTALL_XDEBUG} = 1 ]; then \
#    install-php-extensions xdebug-^${XDEBUG_VERSION} \
#    && cleanimage \
#    && php -m \
#;fi

# Install composer
COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

# Copy php settings
COPY ./php.ini ${PHP_INI_DIR}/conf.d/99-php.ini

# Copy entrypoint
COPY ./entrypoint /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Workdir
ENV WORKDIR=/var/www/app
RUN mkdir -p ${WORKDIR}
WORKDIR ${WORKDIR}

# Expose port
EXPOSE 9000

USER $UID:$GID

# Run entrypoint
CMD ["docker-entrypoint"]
