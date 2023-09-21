FROM php:8.1-cli

RUN apt-get update && apt-get install -y curl git zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install swoole && docker-php-ext-enable swoole
RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug
ENV XDEBUG_CLIENT_HOST=host.docker.internal
ENV XDEBUG_PORT=9001
ENV XDEBUG_IDE_KEY="FIDELIFY"
ENV XDEBUG_START_WITH_REQUEST=yes
RUN echo "xdebug.mode=debug,profile,coverage,develop"  >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
    && echo "xdebug.discover_client_host=0"            >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
    && echo "xdebug.client_host=${XDEBUG_CLIENT_HOST}" >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
    && echo "xdebug.idekey=${XDEBUG_IDE_KEY}"          >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
    && echo "xdebug.client_port=${XDEBUG_PORT}"        >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini" \
    && echo "xdebug.start_with_request=${XDEBUG_START_WITH_REQUEST}" >> "${PHP_INI_DIR}/conf.d/90-xdebug.ini"

COPY . /usr/src/swoole-server
WORKDIR /usr/src/swoole-server

RUN cd /usr/src/swoole-server && composer install
