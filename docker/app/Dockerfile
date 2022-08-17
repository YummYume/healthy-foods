FROM php:8.1-fpm-alpine

WORKDIR /app

RUN apk add --no-cache --update \
   icu-dev && \
   docker-php-ext-configure intl && \
   docker-php-ext-install pdo pdo_mysql intl && \
   docker-php-ext-enable pdo pdo_mysql intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ./symfony-inertia-svelte .

RUN apk update && apk add -u yarn
RUN yarn install
