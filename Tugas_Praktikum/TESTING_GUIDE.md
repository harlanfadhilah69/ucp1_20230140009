# Panduan Testing API dengan Postman

## Persiapan

### 1. Import Collection ke Postman

Buat collection baru atau import dari file JSON.

### 2. Setup Environment Variables

Buat environment baru dengan variable:
- `base_url` = `http://localhost:8000`
- `token` = (akan diisi setelah login)

---

## Testing Steps

### STEP 1: Authentication - Login

**Method**: POST
**URL**: `{{base_url}}/api/auth/login`

**Headers**:
```
Content-Type: application/json
```

**Body** (raw JSON):
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Expected Response** (200 OK):
```json
{
    "message": "Login berhasil",
    "access_token": "1|abcdefg...",
    "token_type": "Bearer"
}
```

**Post-request Script** (save token):
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.environment.set("token", jsonData.access_token);
    console.log("Token saved:", jsonData.access_token);
}
```

---

### STEP 2: Category Management

#### 2.1 CREATE - Tambah Kategori

**Method**: POST
**URL**: `{{base_url}}/api/categories`

**Headers**:
```
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Body** (raw JSON):
```json
{
    "name": "Elektronik",
    "slug": "elektronik"
}
```

**Expected Response** (201 Created):
```json
{
    "message": "Kategori berhasil ditambahkan!",
    "data": {
        "id": 1,
        "name": "Elektronik",
        "slug": "elektronik",
        "created_at": "2026-05-08T10:30:00.000000Z",
        "updated_at": "2026-05-08T10:30:00.000000Z"
    }
}
```

---

#### 2.2 READ - Dapatkan Semua Kategori

**Method**: GET
**URL**: `{{base_url}}/api/categories?per_page=10`

**Headers**:
```
Content-Type: application/json
```

**Expected Response** (200 OK):
```json
{
    "message": "Daftar kategori berhasil diambil",
    "data": [
        {
            "id": 1,
            "name": "Elektronik",
            "slug": "elektronik",
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

#### 2.3 READ - Detail Kategori

**Method**: GET
**URL**: `{{base_url}}/api/categories/1`

**Expected Response** (200 OK):
```json
{
    "message": "Kategori berhasil diambil",
    "data": {
        "id": 1,
        "name": "Elektronik",
        "slug": "elektronik",
        "created_at": "2026-05-08T10:30:00.000000Z",
        "updated_at": "2026-05-08T10:30:00.000000Z",
        "products": [
            {
                "id": 1,
                "title": "Laptop Dell",
                "price": 10000000,
                "stock": 5
            }
        ]
    }
}
```

---

#### 2.4 UPDATE - Edit Kategori

**Method**: PUT
**URL**: `{{base_url}}/api/categories/1`

**Headers**:
```
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Body** (raw JSON):
```json
{
    "name": "Elektronik (Updated)",
    "slug": "elektronik-updated"
}
```

**Expected Response** (200 OK):
```json
{
    "message": "Kategori berhasil diupdate",
    "data": {
        "id": 1,
        "name": "Elektronik (Updated)",
        "slug": "elektronik-updated",
        "created_at": "2026-05-08T10:30:00.000000Z",
        "updated_at": "2026-05-08T10:40:00.000000Z"
    }
}
```

---

#### 2.5 DELETE - Hapus Kategori

**Method**: DELETE
**URL**: `{{base_url}}/api/categories/1`

**Headers**:
```
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Expected Response** (200 OK):
```json
{
    "message": "Kategori berhasil dihapus"
}
```

---

### STEP 3: Product Management

#### 3.1 CREATE - Tambah Produk

**Method**: POST
**URL**: `{{base_url}}/api/products`

**Headers**:
```
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Body** (raw JSON):
```json
{
    "title": "Laptop Dell XPS 13",
    "description": "Laptop high-end dengan prosesor Intel i7",
    "price": 12000000,
    "stock": 5,
    "category_id": 1
}
```

**Expected Response** (201 Created):
```json
{
    "message": "Produk berhasil ditambahkan!",
    "data": {
        "id": 1,
        "title": "Laptop Dell XPS 13",
        "description": "Laptop high-end dengan prosesor Intel i7",
        "price": 12000000,
        "stock": 5,
        "user_id": 1,
        "category_id": 1,
        "image": null,
        "category": {
            "id": 1,
            "name": "Elektronik"
        },
        "created_at": "2026-05-08T10:30:00.000000Z",
        "updated_at": "2026-05-08T10:30:00.000000Z"
    }
}
```

