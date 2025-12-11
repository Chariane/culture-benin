#!/bin/sh
set -e

# Default to 80 if PORT is not set
PORT=${PORT:-80}

# Debug Environment
echo "--- ENVIRONMENT DEBUG ---"
echo "DB_CONNECTION is set to: '$DB_CONNECTION'"
echo "DB_HOST is set to: '$DB_HOST'"
echo "PORT is set to: '$PORT'"
echo "--- END DEBUG ---"

if [ -z "$DB_CONNECTION" ]; then
    if [ -n "$DATABASE_URL" ]; then
        echo "WARNING: DB_CONNECTION missing, but DATABASE_URL found. Defaulting to 'pgsql'."
        export DB_CONNECTION=pgsql
        export DB_URL="$DATABASE_URL"
    else
        echo "WARNING: DB_CONNECTION variable is missing and no DATABASE_URL found!"
        # We continue, as the user might be debugging unrelated issues, but this will likely fail later if DB is needed.
    fi
fi

echo "Configuring Apache to listen on port $PORT..."

# 1. Fix "Could not reliably determine the server's fully qualified domain name"
echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 2. Update ports.conf to Listen on 0.0.0.0:$PORT (Forces IPv4, fixes binding issues)
# Replaces "Listen 80" or "Listen <any_number>" with "Listen 0.0.0.0:$PORT"
sed -i "s/^Listen [0-9]\{1,\}.*/Listen 0.0.0.0:$PORT/g" /etc/apache2/ports.conf

# 3. Update Default VirtualHost to match the new port
# Replaces <VirtualHost *:80> with <VirtualHost *:$PORT>
sed -i "s/<VirtualHost \*:[0-9]\{1,\}>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf

# Verify Configuration changes in logs
echo "--- APACHE CONFIG CHECK ---"
echo ">> /etc/apache2/ports.conf:"
grep "Listen" /etc/apache2/ports.conf
echo ">> /etc/apache2/sites-available/000-default.conf (top):"
grep "VirtualHost" /etc/apache2/sites-available/000-default.conf | head -n 1
echo "--- END CONFIG CHECK ---"

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
