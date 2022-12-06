FROM php:8.1.6-apache
RUN apt-get update && apt-get install -y libicu-dev
RUN apt-get update && apt-get install -y libbz2-dev
RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y libxslt-dev
RUN apt-get install -y libzip-dev \
        zip \
        libpng-dev \
        libcurl4-openssl-dev
RUN docker-php-ext-configure calendar
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap
RUN docker-php-ext-install intl pcntl sysvsem sysvshm xsl zip mysqli pdo pdo_mysql shmop bz2 bcmath calendar gettext dba gd curl exif
