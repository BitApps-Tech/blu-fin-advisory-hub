# MamoKacha Sweet Retreat - API Documentation

**Version**: 1.0.0  
**Base URL**: `http://localhost:8000/api/v1` (Development)  
**Production URL**: `https://api.yourdomain.com/api/v1`

---

## Table of Contents

1. [Authentication](#authentication)
2. [Public Endpoints](#public-endpoints)
3. [Admin Endpoints](#admin-endpoints)
4. [Error Handling](#error-handling)
5. [Rate Limiting](#rate-limiting)

---

## Authentication

### JWT Token Authentication

All admin endpoints require a Bearer token in the Authorization header.

**Login**

```http
POST /auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "Admin@12345"
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

**Using the Token**:
```http
Authorization: Bearer YOUR_TOKEN_HERE
```

**Refresh Token**:
```http
POST /auth/refresh
Authorization: Bearer YOUR_TOKEN_HERE
```

**Logout**:
```http
POST /auth/logout
Authorization: Bearer YOUR_TOKEN_HERE
```

**Get Current User**:
```http
GET /auth/me
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## Public Endpoints

No authentication required for these endpoints.

### Get Menu Categories (with nested items)

```http
GET /public/menu-categories
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Pastries",
      "slug": "pastries",
      "description": "Fresh baked pastries",
      "menu_items": [
        {
          "id": 1,
          "name": "Croissant",
          "slug": "croissant",
          "description": "Buttery and flaky",
          "price": 3.50,
          "is_special": false,
          "image": {
            "id": 1,
            "url": "http://localhost:8000/storage/uploads/2024/01/croissant.jpg",
            "thumb_url": "http://localhost:8000/storage/uploads/2024/01/croissant-thumb.jpg"
          }
        }
      ]
    }
  ]
}
```

### Get Menu Items

```http
GET /public/menu-items?category_id=1&is_special=1
```

**Query Parameters**:
- `category_id` (optional): Filter by category
- `is_special` (optional): Filter special items (0 or 1)

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Croissant",
      "price": 3.50,
      "is_special": false,
      "image": {...}
    }
  ]
}
```

### Get Specialties

```http
GET /public/specialties
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Wedding Cakes",
      "slug": "wedding-cakes",
      "excerpt": "Custom wedding cakes",
      "description": "Beautiful custom cakes for your special day",
      "image": {...}
    }
  ]
}
```

### Get Gallery

```http
GET /public/gallery
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Interior Shot",
      "caption": "Our cozy bakery interior",
      "image": {
        "id": 1,
        "url": "...",
        "thumb_url": "..."
      }
    }
  ]
}
```

### Submit Contact Message

```http
POST /public/contact
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "555-1234",
  "subject": "General Inquiry",
  "message": "I'd like to know more about..."
}
```

**Response**:
```json
{
  "success": true,
  "message": "Your message has been sent successfully"
}
```

**Rate Limit**: 5 requests per minute

### Submit Order

```http
POST /public/orders
Content-Type: application/json

{
  "customer_name": "John Doe",
  "phone": "555-1234",
  "email": "john@example.com",
  "order_type": "pickup",
  "scheduled_at": "2024-01-15 14:00:00",
  "note": "Please add extra frosting",
  "items": [
    {
      "menu_item_id": 1,
      "name": "Croissant",
      "qty": 2,
      "unit_price": 3.50
    },
    {
      "menu_item_id": 2,
      "name": "Chocolate Cake",
      "qty": 1,
      "unit_price": 25.00
    }
  ]
}
```

**Order Types**: `pickup`, `delivery`, `dinein`

**Response**:
```json
{
  "success": true,
  "message": "Order placed successfully",
  "data": {
    "code": "ORD-2024-00001",
    "order": {
      "id": 1,
      "code": "ORD-2024-00001",
      "status": "pending",
      "total": 32.00
    }
  }
}
```

**Rate Limit**: 10 requests per minute

### Get Public Settings

```http
GET /public/settings
```

**Response**:
```json
{
  "success": true,
  "data": {
    "site_name": "MamoKacha Sweet Retreat",
    "phone": "555-1234",
    "email": "info@example.com",
    "address": "123 Main St",
    "hours": "Mon-Fri: 8AM-6PM",
    "social": {
      "facebook": "https://facebook.com/...",
      "instagram": "https://instagram.com/..."
    }
  }
}
```

---

## Admin Endpoints

All require `Authorization: Bearer TOKEN` header.

### Menu Categories

**List Categories**:
```http
GET /menu-categories?search=pastry&per_page=15
```

**Create Category**:
```http
POST /menu-categories
Content-Type: application/json

