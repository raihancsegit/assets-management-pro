# Use the official PHP image as the base
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libmariadb-dev \
    libssl-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    mysql-common \
    libldap2-dev \
    libicu-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    default-mysql-client \
  && docker-php-ext-install gettext \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install -j$(nproc) pdo_mysql \
  && docker-php-ext-install -j$(nproc) mbstring zip exif pcntl mysqli \
  && docker-php-ext-enable mysqli

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install NVM
RUN npm cache clean -f \
  && npm install -g n \
  && n stable

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for Laravel application
RUN groupadd -g 1000 www \
  && useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY --chown=www:www ./amp-app/ /var/www/html

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]