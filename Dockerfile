# Use official PHP image with Apache
FROM php:8.2-apache

# Install mysqli extension (for MySQL)
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (for .htaccess support)
RUN a2enmod rewrite

# Configure Apache to allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy all project files to Apache web root
COPY . /var/www/html/

# Set permissions (optional but safe)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose default Apache port
EXPOSE 80
