FROM php:8.3-apache

RUN apt-get update && apt-get install -y libbz2-dev
RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y libxslt-dev
RUN apt-get update && apt-get install -y libssh2-1-dev
RUN apt-get install -y libzip-dev

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN docker-php-ext-install zip mysqli pdo pdo_mysql shmop bz2

RUN pecl install ssh2-1.4 \
    && docker-php-ext-enable ssh2

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini