FROM php:8.3.9-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev mariadb-client \
    libmagickwand-dev --no-install-recommends \
    git curl wget zip unzip vim htop man procps;

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug;

RUN docker-php-ext-install opcache && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install sockets;

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure exif
RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif

# копируем кастомные конфиги PHP
COPY ./conf.d/* ${PHP_INI_DIR}/conf.d/

# создаем группу и пользователя под которым будет запускаться PHP
RUN groupadd -g 1000 php_kafka
RUN useradd -u 1000 -g php_kafka -G sudo -m php_kafka
RUN echo 'php_kafka:qwerty' | chpasswd
# указываем под каким пользователем запускать PHP
USER php_kafka

WORKDIR /var/www/public

RUN touch composer.json && echo '{}' > composer.json
RUN composer require "longlang/phpkafka"