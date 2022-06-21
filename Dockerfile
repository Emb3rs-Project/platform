FROM php:8.1-cli-alpine as builder

RUN apk --no-cache add $PHPIZE_DEPS zip unzip git zlib-dev
RUN pecl install grpc

RUN docker-php-ext-enable grpc

RUN curl -sSLf \
    -o /usr/local/bin/install-php-extensions \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions mbstring pdo_pgsql zip exif pcntl gd memcached protobuf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 

WORKDIR /

COPY . /app

RUN composer install --optimize-autoloader --no-dev

RUN sleep infinity