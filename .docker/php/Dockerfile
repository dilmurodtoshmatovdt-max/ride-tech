FROM node:latest AS node_base

RUN echo "NODE Version:" && node --version
RUN echo "NPM Version:" && npm --version

FROM php:8.3.3-fpm

COPY --from=node_base . .

# Arguments defined in docker-compose.yml
ARG user
ARG uid

ENV EXTENSIONS="pdo_mysql mbstring exif pcntl bcmath gd zip soap intl zip xsl bcmath xml sockets"

# Install system dependencies
RUN apt-get update && apt-get install -y \
    ca-certificates \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libxslt-dev \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev


RUN apt-get install -y bash cron
RUN apt-get install -y procps

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions imagick/imagick@master

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install ${EXTENSIONS}

# Install PHP GD lib
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Redis via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get install -y npm

RUN node --version
RUN npm --version

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    mkdir /var/www/app && \
    chown -R $user:$user /var/www/app

# Install Swoole via PECL
RUN printf "no\nyes\nno\nno\n" | pecl install swoole \
    && docker-php-ext-enable swoole

# Set working directory
WORKDIR /var/www/app

# ✅ Добавляем права для Laravel
# (storage, cache, logs, uploads, vendor)
RUN chmod -R 777 ./storage/framework/views || true && \
    chmod -R 777 ./bootstrap/cache || true && \
    chmod -R 777 ./storage/logs || true && \
    chmod -R 777 ./public/vendor || true && \
    chmod -R 777 ./public/uploads || true && \
    chmod 777 composer.lock || true

# Назначаем владельца
RUN chown -R $user:www-data /var/www/app

USER $user
