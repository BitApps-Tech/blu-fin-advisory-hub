# JWT Configuration Setup

After installing `tymon/jwt-auth`, you need to configure JWT settings.

## Step 1: Publish JWT Config

```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

## Step 2: Generate JWT Secret

```bash
php artisan jwt:secret
```

This will generate a random secret key and add it to your `.env` file as `JWT_SECRET`.

## Step 3: Update config/jwt.php

After publishing, update `api/config/jwt.php` with these recommended settings:

```php
'ttl' => env('JWT_TTL', 60), // Token lifetime in minutes (default: 60)
'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Refresh token lifetime in minutes (default: 2 weeks)
```

## Step 4: Update .env

Make sure your `.env` includes:

```env
JWT_SECRET=<generated-by-artisan>
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

## Verification

After setup, you can test JWT auth:

```bash
# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"Admin@12345"}'

# Use the token from response
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

