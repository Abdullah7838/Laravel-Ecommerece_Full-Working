FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# PHP extensions
RUN apt-get update && apt-get install -y \
    unzip zip git curl libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
    libjpeg-dev libfreetype6-dev libwebp-dev libxpm-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install pdo_mysql zip gd intl

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project
COPY . .

# Laravel permissions
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Laravel dependencies
RUN composer install --no-interaction --no-scripts --optimize-autoloader
RUN npm install --legacy-peer-deps && npm run build
RUN php artisan storage:link

# Serve from /public directory
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]
