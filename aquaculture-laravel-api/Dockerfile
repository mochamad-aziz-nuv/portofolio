FROM php:8.3-cli-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-install \
    pdo_sqlite \
    bcmath \
    intl \
    opcache \
    zip \
    mbstring

# Install Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Beri izin eksekusi script entrypoint
RUN chmod +x docker-entrypoint.sh

# Install Composer dependencies (production optimize)
RUN composer install --no-dev --optimize-autoloader --no-interaction

ENV PORT=8000
EXPOSE 8000

ENTRYPOINT ["./docker-entrypoint.sh"]
