FROM php:8-apache

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install sockets
RUN docker-php-ext-install shmop
COPY ./php.ini /usr/local/etc/php/php.ini
