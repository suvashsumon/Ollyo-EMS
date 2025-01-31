# Use the official PHP 8.0 Apache image as a base
FROM php:8.0-apache

# Install required dependencies and enable PHP extensions for MySQL and PDO
RUN apt-get update && apt-get install -y \
    libpdo-mysql \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy your PHP application to the Apache server's document root
COPY . /var/www/html/

# Expose port 80 to make the web server accessible
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
