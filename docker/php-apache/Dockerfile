FROM php:8.3-apache

# Abilita mod_rewrite per Apache
RUN a2enmod rewrite
RUN a2enmod headers

# Installa estensioni PHP necessarie
RUN docker-php-ext-install pdo pdo_mysql

# Disabilita il virtual host di default
RUN a2dissite 000-default.conf

# Copia la nostra configurazione Apache
COPY php-apache/apache-config.conf /etc/apache2/sites-available/000-app.conf

# Abilita il nostro virtual host
RUN a2ensite 000-app.conf

WORKDIR /var/www/html