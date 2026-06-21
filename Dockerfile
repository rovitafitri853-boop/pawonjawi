FROM php:8.2-fpm

# Install sistem yang dibutuhkan
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip unzip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd pdo pdo_mysql

# Copy file proyek
COPY . /var/www
WORKDIR /var/www

# Copy konfigurasi (opsional)
CMD php artisan serve --host=0.0.0.0 --port=$PORT