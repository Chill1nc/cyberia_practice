#!/bin/sh
set -e

# Clear configurations to avoid caching issues in development when dependencies exist
if [ -f /var/www/vendor/autoload.php ]; then
	echo "Clearing configurations..."
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
else
	echo "Skipping artisan cache clear: vendor/autoload.php not found"
fi

# Run the default command (e.g., php-fpm or bash)
exec "$@"