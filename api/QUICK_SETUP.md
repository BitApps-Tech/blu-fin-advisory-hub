# Quick Setup Guide - 3 Steps

## Prerequisites

Make sure you have:
- PHP 7.4+ installed
- Composer installed
- MySQL/MariaDB running
- Database `MamoKacha` created

## Step 1: Install Dependencies

```bash
cd api
composer install
```

This installs all Laravel packages and dependencies.

**Expected output**: Packages will be downloaded and installed.

## Step 2: Run Migrations & Seed Data

First, ensure your `.env` file is configured:

```env
DB_DATABASE=MamoKacha
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run:

```bash
# Generate application key (if not already done)
php artisan key:generate

# Generate JWT secret (if not already done)
php artisan jwt:secret

# Run migrations
php artisan migrate

# Seed roles, permissions, and admin user
php artisan db:seed

# Create storage link for file uploads
php artisan storage:link
```

**Expected output**: 
- All tables created successfully
- Roles and permissions seeded
- SuperAdmin user created: `admin@example.com` / `Admin@12345`

## Step 3: Test the API

```bash
# Start the Laravel server
php artisan serve
```

In another terminal (or Postman), test the endpoints:

### Test 1: Health Check
```bash
curl http://localhost:8000/api/v1/health
```

Expected response:
```json
{
    "status": "ok",
    "message": "API is running",
    "timestamp": "2024-..."
}
```

### Test 2: Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@example.com\",\"password\":\"Admin@12345\"}"
```

Expected response:
```json
{
    "success": true,
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
        "token_type": "bearer",
        "expires_in": 3600,
        "user": {...},
        "roles": ["SuperAdmin"],
        "permissions": [...]
    }
}
```

### Test 3: Get Menu Categories (Protected)
```bash
# Use the token from login response
curl -X GET http://localhost:8000/api/v1/menu-categories \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Alternative: Use the Batch Script (Windows)

Simply run:
```bash
cd api
run-setup.bat
```

This will execute all steps automatically.

## Troubleshooting

### Issue: "No such file or directory" for vendor/autoload.php
**Solution**: Run `composer install` first

### Issue: "Access denied for user"
**Solution**: Check database credentials in `api/.env`

### Issue: "Database MamoKacha doesn't exist"
**Solution**: Create the database:
```sql
CREATE DATABASE MamoKacha;
```

### Issue: "JWT secret not set"
**Solution**: Run `php artisan jwt:secret`

### Issue: "APP_KEY not set"
**Solution**: Run `php artisan key:generate`

## Next Steps After Setup

Once everything is working:
1. ✅ Test all endpoints with Postman or curl
2. ✅ Proceed to Prompt 5 (Media upload endpoints)
3. ✅ Continue with remaining prompts

All done! 🎉

