FROM php:8.3-fpm

WORKDIR /api

RUN apt-get update && apt-get install -y curl bash git vim zip
RUN apt-get update && apt-get install -y libbrotli-dev ${PHPIZE_DEPS}
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install redis swoole && docker-php-ext-enable redis swoole
RUN docker-php-ext-install pdo pdo_mysql

# RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug
# RUN echo "xdebug.mode=debug,profile,coverage,develop" >> "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini" \
#  && echo "xdebug.log_level=0" >> "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini" \
#  && echo "xdebug.client_host=host.docker.internal" >> "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini" \
#  && echo "xdebug.client_port=9001" >> "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini" \
#  && echo "xdebug.start_with_request=yes" >> "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini"

COPY . /api

RUN composer install

ENTRYPOINT [ "sh", "-c", "php public/index.php" ]
