# Use the official PHP 8.4 Apache image
FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    dos2unix \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Copy custom vhost config
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Set permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Make entrypoint script executable
RUN dos2unix docker/entrypoint.sh && chmod +x docker/entrypoint.sh

# Expose port (Render sets PORT env var, usually 10000)
EXPOSE 80 10000

# Set entrypoint
ENTRYPOINT ["docker/entrypoint.sh"]

# Start Apache in foreground
CMD ["apache2-foreground"]
