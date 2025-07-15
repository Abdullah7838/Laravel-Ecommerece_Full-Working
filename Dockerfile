FROM php:8.2-cli

# ----------------------------
# System dependencies
# ----------------------------
RUN apt-get update && apt-get install -y \
    unzip zip git curl \
    libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
    libjpeg-dev libfreetype6-dev libwebp-dev libxpm-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install \
        pdo_mysql \
        zip \
        gd \
        intl

# ----------------------------
# Install Node.js (v18)
# ----------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# ----------------------------
# Copy Composer from official image
# ----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ----------------------------
# Set application directory
# ----------------------------
WORKDIR /var/www

# ----------------------------
# Copy application code
# ----------------------------
COPY . .

# ----------------------------
# Fix permissions for Laravel
# ----------------------------
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# ----------------------------
# Install dependencies and build frontend
# ----------------------------
RUN composer install --no-interaction --no-scripts --optimize-autoloader
RUN npm install --legacy-peer-deps && npm run build

# ----------------------------
# Laravel storage link
# ----------------------------
RUN php artisan storage:link

# ----------------------------
# Expose Laravel dev port
# ----------------------------
EXPOSE 8000

# ----------------------------
# Run Laravel server
# ----------------------------
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
