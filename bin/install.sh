#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."

composer install
cp -n .env.example .env || true
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed --force
php artisan storage:link || true
npm install || true
npm run build || true

echo "Installed. Demo password: password"
echo "Public site: /  | Platform: /platform  | Business: /business  | Staff: /staff  | Driver: /driver  | Portal: /portal"
echo "Live demo logins: /demo"
