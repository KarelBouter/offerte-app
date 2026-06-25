#!/usr/bin/env bash
# deploy.sh — Productie deploy script voor Offerte Tool
# Gebruik: bash deploy.sh

set -euo pipefail

echo "── Offerte Tool — Deploy ──────────────────────────────────"

# 1. Pull latest code
echo "→ Code bijwerken..."
git pull origin main

# 2. Composer dependencies (no dev)
echo "→ Composer installeren..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. NPM build
echo "→ Frontend builden..."
npm ci --omit=dev
npm run build

# 4. Maintenance mode aan
echo "→ Onderhoudsmodus aan..."
php artisan down --retry=60 --render="errors::503"

# 5. Migrations
echo "→ Migraties uitvoeren..."
php artisan migrate --force

# 6. Clear & cache config
echo "→ Cache opbouwen..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Permissions (pas aan aan server-setup)
echo "→ Rechten instellen..."
chmod -R 775 storage bootstrap/cache
# chown -R www-data:www-data storage bootstrap/cache

# 8. Maintenance mode uit
echo "→ Onderhoudsmodus uit..."
php artisan up

echo ""
echo "✓ Deploy succesvol voltooid op $(date '+%d-%m-%Y %H:%M:%S')"
echo "────────────────────────────────────────────────────────────"
