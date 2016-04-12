FROM php:zts-alpine
RUN pecl install pthreads && \
    docker-php-ext-enable pthreads
COPY . /epiphany
WORKDIR /epiphany
CMD php server.php
EXPOSE 8080
