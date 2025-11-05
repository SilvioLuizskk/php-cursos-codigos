FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libpng-dev \
    libcurl4-openssl-dev \
    bash \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip gd curl \
    && rm -rf /var/lib/apt/lists/*

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Note: scripts are mounted at runtime via the project volume; avoid copying them at build
ENTRYPOINT ["/var/www/html/scripts/docker-entrypoint.sh"]

CMD ["php-fpm"]
