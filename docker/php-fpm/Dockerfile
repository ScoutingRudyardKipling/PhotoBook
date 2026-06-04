FROM php:8.3-fpm-alpine

RUN apk add git

WORKDIR /var/www/html
ENV PATH /var/www/html:$PATH

RUN apk add --no-cache libzip-dev unzip libzip-dev libmcrypt-dev wget \
        && docker-php-ext-install pdo pdo_mysql zip exif pcntl bcmath

RUN apk add --no-cache shadow
RUN usermod -u 1000 www-data
RUN groupmod --gid 1000 www-data
RUN chown -R www-data:www-data /var/www

ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 2.10.1

RUN curl --silent --fail --location --retry 3 --output /tmp/installer.php --url https://raw.githubusercontent.com/composer/getcomposer.org/a5874d7ceecca18772d44ed19e7da5fd267ba0a4/web/installer \
     && php -r " \
        \$signature = 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a'; \
        \$hash = hash('sha384', file_get_contents('/tmp/installer.php')); \
        if (!hash_equals(\$signature, \$hash)) { \
            unlink('/tmp/installer.php'); \
            echo 'Integrity check failed, installer is either corrupt or worse.' . PHP_EOL; \
            exit(1); \
        }" \
     && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
     && composer --ansi --version --no-interaction \
     && rm -f /tmp/installer.php

COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf

# Install XDebug
ARG XDEBUG_VERSION="3.5.1"
ARG XDEBUG_CONFIG="client_host=172.17.0.1"
ARG PHP_IDE_CONFIG="serverName=fpm"

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN apk add --update linux-headers
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS

# Install GD
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd
