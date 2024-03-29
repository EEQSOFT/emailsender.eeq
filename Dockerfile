FROM php:8.0-apache

RUN a2enmod rewrite
RUN apt-get update && apt-get install -y cron
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y

CMD (cron -f -l 8 &) && apache2-foreground
