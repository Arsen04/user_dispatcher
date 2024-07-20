# Use the official PHP image with PHP 8.2
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo_mysql zip intl

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . .

# Set permissions
RUN mkdir -p var/cache var/log
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 var/cache var/log

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
