#!/bin/bash
set -e

DB_PATH="/var/www/html/database/database.sqlite"
touch "$DB_PATH"
chown www-data:www-data "$DB_PATH"
chmod 664 "$DB_PATH"

php artisan key:generate --force

php artisan migrate --force --seed

php artisan storage:link

php artisan package:discover --ansi

exec apache2-foreground
