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

echo "Configuring Application..."

# Run system requirements
echo "Linking storage..."
php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP Built-in Server (Temporary Debugging)
echo "Starting PHP Built-in Server on 0.0.0.0:$PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
