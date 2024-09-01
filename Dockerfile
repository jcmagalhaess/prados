# Use a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instale extensões PHP necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilite o mod_rewrite do Apache
RUN a2enmod rewrite

# Defina o diretório de trabalho no Apache
WORKDIR /var/www/html

# Copie os arquivos do seu projeto para o container
COPY . /var/www/html/

# Configurando php.ini
COPY ./php.ini /var/www/html/php.ini

RUN service apache2 restart

# Exponha a porta 80
EXPOSE 80
