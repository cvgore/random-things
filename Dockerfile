FROM php:8.2-cli

RUN apt-get update  \
    && apt-get install -y libmagickwand-dev --no-install-recommends \
    && pecl install imagick-3.7.0 \
    && pecl install xdebug \
    && docker-php-ext-enable imagick \
    && docker-php-ext-enable xdebug \
    && apt-get clean

VOLUME /app
WORKDIR /app
EXPOSE 8000

CMD [ "php", "-S0.0.0.0:8000", "-tpublic" ]