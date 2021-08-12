FROM php:8.0-cli-alpine

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
