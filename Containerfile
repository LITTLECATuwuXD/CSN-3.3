FROM docker.io/library/php:8.4-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
      git unzip libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip xml bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=docker.io/library/composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
