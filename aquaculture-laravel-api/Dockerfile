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

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create SQLite database if not present
RUN touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

# Environment setup
ENV PORT=8000
EXPOSE 8000

# Entrypoint script: migrate & run server
CMD php artisan key:generate --force \
    && php artisan migrate --force --seed \
    && php artisan serve --host=0.0.0.0 --port=${PORT}