{
  "name": "Cakes",
  "description": "Delicious cakes",
  "is_active": true,
  "order": 1
}
```

**Update Category**:
```http
PUT /menu-categories/{id}
Content-Type: application/json

{
  "name": "Updated Name",
  "is_active": false
}
```

**Delete Category**:
```http
DELETE /menu-categories/{id}
```

### Menu Items

**List Items**:
```http
GET /menu-items?search=cake&category_id=1&is_special=1
```

**Create Item**:
```http
POST /menu-items
Content-Type: application/json

{
  "category_id": 1,
  "name": "Chocolate Cake",
  "description": "Rich chocolate cake",
  "price": 25.99,
  "is_special": true,
  "is_active": true,
  "image_id": 5,
  "order": 1
}
```

**Update Item**:
```http
PUT /menu-items/{id}
```

**Delete Item** (soft delete):
```http
DELETE /menu-items/{id}
```

### Specialties

**List Specialties**:
```http
GET /specialties?search=wedding
```

**Create Specialty**:
```http
POST /specialties
Content-Type: application/json

{
  "title": "Wedding Cakes",
  "excerpt": "Custom wedding cakes",
  "description": "Full description...",
  "image_id": 10,
  "is_active": true,
  "order": 1
}
```

**Update Specialty**:
```http
PUT /specialties/{id}
```

**Delete Specialty** (soft delete):
```http
DELETE /specialties/{id}
```

### Gallery

**List Gallery Items**:
```http
GET /gallery?search=interior
```

**Create Gallery Item**:
```http
POST /gallery
Content-Type: application/json

{
  "title": "Interior Shot",
  "caption": "Our cozy bakery",
  "image_id": 15,
  "is_active": true,
  "order": 1
}
```

**Update Gallery Item**:
```http
PUT /gallery/{id}
```

**Delete Gallery Item** (soft delete):
```http
DELETE /gallery/{id}
```

### Media

**List Media**:
```http
GET /media?search=cake&page=1&per_page=24
```

**Upload Media**:
```http
POST /media
Content-Type: multipart/form-data

file: [binary image data]
title: "Chocolate Cake"
alt: "Delicious chocolate cake"
```

**Update Media Metadata**:
```http
PUT /media/{id}
Content-Type: application/json

{
  "title": "Updated Title",
  "alt": "Updated alt text"
}
```

**Delete Media**:
```http
DELETE /media/{id}
```

### Orders

**List Orders**:
```http
GET /orders?search=ORD-2024&status=pending&per_page=10
```

**Get Order Detail**:
```http
GET /orders/{id}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "code": "ORD-2024-00001",
    "customer_name": "John Doe",
    "phone": "555-1234",
    "email": "john@example.com",
    "order_type": "pickup",
    "status": "pending",
    "scheduled_at": "2024-01-15 14:00:00",
    "note": "Extra frosting",
    "total": 32.00,
    "created_at": "2024-01-10 10:30:00",
    "items": [
      {
        "id": 1,
        "name": "Croissant",
        "qty": 2,
        "unit_price": 3.50,
        "total": 7.00
      }
    ]
  }
}
```

**Update Order Status**:
```http
PUT /orders/{id}/status
Content-Type: application/json

{
  "status": "confirmed"
}
```

**Order Statuses**: `pending`, `confirmed`, `preparing`, `ready`, `completed`, `cancelled`

**Export Orders (CSV)**:
```http
GET /orders/export?status=completed&from=2024-01-01&to=2024-01-31
```

### Messages

**List Messages**:
```http
GET /messages?search=john&page=1
```

**Get Message Detail**:
```http
GET /messages/{id}
```

**Mark as Read**:
```http
POST /messages/{id}/read
```

**Delete Message**:
```http
DELETE /messages/{id}
```

### Settings

**Get All Settings**:
```http
GET /settings
```

**Get Settings by Group**:
```http
GET /settings/group/site
GET /settings/group/seo
GET /settings/group/social
GET /settings/group/orders
```

**Update Settings**:
```http
PUT /settings
Content-Type: application/json