---

#### 3.2 READ - Dapatkan Semua Produk

**Method**: GET
**URL**: `{{base_url}}/api/products?per_page=10`

**Expected Response** (200 OK):
```json
{
    "message": "Daftar produk berhasil diambil",
    "data": [
        {
            "id": 1,
            "title": "Laptop Dell XPS 13",
            "description": "Laptop high-end dengan prosesor Intel i7",
            "price": 12000000,
            "stock": 5,
            "user_id": 1,
            "category_id": 1,
            "image": null,
            "category": {
                "id": 1,
                "name": "Elektronik"
            },
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

#### 3.3 READ - Detail Produk

**Method**: GET
**URL**: `{{base_url}}/api/products/1`

**Expected Response** (200 OK):
```json
{
    "message": "Produk berhasil diambil",
    "data": {
        "id": 1,
        "title": "Laptop Dell XPS 13",
        "description": "Laptop high-end dengan prosesor Intel i7",
        "price": 12000000,
        "stock": 5,
        "user_id": 1,
        "category_id": 1,
        "image": null,
        "category": {
            "id": 1,
            "name": "Elektronik"
        },
        "created_at": "2026-05-08T10:30:00.000000Z",
        "updated_at": "2026-05-08T10:30:00.000000Z"
    }
}
```

---

#### 3.4 UPDATE - Edit Produk

**Method**: PUT
**URL**: `{{base_url}}/api/products/1`

**Headers**:
```
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Body** (raw JSON):
```json
{
    "title": "Laptop Dell XPS 13 (Updated)",
    "description": "Laptop high-end dengan prosesor Intel i7 Gen 12",
    "price": 13000000,
    "stock": 3,
    "category_id": 1
}
```

**Expected Response** (200 OK):
```json
{
    "message": "Produk berhasil diupdate",
    "data": {
        "id": 1,
        "title": "Laptop Dell XPS 13 (Updated)",
        "description": "Laptop high-end dengan prosesor Intel i7 Gen 12",
        "price": 13000000,
        "stock": 3,
        "user_id": 1,
        "category_id": 1,
        "created_at": "2026-05-08T10:30:00.000000Z",
        "updated_at": "2026-05-08T10:40:00.000000Z"
    }
}
```

---

#### 3.5 DELETE - Hapus Produk

**Method**: DELETE
**URL**: `{{base_url}}/api/products/1`

**Headers**:
```
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Expected Response** (200 OK):
```json
{
    "message": "Produk berhasil dihapus"
}
```

---

### STEP 4: Authentication - Logout

**Method**: POST
**URL**: `{{base_url}}/api/auth/logout`

**Headers**:
```
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Expected Response** (200 OK):
```json
{
    "message": "Logout berhasil"
}
```

---

## Error Handling

### 401 - Unauthorized

**Request tanpa token atau token invalid**:
```json
{
    "message": "Unauthenticated."
}
```

### 404 - Not Found

**Resource tidak ditemukan**:
```json
{
    "message": "Produk tidak ditemukan"
}
```

### 422 - Validation Error

**Validation gagal**:
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "Judul produk wajib diisi."
        ],
        "price": [
            "Harga produk harus berupa angka yang valid."
        ]
    }
}
```

### 500 - Server Error

**Internal server error**:
```json
{
    "message": "Error saat menambah produk",
    "error": "Exception message..."
}
```

---

## Testing Checklist

- [ ] Login berhasil mendapatkan token
- [ ] Dapat membuat kategori baru
- [ ] Dapat melihat daftar kategori
- [ ] Dapat melihat detail kategori
- [ ] Dapat update kategori
- [ ] Dapat delete kategori
- [ ] Dapat membuat produk baru
- [ ] Dapat melihat daftar produk
- [ ] Dapat melihat detail produk
- [ ] Dapat update produk
- [ ] Dapat delete produk
- [ ] Logout berhasil dan token ter-revoke
- [ ] Error handling bekerja dengan baik

---

**Tips**:
- Simpan token dari login response ke environment variable
- Gunakan pre-request script untuk automation
- Test dengan berbagai kombinasi input
- Verifikasi response format sesuai dokumentasi

