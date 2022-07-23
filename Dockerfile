FROM composer:2 AS composer

WORKDIR /src/app
COPY composer.* ./
RUN composer install --no-dev

FROM debian:stable-slim

RUN apt-get update && apt-get install -y --no-install-recommends \
	apache2 \
	curl \
	php \
	libapache2-mod-php \
	&& apt-get clean && rm -rf /var/lib/apt/lists/*

RUN sed -zie 's|\(<Directory /var/www/>\)\(.*\)\(</Directory>\)|\1\nOptions Indexes FollowSymLinks\nAllowOverride All\nRequire all granted\n\3|g' /etc/apache2/apache2.conf && \
	sed -ie 's|\(DocumentRoot\)\(.*\)|\1 /var/www/public|g' /etc/apache2/sites-available/000-default.conf && \
	a2enmod rewrite && \
	service apache2 restart && \
	rm -r /var/www/*

COPY . /var/www/
COPY --from=composer /src/app/vendor /var/www/vendor

EXPOSE 80

ENTRYPOINT [ "service", "apache2", "start" ]

