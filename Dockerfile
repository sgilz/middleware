FROM php:7.3-apache-stretch
RUN apt-get update -y && apt-get install -y openssl zip unzip git zlib1g-dev libicu-dev libpng-dev g++
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
COPY . /var/www/html
COPY ./public/.htaccess /var/www/html/.htaccess
WORKDIR /var/www/html
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist
RUN php artisan key:generate
RUN php artisan migrate
RUN chmod -R 777 storage
RUN a2enmod rewrite
RUN service apache2 restart
