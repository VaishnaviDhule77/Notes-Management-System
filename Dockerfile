FROM php:8.2-apache

WORKDIR /var/www/html

# Copy project
COPY ./library/ /var/www/html/

# Install BOTH mysqli + PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80