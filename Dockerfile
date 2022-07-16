FROM php:8-apache
WORKDIR /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN apt-get update && apt-get install -y --no-install-recommends \
	unzip \
	&& rm -rf /var/lib/apt/lists/*

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-dev

COPY . /var/www/html

EXPOSE 80

