#!/bin/sh
set -e

# Default to 80 if PORT is not set
PORT=${PORT:-80}

# Debug Environment
echo "--- ENVIRONMENT DEBUG ---"
echo "DB_CONNECTION is set to: '$DB_CONNECTION'"
echo "DB_HOST is set to: '$DB_HOST'"
echo "--- END DEBUG ---"

if [ -z "$DB_CONNECTION" ]; then
    echo "ERROR: DB_CONNECTION variable is missing!"
    echo "Please configure it in Railway variables."
    exit 1
fi

echo "Configuring Apache to listen on port $PORT..."
# Replace 'Listen 80' with 'Listen $PORT' (exact match)
sed -i "s/^Listen 80$/Listen $PORT/g" /etc/apache2/ports.conf
# Replace '<VirtualHost *:80>' with '<VirtualHost *:$PORT>'
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf

# Verify changes
echo "Checking ports.conf:"
grep "Listen" /etc/apache2/ports.conf

# Run system requirements
echo "Linking storage..."
php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
