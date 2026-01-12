FROM php:8.2-apache

# Install system packages
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install zip pdo pdo_mysql

# Install MongoDB + Redis extensions
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# Enable Apache modules
RUN a2enmod rewrite headers

# Allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy backend code (WITHOUT vendor)
COPY . /var/www/html/

# Install PHP dependencies inside container
RUN composer install --no-dev --optimize-autoloader

# Copy CORS config
COPY cors.conf /etc/apache2/conf-available/cors.conf
RUN a2enconf cors

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
