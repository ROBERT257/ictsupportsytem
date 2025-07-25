# Use official PHP image with Apache
FROM php:8.2-apache

# Install mysqli extension (for MySQL)
RUN docker-php-ext-install mysqli

# Copy all project files to Apache web root
COPY . /var/www/html/

# Set permissions (optional but safe)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
