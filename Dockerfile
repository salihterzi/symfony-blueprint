# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG PHP_VERSION=8.1
# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine AS symfony_base
RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;
ARG APCU_VERSION=5.1.21
ARG REDIS_VERSION=5.3.7
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		zip \
	; \
    pecl install apcu-${APCU_VERSION}; \
    pecl install redis-${REDIS_VERSION}; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
		redis \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

VOLUME /var/run/php
VOLUME /app/var

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PATH="${PATH}:/root/.composer/vendor/bin"

ADD https://github.com/just-containers/s6-overlay/releases/download/v2.2.0.1/s6-overlay-amd64.tar.gz /tmp/
RUN tar xzf /tmp/s6-overlay-amd64.tar.gz -C /
ENTRYPOINT ["/init"]

COPY docker/php/run.sh /etc/services.d/php-fpm/run
COPY docker/php/finish.sh /etc/services.d/php-fpm/finish
COPY docker/php/init.sh /etc/cont-init.d/init-php-fpm.sh

RUN chmod 755 /etc/services.d/php-fpm/run && \
    chmod 755 /etc/services.d/php-fpm/finish && \
    chmod 755 /etc/cont-init.d/init-php-fpm.sh

###### deployment
FROM symfony_base as deployment
#todo: configuration
RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY docker/php/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY docker/php/php-fpm.d/zz-docker.prod.conf ${PHP_INI_DIR}-fpm.d/zz-docker.conf

###### development
FROM symfony_base as development
ARG XDEBUG_VERSION=3.1.0
RUN apk add --no-cache
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install xdebug-${XDEBUG_VERSION}; \
    pecl clear-cache; \
    docker-php-ext-enable xdebug; \
    apk add --no-cache git nodejs npm yarn; \
    apk del .build-deps;

COPY docker/php/conf.d/symfony.dev.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY docker/php/php-fpm.d/zz-docker.dev.conf ${PHP_INI_DIR}-fpm.d/zz-docker.conf

COPY docker/angular/run.sh /etc/services.d/angular/run
COPY docker/angular/finish.sh /etc/services.d/angular/finish
RUN chmod 755 /etc/services.d/angular/run && \
    chmod 755 /etc/services.d/angular/finish