{
  "site_name": "MamoKacha Sweet Retreat",
  "site_tagline": "Fresh pastries daily",
  "phone": "555-1234",
  "email": "info@example.com"
}
```

### Users

**List Users**:
```http
GET /users?search=john
```

**Create User** (SuperAdmin only):
```http
POST /auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePassword123",
  "password_confirmation": "SecurePassword123"
}
```

**Get User Abilities**:
```http
GET /me/abilities
```

**Response**:
```json
{
  "success": true,
  "data": {
    "roles": ["SuperAdmin"],
    "permissions": [
      "menus.view",
      "menus.create",
      "menus.update",
      "menus.delete",
      ...
    ]
  }
}
```

---

## Error Handling

### Standard Error Response

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": [
      "Validation error message"
    ]
  }
}
```

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized (invalid/missing token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests (rate limit exceeded)
- `500` - Internal Server Error

### Common Errors

**Unauthorized**:
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

**Validation Error**:
```json
{
  "success": false,
  "message": "The given data was invalid",
  "errors": {
    "email": ["The email field is required."],
    "price": ["The price must be a number."]
  }
}
```

**Forbidden**:
```json
{
  "success": false,
  "message": "This action is unauthorized"
}
```

---

## Rate Limiting

### Limits by Endpoint Group

| Endpoint Group | Limit | Window |
|---------------|-------|--------|
| Public Endpoints (GET) | 60 requests | 1 minute |
| Contact Form | 5 requests | 1 minute |
| Order Submission | 10 requests | 1 minute |
| Admin Endpoints | 60 requests | 1 minute |
| Auth Endpoints | 10 requests | 1 minute |

### Rate Limit Headers

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1610000000
```

### Rate Limit Exceeded Response

```json
{
  "success": false,
  "message": "Too Many Attempts. Please try again later."
}
```

---

## Pagination

All list endpoints support pagination.

**Query Parameters**:
- `page` (default: 1)
- `per_page` (default: 15, max: 100)

**Response Structure**:
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75,
    "from": 1,
    "to": 15
  },
  "links": {
    "first": "http://api.example.com/v1/menu-items?page=1",
    "last": "http://api.example.com/v1/menu-items?page=5",
    "prev": null,
    "next": "http://api.example.com/v1/menu-items?page=2"
  }
}
```

---

## File Uploads

### Media Upload

**Endpoint**: `POST /media`

**Content-Type**: `multipart/form-data`

**Fields**:
- `file` (required): Image file (JPEG, PNG, GIF, WebP)
- `title` (optional): Image title
- `alt` (optional): Alt text for accessibility

**Max File Size**: 10MB

**Accepted Types**: `image/jpeg`, `image/png`, `image/gif`, `image/webp`

**Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "url": "http://localhost:8000/storage/uploads/2024/01/image.jpg",
    "thumb_url": "http://localhost:8000/storage/uploads/2024/01/image-thumb.jpg",
    "title": "Chocolate Cake",
    "alt": "Delicious chocolate cake",
    "mime": "image/jpeg",
    "size": 245678,
    "width": 1200,
    "height": 800
  }
}
```

---

## Testing

### Postman Collection

Import the Postman collection from `api/postman_collection.json`

### Example Requests (cURL)

**Login**:
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"Admin@12345"}'
```

**Get Menu Categories (Public)**:
```bash
curl http://localhost:8000/api/v1/public/menu-categories
```

**Create Menu Item (Admin)**:
```bash
curl -X POST http://localhost:8000/api/v1/menu-items \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"category_id":1,"name":"Croissant","price":3.50}'
```

**Upload Image**:
```bash
curl -X POST http://localhost:8000/api/v1/media \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "file=@/path/to/image.jpg" \
  -F "title=Test Image"
```

---

## CORS Configuration

Configured to allow requests from:
- Admin URL (defined in `.env` as `ADMIN_URL`)
- Frontend URL (defined in `.env` as `FRONTEND_URL`)

**Allowed Headers**:
- `Authorization`, `Content-Type`, `Accept`

**Exposed Headers**:
- `Authorization`

---

## Versioning

Current API version: **v1**

All endpoints are prefixed with `/api/v1/`

Future versions will be released as `/api/v2/`, etc.

---

## Support

For issues or questions:
- Email: support@yourdomain.com
- GitHub: https://github.com/yourrepo

---

**Last Updated**: November 3, 2025  
**API Version**: 1.0.0  
**Laravel Version**: 8.x
