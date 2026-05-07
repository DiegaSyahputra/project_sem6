FROM php:8.2-fpm
WORKDIR /var/www
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip
RUN docker-php-ext-install pdo_mysql gd
COPY . .
RUN mkdir -p /var/www/storage /var/www/cache && \
    chown -R www-data:www-data /var/www/storage /var/www/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/cache