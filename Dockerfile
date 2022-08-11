# Fetch our dependencies with Composer.
FROM composer:2 AS composer

WORKDIR /src/app
COPY composer.* ./
RUN composer install --no-dev

RUN chmod -R 755 vendor/twbs/bootstrap/dist/ && \
	chmod -R 755 vendor/components/jquery/

# Reconfigure Apache to work with our application.
FROM alpine:3 AS apache-config

RUN apk update && apk upgrade && apk add \
	php81-apache2 \
	curl \
	php-mbstring \
	sed \
	&& rm -rf /var/cache/apk/*

RUN sed -zie 's|\(<Directory "/var/www/localhost/htdocs">\)\(.*\)\(</Directory>\)|\1\nOptions Indexes FollowSymLinks\nAllowOverride All\nRequire all granted\n\3|g' /etc/apache2/httpd.conf && \
	sed -ie 's|/var/www/localhost/htdocs|/var/www/app/public|g' /etc/apache2/httpd.conf && \
	sed -ie 's|#\(LoadModule rewrite_module modules/mod_rewrite.so\)|\1|g' /etc/apache2/httpd.conf

# Setup our application.
FROM alpine:3

RUN apk update && apk add \
	php81-apache2 \
	curl \
	php-mbstring \
	&& rm -rf /var/cache/apk/*

COPY --from=apache-config /etc/apache2/httpd.conf /etc/apache2/httpd.conf

WORKDIR /var/www/app
COPY --from=composer /src/app/vendor ./vendor
COPY --from=composer /src/app/vendor/twbs/bootstrap/dist/ ./public/lib/bootstrap/
COPY --from=composer /src/app/vendor/components/jquery/ ./public/lib/jquery/
COPY . ./

EXPOSE 80

ENTRYPOINT ["/usr/sbin/httpd", "-D", "FOREGROUND"]
