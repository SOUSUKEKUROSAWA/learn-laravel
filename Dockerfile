FROM php:7.2-apache

WORKDIR /var/www/html

COPY ./src /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install zip gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache

# Copy OPcache configuration
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change the Apache document root
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Enable URL rewriting, redirection etc.
RUN a2enmod rewrite