# Praktikum: API Authentication & Documentation dengan Laravel Sanctum

## Daftar Isi
1. [Setup & Instalasi](#setup--instalasi)
2. [API Endpoints](#api-endpoints)
3. [Contoh Penggunaan API](#contoh-penggunaan-api)
4. [Testing API](#testing-api)

---

## Setup & Instalasi

### Tahap 1: Tambahkan HasApiTokens pada Model User

File: `app/Models/User.php`

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    // ...
}
```

**Status**: ✅ Sudah diimplementasikan

---

### Tahap 2: Install Laravel Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force
php artisan migrate
```

**Status**: ✅ Sudah diimplementasikan

---

### Tahap 3: Update AppServiceProvider

File: `app/Providers/AppServiceProvider.php`

```php
use Illuminate\Support\Facades\Gate;

public function boot(): void
{
    // ... existing code ...
    
    // API Documentation Gate
    Gate::define('viewApiDocs', function () {
        return true;
    });
}
```

**Status**: ✅ Sudah diimplementasikan

---

## Struktur API

### 1. Authentication API

#### Login - Dapatkan Access Token

**Endpoint**: `POST /api/auth/login`

**Request Body**:
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response Success (200)**:
```json
{
    "message": "Login berhasil",
    "access_token": "1|sometoken...",
    "token_type": "Bearer"
}
```

**Response Error (401)**:
```json
{
    "message": "Email atau password salah"
}
```

---

#### Logout - Revoke Token

**Endpoint**: `POST /api/auth/logout`

**Headers**:
```
Authorization: Bearer {access_token}
```

**Response (200)**:
```json
{
    "message": "Logout berhasil"
}
```

---

### 2. Product API

#### GET - Daftar Semua Produk

**Endpoint**: `GET /api/products?per_page=10`

**Response (200)**:
```json
{
    "message": "Daftar produk berhasil diambil",
    "data": [
        {
            "id": 1,
            "name": "Laptop",
            "quantity": 5,
            "price": 10000000,
            "user_id": 1,
            "category_id": 1,
            "category": {
                "id": 1,
                "name": "Electronics"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total": 1,
        "last_page": 1
    }
}
```

---

#### GET - Detail Produk

**Endpoint**: `GET /api/products/{id}`

**Response (200)**:
```json
{
    "message": "Produk berhasil diambil",
    "data": {
        "id": 1,
        "name": "Laptop",
        "quantity": 5,
        "price": 10000000,
        "user_id": 1,
        "category_id": 1,
        "category": {
            "id": 1,
            "name": "Electronics"
        }
    }
}
```

**Response (404)**:
```json
{
    "message": "Produk tidak ditemukan"
}
```

---

#### POST - Tambah Produk (Require Auth)

**Endpoint**: `POST /api/products`

**Headers**:
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "Laptop Dell",
    "quantity": 5,
    "price": 10000000,
    "category_id": 1
}
```

**Response (201)**:
```json
{
    "message": "Produk berhasil ditambahkan!",
    "data": {
        "id": 1,
        "name": "Laptop Dell",
        "quantity": 5,
        "price": 10000000,
        "user_id": 1,
        "category_id": 1,
        "category": {
            "id": 1,
            "name": "Electronics"
        }
    }
}
```

---

#### PUT - Update Produk (Require Auth)

**Endpoint**: `PUT /api/products/{id}`

**Headers**:
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "Laptop Dell XPS",
    "quantity": 3,
    "price": 12000000,
    "category_id": 1
}
```

**Response (200)**:
```json
{
    "message": "Produk berhasil diupdate",
    "data": {
        "id": 1,
        "name": "Laptop Dell XPS",
        "quantity": 3,
        "price": 12000000,
        "user_id": 1,
        "category_id": 1
    }
}
```

---

#### DELETE - Hapus Produk (Require Auth)

**Endpoint**: `DELETE /api/products/{id}`

**Headers**:
```
Authorization: Bearer {access_token}
```

**Response (200)**:
```json
{
    "message": "Produk berhasil dihapus"
}
```

---

### 3. Category API

#### GET - Daftar Semua Kategori

**Endpoint**: `GET /api/categories?per_page=10`

**Response (200)**:
```json
{
    "message": "Daftar kategori berhasil diambil",
    "data": [
        {
            "id": 1,
            "name": "Electronics",
            "slug": "electronics",
            "created_at": "2026-05-08T10:30:00.000000Z",
            "updated_at": "2026-05-08T10:30:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total": 1,
        "last_page": 1
    }
}
```

---

#### GET - Detail Kategori

**Endpoint**: `GET /api/categories/{id}`

**Response (200)**:
```json
{
    "message": "Kategori berhasil diambil",
    "data": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics",
        "products": [
            {
                "id": 1,
                "name": "Laptop",
                "quantity": 5,
                "price": 10000000
            }
        ]
    }
}
```

---

#### POST - Tambah Kategori (Require Auth)

**Endpoint**: `POST /api/categories`

**Headers**:
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "Electronics",
    "slug": "electronics"
}
```

**Response (201)**:
```json
{
    "message": "Kategori berhasil ditambahkan!",
    "data": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics"
    }
}
```

---

#### PUT - Update Kategori (Require Auth)

**Endpoint**: `PUT /api/categories/{id}`

**Headers**:
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "Elektronik",
    "slug": "elektronik"
}
```

