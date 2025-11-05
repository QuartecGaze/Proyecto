# Base PHP + Apache
FROM php:8.2-apache

# Copiamos el proyecto al contenedor
COPY ./Proyecto /var/www/html

# Activar mod_rewrite (si tu proyecto lo necesita)
RUN a2enmod rewrite

# Ajuste de permisos dentro del contenedor (opcional)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

FROM php:8.2-apache
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Copiar configuración de Apache
COPY my-apache.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

