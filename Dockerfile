FROM php:8.4-cli

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer \
    PATH="/app/bin:${PATH}"

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    nano \
    libicu-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    graphviz \
    liblpsolve55-dev \
    build-essential \
    pkg-config \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        pcntl \
        opcache \
        dom \
        intl \
        zip \
    && rm -rf /var/lib/apt/lists/*


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

RUN { \
      echo "opcache.enable=1"; \
      echo "opcache.enable_cli=1"; \
      echo "opcache.validate_timestamps=1"; \
      echo "opcache.revalidate_freq=0"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

CMD ["bash"]
