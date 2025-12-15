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

# 2. STRATEGY CHANGE: Force IPv4 Only
# Our internal tests showed that IPv6 ([::1]) connection was REFUSED, while IPv4 (127.0.0.1) worked.
# Railway might be trying to hit IPv6 if we advertise it. We will strictly force IPv4.
echo "Listen 0.0.0.0:$PORT" > /etc/apache2/ports.conf

# 3. Update Default VirtualHost to match the new port
# Replaces <VirtualHost *:80> or any other port with <VirtualHost *:$PORT>
sed -i "s/<VirtualHost \*:[0-9]\{1,\}>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf

# Verify Configuration changes in logs
echo "--- APACHE CONFIG CHECK ---"
echo ">> /etc/apache2/ports.conf:"
cat /etc/apache2/ports.conf
echo ">> /etc/apache2/sites-available/000-default.conf ("VirtualHost" line):"
grep "VirtualHost" /etc/apache2/sites-available/000-default.conf
echo "--- END CONFIG CHECK ---"

# Run system requirements
echo "Fixing permissions..."
# Ensure log file exists and has correct permissions
touch /var/www/html/storage/logs/laravel.log
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Linking storage..."
php artisan storage:link || true

# CHANGED: Use standard migrate --force to PRESERVE DATA. 
# NEVER use migrate:fresh in production entrypoint!
php artisan migrate --force

# Note: We removed the separate 'db:seed' command because 'migrate:fresh --seed' does both.

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
