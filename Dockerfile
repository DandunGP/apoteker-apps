FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    bash

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache xml dom

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install Composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Ensure storage and bootstrap directories exist
RUN mkdir -p /var/www/storage/framework/cache/data \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/testing \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/bootstrap/cache

# Change ownership of our applications to www-data
RUN chown -R www-data:www-data /var/www

# Make entrypoint script executable
RUN chmod +x /var/www/docker/entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000

ENTRYPOINT ["/var/www/docker/entrypoint.sh"]
