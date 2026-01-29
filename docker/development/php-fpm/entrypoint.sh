#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! nc -z mysql 3306; do
    sleep 1
done
echo "MySQL is ready!"

# Set permissions
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
chmod -R 775 /var/www/bootstrap/cache

# Install dependencies if vendor folder doesn't exist
if [ ! -d "vendor" ]; then
    echo "Installing PHP dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# Generate application key if not set
if grep -q "APP_KEY=base64:" .env; then
    echo "Application key already set."
else
    echo "Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear cache
echo "Clearing cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Execute the command
exec "$@"