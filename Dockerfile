FROM php:8.2-apache

# Installer les dépendances système et extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    curl \
    gnupg \
    && docker-php-ext-install pdo pdo_pgsql zip

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Configurer le DocumentRoot Apache vers le dossier public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Installer Node.js (pour compiler les assets Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Configurer le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP (sans dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Installer les dépendances JS et compiler avec Vite
RUN npm install && npm run build

# Ajuster les permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80 (standard Railway pour Apache)
EXPOSE 80

# Script de démarrage personnalisé
RUN echo "#!/bin/sh\n\
    # Configurer le port Apache dynamiquement selon la variable PORT de Railway\n\
    sed -i \"s/80/\${PORT:-80}/g\" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf\n\
    \n\
    # Exécuter les migrations et le cache\n\
    php artisan migrate --force\n\
    php artisan storage:link\n\
    php artisan config:cache\n\
    php artisan route:cache\n\
    php artisan view:cache\n\
    \n\
    # Démarrer Apache\n\
    apache2-foreground" > /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

CMD ["/usr/local/bin/start-container"]
