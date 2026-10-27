#!/bin/sh
set -e

# Salin .env.example jika .env belum ada
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Pastikan direktori database dan file sqlite ada
mkdir -p database storage/logs bootstrap/cache
touch database/database.sqlite
chmod -R 777 database storage bootstrap/cache

# Generate Application Key jika belum terisi
php artisan key:generate --force --ansi || true

# Jalankan migrasi dan seeder data dummy
php artisan migrate --force --seed --ansi

# Cache routes and views untuk performa production
php artisan route:cache || true
php artisan view:cache || true

echo "🌊 NagroSmart Aquaculture Backend siap menerima traffic di port ${PORT:-8000}!"

# Jalankan server
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
