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

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PATH="${PATH}:/root/.composer/vendor/bin"

ADD https://github.com/just-containers/s6-overlay/releases/download/v3.1.0.1/s6-overlay-noarch.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz
ADD https://github.com/just-containers/s6-overlay/releases/download/v3.1.0.1/s6-overlay-x86_64.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-x86_64.tar.xz
ENTRYPOINT ["/init"]
CMD []

WORKDIR /app
RUN apk add --no-cache nginx && \
    mkdir -p /run/nginx /run/php && \
    ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

COPY docker/nginx/conf.d/default.conf /etc/nginx/http.d/default.conf

COPY docker/php/type.sh /etc/s6-overlay/s6-rc.d/10-init-php-fpm/type
COPY docker/php/init.sh /etc/s6-overlay/scripts/10-init-php-fpm
RUN chmod +x /etc/s6-overlay/scripts/10-init-php-fpm
RUN touch /etc/s6-overlay/s6-rc.d/user/contents.d/10-init-php-fpm
COPY docker/php/up.sh /etc/s6-overlay/s6-rc.d/10-init-php-fpm/up

COPY docker/nginx/type.sh /etc/s6-overlay/s6-rc.d/nginx/type
COPY docker/nginx/run.sh /etc/s6-overlay/s6-rc.d/nginx/run
RUN touch /etc/s6-overlay/s6-rc.d/user/contents.d/nginx
COPY docker/nginx/finish.sh /etc/s6-overlay/s6-rc.d/nginx/finish

COPY docker/php/php-fpm.d/type.sh /etc/s6-overlay/s6-rc.d/php-fpm/type
COPY docker/php/run.sh /etc/s6-overlay/s6-rc.d/php-fpm/run
RUN touch /etc/s6-overlay/s6-rc.d/user/contents.d/php-fpm
COPY docker/php/finish.sh /etc/s6-overlay/s6-rc.d/php-fpm/finish

###### deployment
FROM symfony_base as deployment
#todo: configuration
RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY docker/php/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY docker/php/php-fpm.d/zz-docker.prod.conf ${PHP_INI_DIR}-fpm.d/zz-docker.conf

###### development
FROM symfony_base as development
ARG XDEBUG_VERSION=3.1.0
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install xdebug-${XDEBUG_VERSION}; \
    pecl clear-cache; \
    docker-php-ext-enable xdebug; \
    apk add --no-cache git nodejs npm yarn; \
    apk del .build-deps;

COPY docker/php/conf.d/symfony.dev.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY docker/php/php-fpm.d/zz-docker.dev.conf ${PHP_INI_DIR}-fpm.d/zz-docker.conf

COPY docker/angular/type.sh /etc/s6-overlay/s6-rc.d/angular/type
COPY docker/angular/run.sh /etc/s6-overlay/s6-rc.d/angular/run
RUN touch /etc/s6-overlay/s6-rc.d/user/contents.d/angular
COPY docker/angular/finish.sh /etc/s6-overlay/s6-rc.d/angular/finish



