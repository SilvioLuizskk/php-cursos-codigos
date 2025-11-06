FROM php:8.1-cli

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
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip gd curl \
    && rm -rf /var/lib/apt/lists/*

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["bash"]

# Notes:
# - Project files are mounted into /app by docker-compose so we don't copy them here.
# - The container provides composer and PHP tooling so the runner can execute
#   `composer install` and `vendor/bin/behat` as configured in docker-compose.bdd.yml.
