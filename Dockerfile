FROM php:8.3-apache-bookworm 

RUN apt-get update && apt-get install -y libbz2-dev
RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y libxslt-dev
RUN apt-get update && apt-get install -y libssh2-1-dev
RUN apt-get install -y libzip-dev

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

RUN docker-php-ext-install shmop \
    && docker-php-ext-configure shmop --enable-shmop

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get install -y \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libpng-dev \
            libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install zip mysqli pdo pdo_mysql opcache bz2

RUN apt-get update && apt-get install -y vim # Text Editor for manual edits

RUN pecl install ssh2-1.4 \
    && docker-php-ext-enable ssh2

RUN pecl install apcu \
    && docker-php-ext-enable apcu # APCu caching for performance

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug # Debugger

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN a2enmod proxy proxy_http proxy_wstunnel
