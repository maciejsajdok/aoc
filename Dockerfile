FROM php:8.5-cli

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer \
    PATH="/app/bin:${PATH}"

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip nano curl ca-certificates \
    libicu-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    graphviz \
    lp-solve liblpsolve55-dev \
    build-essential pkg-config autoconf \
  && docker-php-ext-configure intl \
  && docker-php-ext-install -j"$(nproc)" \
      bcmath \
      pcntl \
      intl \
      zip \
      sockets \
  && rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    mkdir -p /tmp/lpsolve-src; \
    curl -fsSL https://github.com/Kerigard/lp-solve-php-docker/archive/8.x.tar.gz \
      | tar -xz -C /tmp/lpsolve-src --strip-components=1; \
    cd /tmp/lpsolve-src/lp-solve/extra/PHP; \
    phpize; \
    ./configure; \
    make -j"$(nproc)"; \
    make install; \
    echo "extension=phplpsolve55.so" > /usr/local/etc/php/conf.d/lpsolve.ini; \
    rm -rf /tmp/lpsolve-src

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

RUN php -m | grep -Ei 'dom|libxml'

CMD ["bash"]
