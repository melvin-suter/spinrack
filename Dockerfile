FROM php:8.3-apache

# (Optional) default envs, but .env may override unless you handle it
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/data/database.sqlite

# Enable Apache rewrite (Laravel needs it)
RUN a2enmod rewrite

# Allow .htaccess overrides for Laravel (common gotcha)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# System deps + PHP extensions for Laravel + SQLite
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip \
    libicu-dev libzip-dev \
    sqlite3 libsqlite3-dev \
    libonig-dev \
    pkg-config \
  && docker-php-ext-install pdo pdo_sqlite intl mbstring zip \
  && rm -rf /var/lib/apt/lists/*

# Set Apache DocumentRoot to Laravel /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps (cache-friendly) WITHOUT running scripts (artisan not copied yet)
COPY src/composer.json src/composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy app (artisan exists now)
COPY ./src ./

# Now run Laravel's composer scripts safely
RUN php artisan package:discover --ansi

# Create persistent DB file at build time
RUN mkdir -p /data \
 && touch /data/database.sqlite \
 && chown -R www-data:www-data /data storage bootstrap/cache \
 && chmod -R 775 /data storage bootstrap/cache

# Startup script (migrate, etc.)
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
