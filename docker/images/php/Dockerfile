FROM php:8.3.9-fpm

RUN apt-get update && apt-get install --no-install-recommends --no-install-suggests -y \ 
    git \
    cron \
    curl \
    wget \
    zip \
    unzip \
    vim \
    htop \
    man \
    procps \
    libmcrypt-dev \
    libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install bcmath

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug;

RUN docker-php-ext-install opcache && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install sockets;

RUN docker-php-ext-configure exif
RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# копируем кастомные конфиги PHP
COPY ./conf.d/* ${PHP_INI_DIR}/conf.d/

# создаем группу и пользователя под которым будет запускаться PHP
RUN groupadd -g 1000 php_kafka
RUN useradd -u 1000 -g php_kafka -G sudo -m php_kafka
RUN echo 'php_kafka:qwerty' | chpasswd
# указываем под каким пользователем запускать PHP
USER php_kafka

WORKDIR /var/www/public

#ENTRYPOINT ["composer"]
#CMD ["i"]