FROM php:8.3-fpm

WORKDIR /api

RUN apt-get update && apt-get install -y curl bash git zip
RUN apt-get update && apt-get install -y libbrotli-dev ${PHPIZE_DEPS}
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install redis swoole && docker-php-ext-enable redis swoole
RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug
ENV XDEBUG_CLIENT_HOST=host.docker.internal
ENV XDEBUG_PORT=9001
ENV XDEBUG_START=yes
RUN echo "xdebug.mode=debug,profile,coverage,develop"  >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
 && echo "xdebug.discover_client_host=0"               >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
 && echo "xdebug.client_host=${XDEBUG_CLIENT_HOST}"    >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
 && echo "xdebug.client_port=${XDEBUG_PORT}"           >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
 && echo "xdebug.start_with_request=${XDEBUG_START}"   >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini"

COPY . /api

RUN composer install

# ENTRYPOINT [ "sh", "-c", "php public/index.php" ]
