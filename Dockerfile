FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create SQLite database file (important for your env)
RUN mkdir -p database && touch database/database.sqlite

# Fix permissions (very important for Render)
RUN chmod -R 775 storage bootstrap/cache database

# Clear caches for production
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Expose Render port
EXPOSE 10000

# Start Laravel server
CMD php -S 0.0.0.0:10000 -t public