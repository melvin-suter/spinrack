FROM php:8.3-apache
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/data/database.sqlite

# Enable Apache rewrite (Laravel needs it)
RUN a2enmod rewrite

# System deps + PHP extensions for Laravel + SQLite
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    sqlite3 \
  && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    intl \
    mbstring \
    zip \
  && rm -rf /var/lib/apt/lists/*

# Set Apache DocumentRoot to Laravel /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps (cache-friendly)
COPY src/composer.json src/composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy app
COPY ./src .

# Ensure SQLite DB file exists + permissions for Laravel
RUN mkdir -p /data \
 && touch /data/database.sqlite \
 && chown -R www-data:www-data /data storage bootstrap/cache \
 && chmod -R 775 /data storage bootstrap/cache /data

# Optional: if you want migrations at build time (usually you don't)
# RUN php artisan migrate --force

# Run migrations on container start, then Apache
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
