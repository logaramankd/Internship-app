FROM php:8.2-apache

# System deps
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libssl-dev \
    pkg-config \
    && docker-php-ext-install zip pdo pdo_mysql

# Mongo + Redis
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# Enable rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy backend code
COPY . /var/www/html/

# Set correct permissions (CRITICAL)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Allow Apache to access this directory
RUN sed -i 's|/var/www/|/var/www/html|g' /etc/apache2/apache2.conf

EXPOSE 80
