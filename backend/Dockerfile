# Usamos una imagen oficial de PHP con Apache (aunque usaremos artisan serve, esta trae buenas librerías)
FROM php:8.3-apache

# 1. Instalar dependencias del sistema necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# 2. Limpiar caché para reducir tamaño de imagen
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensiones de PHP requeridas
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Instalar Composer (el gestor de paquetes de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Establecer el directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar los archivos del proyecto al contenedor
COPY . .

# 7. Instalar dependencias de PHP
# Usamos --no-scripts para evitar errores si falta la DB al construir
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Dar permisos a las carpetas de almacenamiento
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Exponer el puerto 8000 (donde correrá artisan serve)
EXPOSE 8000

# 10. Comando para arrancar el servidor
CMD php artisan serve --host=0.0.0.0 --port=8000