FROM php:8.0.10-fpm

WORKDIR /var/www/html

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive
ENV TZ UTC

RUN set -eux; \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install Linux dependencies
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

# Setup ldap configuration
RUN set -eux; \
    ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so; \
    ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so;

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Get the latest Node
# https://deb.nodesource.com/setup_current.x
# https://deb.nodesource.com/setup_16.x
# https://deb.nodesource.com/setup_lts.x
RUN set -eux; \
    curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -; \
    apt-get install -y nodejs;

# Install Node yarn
RUN set -eux; \
    npm install -g yarn;

# Clear the cache
RUN set -eux; \
    apt-get autoremove -y; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*;

# Configure PHP extension gd
RUN set -eux; \
    docker-php-ext-configure gd \
    --prefix=/usr \
    --with-jpeg \
    --with-webp \
    --with-xpm \
    --with-freetype;

# Configure PHP extension intl
RUN set -eux; \
    docker-php-ext-configure intl;

# Configure PHP extension imap
RUN set -eux; \
    docker-php-ext-configure imap --with-kerberos --with-imap-ssl;

# Configure PHP extension ldap
RUN set -eux; \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/;

# Install core PHP extensions
RUN set -eux; \
    docker-php-ext-install \
    pdo pdo_pgsql pgsql \
    curl mbstring xml \
    zip bcmath soap \
    gd exif pcntl \
    opcache intl imap \
    ldap;

# Install PHP extension redis
RUN set -eux; \
    pecl install -o -f redis; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable redis;

# Install PHP extension memcached
RUN set -eux; \
    pecl -q install memcached; \
    docker-php-ext-enable memcached;

# Install PHP extension pcov
RUN set -eux; \
    pecl install pcov; \
    docker-php-ext-enable pcov;

# Install PHP extension msgpack
RUN set -eux; \
    pecl install msgpack; \
    docker-php-ext-enable msgpack;

# Set the ini settings for production
RUN set -eux; \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini";

COPY ./docker/php/php.ini "$PHP_INI_DIR/99-embers.ini"

# Create the user that will run composer and artisan commands
RUN set -eux; \
    groupadd --force -g 1000 embers; \
    useradd -ms /bin/bash --no-user-group -g embers -u 1000 embers;

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=embers:embers . /var/www/html

# Change current user to embers
USER embers

# debuging
RUN set -eux; \
    ls -all;

# Install node dependencies and build the frontend
RUN set -eux; \
    yarn i; \
    yarn prod;

# Set Environment Variable for opcache
# RUN set -eux; \
#     export PHP_OPCACHE_MAX_ACCELERATED_FILES=$(($(find . -type f -print | grep php | wc -l) + 1000));

EXPOSE 8000

CMD ["php-fpm"]
