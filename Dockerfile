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
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
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

COPY docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck
HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

VOLUME /var/run/php
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /app
EXPOSE 80

FROM symfony_base as deployment

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY docker/php/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY docker/php/php-fpm.d/zz-docker.prod.conf ${PHP_INI_DIR}-fpm.d/zz-docker.conf
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM symfony_base as development
ARG XDEBUG_VERSION=3.1.0
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install xdebug-${XDEBUG_VERSION}; \
    pecl clear-cache; \
    docker-php-ext-enable xdebug; \
    apk del .build-deps;
COPY docker/php/conf.d/symfony.dev.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY docker/php/php-fpm.d/zz-docker.dev.conf ${PHP_INI_DIR}-fpm.d/zz-docker.conf
VOLUME /app/var
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
