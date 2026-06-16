#!/bin/bash
set -e

DB_PATH="/var/www/html/storage/database.sqlite"
touch "$DB_PATH"

php artisan key:generate --force

php artisan migrate --force --seed

php artisan storage:link

php artisan package:discover --ansi

exec apache2-foreground
