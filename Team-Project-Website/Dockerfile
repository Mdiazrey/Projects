FROM php:8.3-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html/
COPY docker-entrypoint.sh /usr/local/bin/student-tutoring-entrypoint

RUN chown -R www-data:www-data /var/www/html \
    && chmod +x /usr/local/bin/student-tutoring-entrypoint

EXPOSE 80

CMD ["student-tutoring-entrypoint"]

