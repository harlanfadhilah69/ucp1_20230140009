# Quick Start Guide - API Praktikum

## 🚀 Setup Cepat

### 1. Database Setup (Pastikan MySQL running)

```bash
# Navigate ke project folder
cd c:\xampp\htdocs\pertemuan-1

# Run migrations
php artisan migrate

# (Optional) Seed data
php artisan db:seed
```

### 2. Start Development Server

```bash
# Terminal 1 - Start Laravel server
php artisan serve

# Server akan berjalan di http://localhost:8000
```

---

## 🧪 Testing Cepat dengan Postman

### Method 1: Import Collection JSON

1. Buka Postman
2. Click "Import"
3. Upload file: `Tugas_Praktikum/Postman_Collection.json`
4. Environment akan otomatis ter-setup dengan variables `base_url` dan `token`

### Method 2: Manual Setup

#### Setup Environment
1. Create new environment: "Laravel API"
2. Add variables:
   - `base_url` = `http://localhost:8000`
   - `token` = (empty, akan diisi saat login)

#### Create Requests

**1. Login**
```
POST http://localhost:8000/api/auth/login
Body (JSON):
{
    "email": "admin@example.com",
    "password": "password"
}
```

**2. Create Category**
```
POST http://localhost:8000/api/categories
Headers: Authorization: Bearer {{token}}
Body (JSON):
{
    "name": "Electronics",
    "slug": "electronics"
}
```

**3. Create Product**
```
POST http://localhost:8000/api/products
Headers: Authorization: Bearer {{token}}
Body (JSON):
{
    "title": "Laptop Dell",
    "description": "High-end laptop",
    "price": 10000000,
    "stock": 5,
    "category_id": 1
}
```

**4. Get All Products**
```
GET http://localhost:8000/api/products?per_page=10
```

**5. Get Product Detail**
```
GET http://localhost:8000/api/products/1
```

---

## 📄 Dokumentasi Lengkap

Baca dokumentasi detail di:
- **API_Praktikum.md** - Penjelasan API lengkap
- **TESTING_GUIDE.md** - Step-by-step testing
- **SUMMARY.md** - Ringkasan implementasi

---

## 📁 File-File Penting

### Code Files
- `app/Http/Controllers/Api/AuthController.php`
- `app/Http/Controllers/Api/ProductController.php`
- `app/Http/Controllers/Api/CategoryController.php`
- `app/Http/Requests/StoreCategoryRequest.php`
- `routes/api.php`

### Documentation Files
- `Tugas_Praktikum/API_Praktikum.md`
- `Tugas_Praktikum/TESTING_GUIDE.md`
- `Tugas_Praktikum/SUMMARY.md`
- `Tugas_Praktikum/Postman_Collection.json`
- `Tugas_Praktikum/QUICKSTART.md` (file ini)

---

## ✅ Endpoint Quick Reference

### Public Endpoints (Tidak perlu token)
```
GET  /api/products              # List semua produk
GET  /api/products/{id}         # Detail produk
GET  /api/categories            # List semua kategori
GET  /api/categories/{id}       # Detail kategori
POST /api/auth/login            # Login & dapatkan token
```

### Authenticated Endpoints (Perlu token)
```
POST   /api/products            # Tambah produk
PUT    /api/products/{id}       # Edit produk
DELETE /api/products/{id}       # Hapus produk

POST   /api/categories          # Tambah kategori
PUT    /api/categories/{id}     # Edit kategori
DELETE /api/categories/{id}     # Hapus kategori

POST   /api/auth/logout         # Logout
```

---

## 🔑 Cara Menggunakan Token

### 1. Login untuk dapatkan token
```bash
POST /api/auth/login
{
    "email": "admin@example.com",
    "password": "password"
}

Response:
{
    "access_token": "1|abcdef123456...",
    "token_type": "Bearer"
}
```

### 2. Gunakan token di request yang memerlukan auth
```bash
Authorization: Bearer 1|abcdef123456...
```

### 3. Logout untuk revoke token
```bash
POST /api/auth/logout
Authorization: Bearer 1|abcdef123456...
```

---

## 🐛 Troubleshooting

### Error: "Unauthenticated"
- Pastikan sudah login dulu
- Pastikan token valid (belum expired/revoked)
- Cek header: `Authorization: Bearer {token}`

### Error: "Database connection refused"
- Pastikan MySQL sudah running
- Cek konfigurasi `.env` file
- Pastikan `DB_DATABASE` sesuai dengan nama database yang ada

### Error: "Table not found"
- Jalankan `php artisan migrate`
- Pastikan tidak ada error saat migrasi

### Error: "Validation failed"
- Cek request body sesuai dokumentasi
- Lihat error message untuk field mana yang salah

---

## 💡 Tips

1. **Save Token ke Postman**
   - Gunakan "Tests" tab untuk auto-save token:
   ```javascript
   var jsonData = pm.response.json();
   pm.environment.set("token", jsonData.access_token);
   ```

2. **Use {{variable}} syntax**
   - `{{base_url}}` untuk base URL
   - `{{token}}` untuk bearer token

3. **Check Response Body**
   - Selalu lihat response untuk debug
   - Error message memberikan informasi lengkap

4. **Pagination**
   - Default per_page = 10
   - Bisa override dengan: `?per_page=20`

---

## 🎯 Testing Checklist

Pastikan test semua dengan urutan ini:

- [ ] **Login** - Dapatkan token
- [ ] **Category CRUD**
  - [ ] POST /api/categories (create)
  - [ ] GET /api/categories (list)
  - [ ] GET /api/categories/1 (detail)
  - [ ] PUT /api/categories/1 (update)
  - [ ] DELETE /api/categories/1 (delete)
- [ ] **Product CRUD**
  - [ ] POST /api/products (create)
  - [ ] GET /api/products (list)
  - [ ] GET /api/products/1 (detail)
  - [ ] PUT /api/products/1 (update)
  - [ ] DELETE /api/products/1 (delete)
- [ ] **Logout** - Revoke token

---

## 📚 Resources

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [RESTful API Design](https://restfulapi.net/)
- [HTTP Status Codes](https://httpwg.org/specs/rfc7231.html#status.codes)

---

## ❓ Pertanyaan Umum

**Q: Apakah saya perlu setup Scramble?**  
A: Tidak, kami skip Scramble karena package tidak tersedia. API documentation sudah lengkap di file markdown.

**Q: Bagaimana jika database belum punya data?**  
A: Jalankan `php artisan db:seed` untuk seed data default.

**Q: Apakah token tidak pernah expire?**  
A: Default Sanctum tidak ada expiration. Untuk production, set di `config/sanctum.php`.

**Q: Apakah bisa test dari frontend JavaScript?**  
A: Ya, tapi perlu setup CORS di `config/cors.php` dulu.

**Q: Bagaimana log untuk audit?**  
A: Semua operations sudah tercatat di `storage/logs/laravel.log`.

---

## 🎓 Learning Path

1. Baca **Quick Start ini** untuk overview
2. Baca **API_Praktikum.md** untuk detail API
3. Baca **TESTING_GUIDE.md** untuk testing
4. Lihat **Source Code** di Controllers
5. Coba test dengan Postman
6. Baca **SUMMARY.md** untuk final review

---

**Status**: ✅ Siap Testing  
**Updated**: 8 Mei 2026

Selamat mencoba! 🚀
