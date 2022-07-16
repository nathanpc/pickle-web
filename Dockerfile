FROM composer:2 AS composer

WORKDIR /src/app
COPY composer.* ./
RUN composer install --no-dev

FROM php:8-apache
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN a2enmod rewrite

COPY . /var/www/html
COPY --from=composer /src/app/vendor /var/www/html/vendor

EXPOSE 80

