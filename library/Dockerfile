FROM php:8.2-apache

# Copy all files
COPY . /var/www/html/

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80