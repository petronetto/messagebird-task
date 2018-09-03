FROM alpine:3.7

LABEL maintainer="Juliano Petronetto <juliano@petronetto.com.br>"

ENV XDEBUG_ENABLE=0 \
    PHP_DISPLAY_ERRORS="Off" \
    PHP_DISPLAY_STARTUP_ERRORS="Off" \
    PHP_ERROR_REPORTING="E_ALL & ~E_DEPRECATED & ~E_STRICT" \
    PHP_CGI_FIX_PATHINFO=0 \
    BUILD_DEPS="autoconf g++ make php7-dev openssl-dev php7-pear"

# Coping project
COPY ./docker /
COPY ./ /app

# Install packages to compile Mongo extension
RUN set -ex; \
    apk --update upgrade --no-cache; \
    apk add --no-cache --virtual .build-deps $BUILD_DEPS; \
# Install packages
    apk add --no-cache \
        nginx \
        curl \
        supervisor \
        php7 \
        php7-fileinfo \
        php7-fpm \
        php7-json \
        php7-curl \
        php7-opcache \
        php7-xdebug; \
# Cleaning
    apk del .build-deps; \
    rm -rf /var/cache/apk/*; \
# Set UID for www user to 1000
    adduser -D -u 1000 -g 'www' www; \
    chown -R www:www /var/lib/nginx; \
# Removing config dir
    rm -rf /app/docker; \
    chmod +x /start.sh; \
# Fixing permissions
    chown -Rf www:www /app;

WORKDIR /app

ENTRYPOINT ["sh"]

# Start Supervisord
CMD ["/start.sh"]
