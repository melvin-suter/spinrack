FROM php:8.4-apache

# Default envs
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/data/database.sqlite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Enable Apache rewrite + allow .htaccess
RUN a2enmod rewrite \
 && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set Apache DocumentRoot to Laravel /public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# System deps + PHP extensions + cron + node/npm
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    cron \
    nodejs \
    npm \
    libicu-dev \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    libonig-dev \
    pkg-config \
 && docker-php-ext-install pdo pdo_sqlite intl mbstring zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first for better layer caching
COPY src/composer.json src/composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Install frontend deps first for better layer caching
COPY src/package.json src/package-lock.json ./
RUN npm install

# Copy app source
COPY ./src ./

# Build frontend assets (Vite/Sass)
RUN npm run build

# Laravel package discovery
RUN php artisan package:discover --ansi

# Create persistent DB file + permissions
RUN mkdir -p /data \
 && touch /data/database.sqlite \
 && chown -R www-data:www-data /data storage bootstrap/cache \
 && chmod -R 775 /data storage bootstrap/cache

# Cron job
RUN echo '* * * * * root cd /var/www/html && /usr/local/bin/php artisan app:process-jobs >> /var/log/cron.log 2>&1' > /etc/cron.d/laravel \
 && chmod 0644 /etc/cron.d/laravel \
 && touch /var/log/cron.log

# Startup script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]