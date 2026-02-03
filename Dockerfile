FROM php:8.1-cli

LABEL maintainer="Marek Baron <baron.marek@googlemail.com>"
LABEL description="Development container for marek-baron/config"

ARG UID=1000
ARG GID=1000

RUN apt-get update \
 && apt-get install -y unzip vim curl libzip-dev \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip
RUN pecl install pcov
RUN docker-php-ext-enable pcov
ENV PCOV_ENABLED=1

RUN groupmod -g ${GID} www-data \
 && usermod  -u ${UID} -g ${GID} www-data

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY .docker/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html

CMD ["bash"]