**Response (200)**:
```json
{
    "message": "Kategori berhasil diupdate",
    "data": {
        "id": 1,
        "name": "Elektronik",
        "slug": "elektronik"
    }
}
```

---

#### DELETE - Hapus Kategori (Require Auth)

**Endpoint**: `DELETE /api/categories/{id}`

**Headers**:
```
Authorization: Bearer {access_token}
```

**Response (200)**:
```json
{
    "message": "Kategori berhasil dihapus"
}
```

---

## Contoh Penggunaan API

### Menggunakan cURL

#### 1. Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

#### 2. Tambah Kategori (dengan token)
```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Electronics",
    "slug": "electronics"
  }'
```

#### 3. Tambah Produk (dengan token)
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Dell",
    "quantity": 5,
    "price": 10000000,
    "category_id": 1
  }'
```

#### 4. Dapatkan Daftar Produk
```bash
curl -X GET http://localhost:8000/api/products?per_page=10
```

---

## Testing API

### Menggunakan Postman

1. **Setup Collection**
   - Buat collection baru "Laravel API"
   - Add variable `base_url` = `http://localhost:8000`
   - Add variable `token` = (akan diisi setelah login)

2. **Login Request**
   - Method: POST
   - URL: `{{base_url}}/api/auth/login`
   - Body (JSON):
     ```json
     {
       "email": "admin@example.com",
       "password": "password"
     }
     ```
   - Pre-request Script:
     ```javascript
     // Simpan token
     var jsonData = pm.response.json();
     pm.environment.set("token", jsonData.access_token);
     ```

3. **Authenticated Request**
   - Add Header: `Authorization: Bearer {{token}}`

---

## File-File yang Dibuat/Dimodifikasi

### Controllers
- ✅ `app/Http/Controllers/Api/AuthController.php` - Authentication
- ✅ `app/Http/Controllers/Api/ProductController.php` - Product CRUD
- ✅ `app/Http/Controllers/Api/CategoryController.php` - Category CRUD

### Requests (Validation)
- ✅ `app/Http/Requests/StoreProductRequest.php` - Product validation
- ✅ `app/Http/Requests/StoreCategoryRequest.php` - Category validation

### Routes
- ✅ `routes/api.php` - API routes configuration

### Models
- ✅ `app/Models/User.php` - HasApiTokens trait ditambahkan

### Configuration
- ✅ `app/Providers/AppServiceProvider.php` - Gate untuk API docs
- ✅ `config/sanctum.php` - Sanctum configuration (auto-published)

---

## Keamanan

### Best Practices yang Diterapkan

1. **Authentication**
   - Token-based authentication menggunakan Laravel Sanctum
   - Password validation pada login

2. **Authorization**
   - Middleware `auth:sanctum` untuk protected routes
   - Automatic user_id assignment dari authenticated user

3. **Validation**
   - Input validation menggunakan Form Request classes
   - Custom error messages untuk user experience

4. **Logging**
   - Semua aksi dicatat dalam log
   - Error handling dengan try-catch

---

## Troubleshooting

### Database Connection Error
Pastikan MySQL server berjalan dan konfigurasi `.env` benar:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_pertemuan3
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration:
```bash
php artisan migrate
```

### Token Invalid
- Pastikan menggunakan token terbaru dari login
- Cek header: `Authorization: Bearer {token}`
- Token case-sensitive

### CORS Issues
Jika mengakses dari frontend, pastikan CORS sudah dikonfigurasi di `config/cors.php`

---

## Catatan Pengembangan

- Semua endpoint sudah siap untuk production
- Error handling sudah comprehensive
- Logging sudah terintegrasi
- Pagination sudah di-support untuk list endpoints
- Relationship (category, products) sudah di-load

---

**Dibuat**: 8 Mei 2026
**Status**: Selesai dan Siap Testing
