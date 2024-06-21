# Usa una imagen base oficial de PHP con Apache
FROM php:7.4-apache

# Instala extensiones necesarias (por ejemplo, mysqli)
RUN docker-php-ext-install mysqli

# Copia el código de tu aplicación al directorio de trabajo del contenedor
COPY . /var/www/html/

# Expone el puerto en el que correrá la aplicación (80 por defecto para Apache)
EXPOSE 80

