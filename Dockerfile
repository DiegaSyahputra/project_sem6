# FROM php:8.2-fpm
# WORKDIR /var/www
# RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip
# RUN docker-php-ext-install pdo_mysql gd
# COPY . .
# RUN mkdir -p /var/www/storage /var/www/cache && \
#     chown -R www-data:www-data /var/www/storage /var/www/cache
# RUN chown -R www-data:www-data /var/www/storage /var/www/cache

# MEMAKAI LARAVEL SERVE DI DOCKER COMPOSE
# FROM php:8.2-fpm

# WORKDIR /var/www

# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     zip \
#     curl \
#     libpng-dev \
#     libjpeg-dev \
#     libfreetype6-dev

# RUN docker-php-ext-install pdo_mysql gd

# # Install Node.js & npm
# RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
#     apt-get install -y nodejs

# COPY . .

# RUN mkdir -p /var/www/storage /var/www/bootstrap/cache && \
#     chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# EXPOSE 8000

# CMD php artisan serve --host=0.0.0.0 --port=8000


# MEMAKAI PHP-FPM DI DOCKER COMPOSE
# FROM php:8.2-fpm

# WORKDIR /var/www

# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     zip \
#     curl \
#     libpng-dev \
#     libjpeg-dev \
#     libfreetype6-dev

# RUN docker-php-ext-install pdo_mysql gd

# # Install Node.js
# RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
#     apt-get install -y nodejs

# COPY . .

# RUN npm install
# RUN npm run build

# RUN chown -R www-data:www-data /var/www/storage
# RUN chown -R www-data:www-data /var/www/bootstrap/cache

# EXPOSE 9000

# CMD ["php-fpm"]


FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

RUN docker-php-ext-install pdo_mysql gd zip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install frontend dependencies
RUN npm install

# Build Vite production assets
RUN npm run build

RUN chown -R www-data:www-data storage
RUN chown -R www-data:www-data bootstrap/cache

RUN chmod -R 775 storage
RUN chmod -R 775 bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]