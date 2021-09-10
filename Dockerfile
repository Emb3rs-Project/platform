FROM php:8.0.10-fpm

WORKDIR /var/www/html

# Set Environment Variables
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN set -eux; \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install Linux dependencies
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y \
    apt-utils \
    build-essential \
    curl \
    zip \
    unzip \
    ca-certificates \
    gnupg \
    locales \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    postgresql-client \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libwebp-dev \
    libxpm-dev \
    libmcrypt-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    libzip-dev \
    libedit-dev;

# Configure and install Linux package gnupg
RUN set -eux; \
    mkdir -p ~/.gnupg; \
    chmod 600 ~/.gnupg; \
    echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf; \
    apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C; \
    apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C300EE8C;

# Clear the cache
RUN set -eux; \
    apt-get autoremove -y; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*;

# Install core PHP extensions
RUN set -eux; \
    docker-php-ext-install \
    pdo_pgsql pgsql \
    curl \
    mbstring xml zip \
    bcmath soap \
    exif pcntl opcache;

# Install PHP extension redis
RUN set -eux; \
    pecl install -o -f redis; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable redis;

RUN set -eux; \
    pecl -q install memcached; \
    docker-php-ext-enable memcached;

RUN set -eux; \
    pecl install pcov; \
    docker-php-ext-enable pcov;

# Configure and install PHP extension gd
RUN set -eux; \
    docker-php-ext-configure gd \
    --prefix=/usr \
    --with-jpeg \
    --with-webp \
    --with-xpm \
    --with-freetype; \
    docker-php-ext-install gd;

# Set the ini settings
RUN set -eux; \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini";

# Create the user that will run composer and artisan commands
RUN set -eux; \
    groupadd --force -g 1000 embers; \
    useradd -ms /bin/bash --no-user-group -g embers -u 1000 embers;

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Get latest Node
RUN set -eux; \
    curl -sL https://deb.nodesource.com/setup_16.x | bash -; \
    apt-get install -y nodejs;

RUN set -eux; \
    npm install -g yarn;

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=embers:embers . /var/www/html

COPY ./docker/php/php.ini "$PHP_INI_DIR/99-embers.ini"

# Change current user to embers
USER embers

RUN set -eux; \
    ls -all;

RUN set -eux; \
    npm i; \
    npm run prod;

# Set Environment Variable for opcache
RUN set -eux; \
    export PHP_OPCACHE_MAX_ACCELERATED_FILES=$(($(find . -type f -print | grep php | wc -l) + 1000));

EXPOSE 8000

CMD ["php-fpm"]
