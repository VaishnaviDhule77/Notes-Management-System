FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy ONLY the library folder contents
COPY ./library/ /var/www/html/

# Enable mysqli
RUN docker-php-ext-install mysqli

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80