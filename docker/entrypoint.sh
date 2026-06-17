#!/bin/sh
set -e

# Wait for database connection
php -r '
$host = getenv("DB_HOST") ?: "127.0.0.1";
$port = getenv("DB_PORT") ?: 3306;
$db   = getenv("DB_DATABASE") ?: "laravel";
$user = getenv("DB_USERNAME") ?: "root";
$pass = getenv("DB_PASSWORD") ?: "";

$max_attempts = 30;
$attempts = 0;

echo "Checking database connection on $host:$port...\n";

while ($attempts < $max_attempts) {
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
        echo "Database is ready!\n";
        exit(0);
    } catch (PDOException $e) {
        echo "Database not ready yet. Retrying in 2 seconds...\n";
        sleep(2);
        $attempts++;
    }
}
echo "Error: Database connection timed out after $max_attempts attempts.\n";
exit(1);
'

# Set permissions for storage and cache
echo "Setting permissions..."
chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# Run storage link if not already done
echo "Linking storage..."
php artisan storage:link --force

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Run database seeder
echo "Running seeder..."
php artisan db:seed --class=DatabaseSeeder

# Cache Laravel configurations
echo "Caching configurations, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
