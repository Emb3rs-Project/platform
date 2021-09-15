FROM php:8.0.10-fpm

WORKDIR /var/www/html

ARG NOVA_USERNAME
ARG NOVA_PASSWORD

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive
ENV TZ UTC

# Set the timezone
RUN set -eux; \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install Linux Dependencies
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y \
    apt-utils \
    build-essential \
    ca-certificates \
    g++ \
    curl \
    zip \
    unzip \
    git \
    gnupg \
    locales \
    postgresql-client \
    libmemcached-dev \
    libicu-dev \
    zlib1g-dev \
    libc-client-dev \
    libkrb5-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libssl-dev \
    libxpm-dev \
    libmcrypt-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    libzip-dev \
    libedit-dev \
    libldb-dev \
    libldap2-dev;

# Setup PHP Module ldap configuration
RUN set -eux; \
    ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so; \
    ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so;

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Get the latest Node
RUN set -eux; \
    curl -fsSL https://deb.nodesource.com/setup_current.x | bash -; \
    apt-get install -y nodejs;

# Install Node Dependency yarn
RUN set -eux; \
    npm install -g yarn;

# Clear apt cache
RUN set -eux; \
    apt-get autoremove -y; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*;

# Configure PHP Module gd
RUN set -eux; \
    docker-php-ext-configure gd \
    --prefix=/usr \
    --with-jpeg \
    --with-webp \
    --with-xpm \
    --with-freetype;

# Configure PHP Module intl
RUN set -eux; \
    docker-php-ext-configure intl;

# Configure PHP Module imap
RUN set -eux; \
    docker-php-ext-configure imap --with-kerberos --with-imap-ssl;

# Configure PHP Module ldap
RUN set -eux; \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/;

# Install core PHP Modules
RUN set -eux; \
    docker-php-ext-install \
    pdo pdo_pgsql pgsql curl \
    mbstring xml zip bcmath \
    soap gd exif pcntl \
    opcache intl imap ldap;

# Install PHP Module redis
RUN set -eux; \
    pecl install -o -f redis; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable redis;

# Install PHP Module memcached
RUN set -eux; \
    pecl -q install memcached; \
    docker-php-ext-enable memcached;

# Install PHP Module pcov
RUN set -eux; \
    pecl install pcov; \
    docker-php-ext-enable pcov;

# Install PHP Module msgpack
RUN set -eux; \
    pecl install msgpack; \
    docker-php-ext-enable msgpack;

# Set the php.ini settings for production
RUN set -eux; \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini";

# Create the user that will run composer and artisan commands
RUN set -eux; \
    groupadd --force -g 1000 embers; \
    useradd -ms /bin/bash --no-user-group -g embers -u 1000 embers;

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=embers:embers . /var/www/html

# Set Environment Variable for OPCache
RUN set -eux; \
    export PHP_OPCACHE_MAX_ACCELERATED_FILES=$(($(find . -type f -print | grep php | wc -l) + 1000));

# Set the php.ini settings for our project
COPY ./docker/php/php.ini "$PHP_INI_DIR/99-embers.ini"

# Change current user to embers
USER embers

# Configure Laravel Nova composer authentication
RUN set -eux; \
    composer config http-basic.nova.laravel.com $NOVA_USERNAME $NOVA_PASSWORD;

# Install PHP Dependencies
RUN set -eux; \
    composer install --optimize-autoloader --no-dev;

# Install Node Dependencies and build the frontend
RUN set -eux; \
    yarn; \
    yarn prod;

# PHP-FPM image already exposes port 9000
# We just double list it here for readability
EXPOSE 9000

CMD ["php-fpm"]
