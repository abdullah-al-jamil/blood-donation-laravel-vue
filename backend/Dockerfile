FROM php:8.4-apache

RUN apt-get update && apt-get install -y git unzip libzip-dev libsqlite3-dev && \
    docker-php-ext-install zip pdo_sqlite && \
    a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY . /var/www/html/
WORKDIR /var/www/html

RUN cp .env.example .env
RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN chown -R www-data:www-data storage bootstrap/cache database

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
