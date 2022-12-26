FROM php:8.1-cli

RUN apt-get update && apt-get install -y curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install swoole && docker-php-ext-enable swoole

# RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug
# RUN touch /etc/php.d/99-xdebug.ini \
#  && echo "zend_extension=/usr/lib64/php/modules/xdebug.so"  >> /etc/php.d/99-xdebug.ini \
#  && echo "xdebug.mode=debug,profile,coverage,develop"  >> /etc/php.d/99-xdebug.ini \
#  && echo "xdebug.client_port=9003"  >> /etc/php.d/99-xdebug.ini \
#  && echo "xdebug.client_host=10.100.12.175"  >> /etc/php.d/99-xdebug.ini \
#  && echo "xdebug.start_with_request=yes"  >> /etc/php.d/99-xdebug.ini \
#  && echo "xdebug.idekey=docker"  >> /etc/php.d/99-xdebug.ini

COPY . /usr/src/swoole-server
WORKDIR /usr/src/swoole-server

CMD [ "php", "index.php" ]
