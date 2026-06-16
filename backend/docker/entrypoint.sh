#!/bin/bash
set -e

DB_PATH="/var/www/html/storage/database.sqlite"
touch "$DB_PATH"

php artisan key:generate --force

php artisan migrate --force

php artisan storage:link

exec apache2-foreground
