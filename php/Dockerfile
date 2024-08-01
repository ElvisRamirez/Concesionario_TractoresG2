# Dockerfile para PHP
FROM php:8.2-apache

# Instalar dependencias necesarias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar archivos de la aplicación al contenedor
COPY ./src /var/www/html

# Opcional: configurar el servidor Apache si es necesario

# Puerto que escucha Apache
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]