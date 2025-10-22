FROM php:8.1-cli

LABEL maintainer="Marek Baron <baron.marek@googlemail.com>"
LABEL description="Development container for marek-baron/config"

RUN apt-get update
RUN apt-get install -y git zip unzip vim curl libzip-dev

RUN docker-php-ext-install zip
RUN pecl install pcov
RUN docker-php-ext-enable pcov
ENV PCOV_ENABLED=1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY .docker/php.ini /usr/local/etc/php/php.ini

WORKDIR /app

CMD ["bash"]
