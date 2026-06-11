FROM php:8.3-fpm-bookworm

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

# Install Apache2. The fpm image doesn't include it, so we add it from apt.
# COPY config files before a2enmod so that a2enmod picks up and symlinks our tuned .conf files.
RUN apt-get update && apt-get install -y apache2

COPY docker/mpm_event.conf /etc/apache2/mods-available/mpm_event.conf
COPY docker/apache-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php-fpm-pool.conf /usr/local/etc/php-fpm.d/zzz-talishar.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Switch from mpm_prefork to mpm_event; enable modules needed for PHP-FPM proxy and the game.
RUN a2dismod mpm_prefork 2>/dev/null || true \
    && a2enmod mpm_event proxy proxy_fcgi setenvif proxy_http proxy_wstunnel rewrite headers deflate \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# The fpm base image sets STOPSIGNAL SIGQUIT for FPM's graceful stop, but our
# entrypoint is now the watchdog shell, not FPM directly. Reset to SIGTERM so
# docker stop sends the signal our trap actually catches.
STOPSIGNAL SIGTERM
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
