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
        python3  \
        make \
        g++ \
	;
ARG APCU_VERSION=5.1.21
ARG REDIS_VERSION=5.3.7

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl zip apcu-${APCU_VERSION} redis-${REDIS_VERSION} @composer-2

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

RUN install-php-extensions xdebug-${XDEBUG_VERSION}
RUN apk add --no-cache git nodejs npm yarn

COPY docker/php/conf.d/symfony.dev.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY docker/php/php-fpm.d/zz-docker.dev.conf ${PHP_INI_DIR}-fpm.d/zz-docker.conf

COPY docker/angular/type.sh /etc/s6-overlay/s6-rc.d/angular/type
COPY docker/angular/run.sh /etc/s6-overlay/s6-rc.d/angular/run
RUN touch /etc/s6-overlay/s6-rc.d/user/contents.d/angular
COPY docker/angular/finish.sh /etc/s6-overlay/s6-rc.d/angular/finish



