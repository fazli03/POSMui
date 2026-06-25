#!/bin/bash
set -e

# Pastikan file SQLite ada
touch database/database.sqlite

# Jalankan migration
php artisan migrate --force

# Seed jika tabel projects kosong
COUNT=$(php artisan tinker --execute="echo App\Models\Project::count();" 2>/dev/null | tail -1)
if [ "$COUNT" = "0" ] || [ -z "$COUNT" ]; then
  php artisan db:seed --class=ProjectSeeder --force
fi

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start server
php -S 0.0.0.0:${PORT:-8000} -t public
