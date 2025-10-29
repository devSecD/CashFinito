FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de composer
COPY composer.json composer.lock ./

# Instalar dependencias de PHP (sin dev para producción)
RUN composer install --no-dev --optimize-autoloader

# Copiar código de la aplicación
COPY . .

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www/www-data /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Comando para desarrollo
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]