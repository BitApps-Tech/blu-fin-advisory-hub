@echo off
echo ================================
echo Seeding Database with Sample Data
echo ================================
echo.

echo Running migrations...
php artisan migrate:fresh
echo.

echo Seeding all data...
php artisan db:seed
echo.

echo ================================
echo Database seeded successfully!
echo ================================
echo.
echo Login Credentials:
echo Email: admin@example.com
echo Password: Admin@12345
echo.
echo Sample data created:
echo - 3 Menu Categories
echo - 6 Menu Items
echo - 2 Specialties
echo - 2 Gallery Items
echo - 2 Orders
echo - 3 Contact Messages
echo - 13 Settings
echo - 4 Media Items
echo.
pause

