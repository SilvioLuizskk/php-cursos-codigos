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

# Install chromedriver for Mink/Selenium tests (optional, used if you need local driver)
RUN set -eux; \
    CHROME_VERSION="114.0.5735.90" || true; \
    # Note: for CI we use selenium/standalone-chrome; chromedriver here is optional
