FROM syncxplus/php:7.3.1-apache-stretch

LABEL maintainer=jibo@outlook.com

COPY . /var/www/

RUN chown -R www-data:www-data /var/www

USER www-data

RUN composer install -d /var/www --prefer-dist --optimize-autoloader --no-dev && composer clear-cache

USER root
