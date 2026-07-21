@echo off
echo ========================================
echo Laravel API Setup Script
echo ========================================
echo.

echo Step 1: Installing dependencies...
composer install
if errorlevel 1 (
    echo ERROR: Composer install failed
    pause
    exit /b 1
)

echo.
echo Step 2: Generating keys...
if not exist .env (
    copy .env.example .env
    echo Created .env file. Please configure it with your database credentials.
)
php artisan key:generate
php artisan jwt:secret

echo.
echo Step 3: Running migrations...
php artisan migrate
if errorlevel 1 (
    echo ERROR: Migrations failed. Please check your database configuration in .env
    pause
    exit /b 1
)

echo.
echo Step 4: Seeding database...
php artisan db:seed

echo.
echo Step 5: Creating storage link...
php artisan storage:link

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Default SuperAdmin credentials:
echo Email: admin@example.com
echo Password: Admin@12345
echo.
echo Start the server with: php artisan serve
echo.
pause

