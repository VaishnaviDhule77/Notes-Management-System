FROM php:8.2-apache

# Copy project files
COPY . /var/www/html/

# Enable mysqli
RUN docker-php-ext-install mysqli

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80