# PHP ve Nginx için temel imajı kullan
FROM php:8.2-fpm

# Sistem paketlerini yükle
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git libzip-dev supervisor && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql zip

# Composer'ı yükle
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Çalışma dizini olarak /var/www belirle
WORKDIR /var/www

# Proje dosyalarını container'a kopyala
COPY . /var/www

# Composer ile bağımlılıkları yükle
RUN composer install --no-dev --optimize-autoloader

# Supervisor konfigürasyon dosyasını ekleyin
#COPY supervisor.conf /etc/supervisor/conf.d/supervisord.conf

# Nginx'in 80 numaralı portu üzerinden hizmet vereceğini belirt
EXPOSE 80

# PHP-FPM'i başlat
CMD ["php-fpm"]
