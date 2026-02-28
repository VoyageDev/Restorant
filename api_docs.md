# Restorant API Documentation (v1)

## Quick Start for Android Studio

To integrate this API with Android:

1. **Base URL Configuration**
    - Development: `http://localhost:8000/api/v1`
    - Replace `localhost` with server IP when moving to production

2. **HTTP Library**
    - Use Retrofit2, OkHttp, or Volley for API calls
    - Recommended: Retrofit2 with Moshi/Gson for JSON serialization

3. **Authentication Flow**
    - POST `/auth/login` to get token
    - Store token in SharedPreferences or secure storage
    - Add header: `Authorization: Bearer <token>` to all requests
    - Handle 401 responses by redirecting to login

4. **Error Handling**
    - Always check response status code
    - Display `message` field to users
    - For 422 errors, typically validation failed - show field errors

5. **Common Tips**
    - All timestamps are ISO 8601 format (UTC)
    - Numeric fields like price are strings in some endpoints - parse as needed
    - Use pagination for list endpoints (current_page, total, per_page, etc.)
    - All prices include 10% tax calculation in final amount

---

Base URL

- Local: http://localhost:8000/api/v1
- All endpoints return JSON when request has `Accept: application/json`.
- All responses include appropriate HTTP status codes (200, 201, 400, 401, 403, 409, 422, 500).

Authentication

- Login returns a Sanctum token.
- Send token via header: `Authorization: Bearer <token>`.
- Protected endpoints return 401 if token is missing/invalid.
- Inactive accounts return 403 on login.

Common Response Headers

- `Content-Type: application/json`
- `Accept: application/json` (in requests)

Error Response Format

```json
{
    "message": "Error description here"
}
```

Common HTTP Status Codes

- **200 OK** - Request successful
- **201 Created** - Resource created successfully
- **400 Bad Request** - Client error (e.g., empty items in order)
- **401 Unauthorized** - Missing/invalid token
- **403 Forbidden** - Account inactive
- **409 Conflict** - Cannot delete (e.g., kategori still has menu)
- **422 Unprocessable Entity** - Validation failed
- **500 Server Error** - Server-side issue

Field Notes

- `price` fields are returned as strings (e.g., "25000")
- `status` enums for Meja: ['Tersedia', 'Terisi', 'Reserved']
- `status` enums for Menu: ['Tersedia', 'Habis']
- `status` enums for Reservasi: ['Pending', 'Selesai', 'Dibatalkan']
- `status` enums for User: ['active', 'inactive']
- `status` enums for Transaction: ['ordered', 'paid']
- `order_type` enums: ['dine_in', 'take_away']
- `jabatan` enums: ['Kasir', 'Waiter', 'Koki', 'Manajer']
- `shift` enums: ['Pagi', 'Malam']
- `Role` enums for User: ['Owner', 'Manajer', 'Kasir']
- Tax (pajak) is always 10% of subtotal
- Daily stock decreases with each order; when daily_stock reaches 0, menu status becomes 'Habis'

---

## Auth

### POST /auth/login

Request body

```json
{
    "email": "user@example.com",
    "password": "secret"
}
```

Response 200

```json
{
    "message": "Login berhasil.",
    "token": "plain-text-token",
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "user@example.com",
        "Role": "Kasir",
        "status": "active"
    }
}
```

Response 403 (inactive)

```json
{ "message": "Status akun tidak aktif." }
```

Response 422 (invalid credentials)

```json
{ "message": "Email atau password tidak valid." }
```

### POST /auth/logout

Auth required.
Response 200

```json
{ "message": "Logout berhasil." }
```

### GET /auth/me

Auth required.
Response 200

```json
{
    "data": {
        "id": 1,
        "name": "Admin",
        "email": "user@example.com",
        "Role": "Kasir",
        "status": "active",
        "email_verified_at": null,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

---

## Dashboard

### GET /dashboard

Auth required.
Response 200

```json
{
    "total_pendapatan": 1200000,
    "jumlah_transaksi": 45,
    "meja_aktif": 3,
    "top_menus": [
        {
            "menu_id": 1,
            "total_terjual": 50,
            "menu": { "id": 1, "name": "Nasi Goreng" }
        }
    ],
    "recent_transactions": [
        { "id": 10, "no_trx": "TRX-20260227-1234", "grand_total": 55000 }
    ]
}
```

---

## Kategori Menu

### GET /kategori

Auth required.
Response 200 (paginated, 10 items per page)

```json
{
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Makanan",
                "menus_count": 12,
                "created_at": "2026-02-27T10:30:00Z",
                "updated_at": "2026-02-27T10:30:00Z"
            }
        ],
        "per_page": 10,
        "total": 45,
        "last_page": 5,
        "first_page_url": "http://localhost:8000/api/v1/kategori?page=1",
        "last_page_url": "http://localhost:8000/api/v1/kategori?page=5",
        "next_page_url": "http://localhost:8000/api/v1/kategori?page=2",
        "prev_page_url": null
    }
}
```

### POST /kategori

Auth required.
Validation: `name` must be unique, string, max 255 chars
Request body

```json
{ "name": "Minuman" }
```

Response 201

```json
{
    "message": "Kategori berhasil ditambahkan.",
    "data": {
        "id": 2,
        "name": "Minuman",
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### GET /kategori/{id}

Auth required.
Response 200

```json
{
    "data": {
        "id": 1,
        "name": "Makanan",
        "menus_count": 12,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### PUT /kategori/{id}

Auth required.
Request body

```json
{ "name": "Snack" }
```

Response 200

```json
{
    "message": "Kategori berhasil diperbarui.",
    "data": { "id": 1, "name": "Snack" }
}
```

### DELETE /kategori/{id}

Auth required.
Response 200

```json
{ "message": "Kategori berhasil dihapus." }
```

Response 409 (still has menu)

```json
{ "message": "Kategori tidak dapat dihapus karena masih memiliki menu" }
```

---

## Menu

### GET /menu

Auth required.
Response 200 (paginated, 10 items per page)

```json
{
    "menus": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "kategori_menu_id": 1,
                "name": "Nasi Goreng",
                "price": "25000",
                "stock": 50,
                "daily_stock": 20,
                "status": "Tersedia",
                "created_at": "2026-02-27T10:30:00Z",
                "updated_at": "2026-02-27T10:30:00Z"
            }
        ],
        "per_page": 10,
        "total": 50,
        "last_page": 5
    },
    "kategoris": [
        {
            "id": 1,
            "name": "Makanan",
            "created_at": "2026-02-27T10:30:00Z",
            "updated_at": "2026-02-27T10:30:00Z"
        }
    ]
}
```

### POST /menu

Auth required.
Validation: kategori_menu_id must exist, name must be unique string, price numeric, stock/daily_stock integers >= 0, status in ['Tersedia','Habis']
Request body

```json
{
    "kategori_menu_id": 1,
    "name": "Nasi Goreng",
    "price": "25000",
    "stock": 50,
    "daily_stock": 20,
    "status": "Tersedia"
}
```

Response 201

```json
{
    "message": "Menu berhasil ditambahkan.",
    "data": {
        "id": 1,
        "kategori_menu_id": 1,
        "name": "Nasi Goreng",
        "price": "25000",
        "stock": 50,
        "daily_stock": 20,
        "status": "Tersedia",
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### GET /menu/{id}

Auth required.
Response 200

```json
{
    "data": {
        "id": 1,
        "kategori_menu_id": 1,
        "name": "Nasi Goreng",
        "price": "25000",
        "stock": 50,
        "daily_stock": 20,
        "status": "Tersedia",
        "kategori": {
            "id": 1,
            "name": "Makanan",
            "created_at": "2026-02-27T10:30:00Z",
            "updated_at": "2026-02-27T10:30:00Z"
        },
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### PUT /menu/{id}

Auth required.
Request body

```json
{
    "kategori_menu_id": 1,
    "name": "Nasi Goreng Spesial",
    "price": "30000",
    "stock": 40,
    "daily_stock": 15,
    "status": "Tersedia"
}
```

Response 200

```json
{
    "message": "Menu berhasil diperbarui.",
    "data": {
        "id": 1,
        "kategori_menu_id": 1,
        "name": "Nasi Goreng Spesial",
        "price": "30000",
        "stock": 40,
        "daily_stock": 15,
        "status": "Tersedia",
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### DELETE /menu/{id}

Auth required.
Response 200

```json
{ "message": "Menu berhasil dihapus." }
```

---

## Meja

### GET /meja

Auth required.
Response 200

```json
{
    "mejas": [
        {
            "id": 1,
            "no_meja": "A-001",
            "status": "Tersedia",
            "jumlah_orang": 4,
            "created_at": "2026-02-27T10:30:00Z",
            "updated_at": "2026-02-27T10:30:00Z"
        }
    ],
    "reservasis": [
        {
            "id": 1,
            "meja_id": 1,
            "nama_pelanggan": "Budi",
            "no_telepon": "08123456789",
            "waktu_reservasi": "2026-02-27T19:00:00Z",
            "status": "Pending",
            "created_at": "2026-02-27T10:30:00Z",
            "updated_at": "2026-02-27T10:30:00Z"
        }
    ]
}
```

### POST /meja

Auth required.
Validation: no_meja must be unique, format Huruf-Angka (e.g., A-001), jumlah_orang >= 1 integer
Request body

```json
{ "no_meja": "A-002", "jumlah_orang": 4 }
```

Response 201

```json
{
    "message": "Meja berhasil ditambahkan.",
    "data": {
        "id": 2,
        "no_meja": "A-002",
        "status": "Tersedia",
        "jumlah_orang": 4,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### GET /meja/{id}

Auth required.
Response 200

```json
{
    "data": {
        "id": 1,
        "no_meja": "A-001",
        "status": "Tersedia",
        "jumlah_orang": 4,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z",
        "reservasis": [
            {
                "id": 1,
                "meja_id": 1,
                "nama_pelanggan": "Budi",
                "no_telepon": "08123456789",
                "waktu_reservasi": "2026-02-27T19:00:00Z",
                "status": "Pending",
                "created_at": "2026-02-27T10:30:00Z",
                "updated_at": "2026-02-27T10:30:00Z"
            }
        ]
    }
}
```

### PUT /meja/{id}

Auth required.
Validation: status in ['Tersedia','Terisi','Reserved']
Request body

```json
{ "no_meja": "A-001", "jumlah_orang": 4, "status": "Terisi" }
```

Response 200

```json
{
    "message": "Meja berhasil diperbarui.",
    "data": {
        "id": 1,
        "no_meja": "A-001",
        "status": "Terisi",
        "jumlah_orang": 4,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### DELETE /meja/{id}

Auth required.
Response 200

```json
{ "message": "Meja berhasil dihapus." }
```

---

## Reservasi

### POST /reservasi

Auth required.
Validation: meja_id must exist, nama_pelanggan required string, no_telepon required string, waktu_reservasi required datetime format
Request body

```json
{
    "meja_id": 1,
    "nama_pelanggan": "Budi",
    "no_telepon": "08123456789",
    "waktu_reservasi": "2026-02-27 19:00:00"
}
```

Response 201

```json
{
    "message": "Reservasi berhasil dibuat.",
    "data": {
        "id": 1,
        "meja_id": 1,
        "nama_pelanggan": "Budi",
        "no_telepon": "08123456789",
        "waktu_reservasi": "2026-02-27T19:00:00Z",
        "status": "Pending",
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### PUT /reservasi/{id}

Auth required.
Validation: status in ['Pending','Selesai','Dibatalkan']
Note: Updating status to 'Selesai' or 'Dibatalkan' will set meja status back to 'Tersedia'
Request body

```json
{
    "nama_pelanggan": "Budi",
    "no_telepon": "08123456789",
    "waktu_reservasi": "2026-02-27 20:00:00",
    "status": "Selesai"
}
```

Response 200

```json
{
    "message": "Reservasi berhasil diperbarui.",
    "data": {
        "id": 1,
        "meja_id": 1,
        "nama_pelanggan": "Budi",
        "no_telepon": "08123456789",
        "waktu_reservasi": "2026-02-27T20:00:00Z",
        "status": "Selesai",
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T15:00:00Z"
    }
}
```

### DELETE /reservasi/{id}

Auth required.
Note: Deleting will set meja status back to 'Tersedia'
Response 200

```json
{ "message": "Reservasi berhasil dibatalkan." }
```

---

## Karyawan

### GET /karyawan

Auth required.
Response 200

```json
{
    "karyawan": [
        {
            "id": 1,
            "nama_lengkap": "Andi",
            "users_id": 2,
            "jabatan": "Waiter",
            "shift": "Pagi",
            "no_hp": "08123456789",
            "tgl_masuk": "2026-02-01",
            "alamat": "Bandung",
            "created_at": "2026-02-27T10:30:00Z",
            "updated_at": "2026-02-27T10:30:00Z"
        }
    ],
    "users": [
        {
            "id": 2,
            "name": "Andi",
            "email": "andi@example.com",
            "Role": "Kasir",
            "status": "active"
        }
    ]
}
```

### POST /karyawan

Auth required.
Validation: jabatan in ['Kasir','Waiter','Koki','Manajer'], shift in ['Pagi','Malam'], users_id must exist and unique to employees
Request body

```json
{
    "nama_lengkap": "Andi",
    "users_id": 2,
    "jabatan": "Waiter",
    "shift": "Pagi",
    "no_hp": "08123456789",
    "tgl_masuk": "2026-02-01",
    "alamat": "Bandung"
}
```

Response 201

```json
{
    "message": "Karyawan berhasil ditambahkan.",
    "data": {
        "id": 1,
        "nama_lengkap": "Andi",
        "users_id": 2,
        "jabatan": "Waiter",
        "shift": "Pagi",
        "no_hp": "08123456789",
        "tgl_masuk": "2026-02-01",
        "alamat": "Bandung",
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### GET /karyawan/{id}

Auth required.
Response 200

```json
{
    "data": {
        "id": 1,
        "nama_lengkap": "Andi",
        "users_id": 2,
        "jabatan": "Waiter",
        "shift": "Pagi",
        "no_hp": "08123456789",
        "tgl_masuk": "2026-02-01",
        "alamat": "Bandung",
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z",
        "user": {
            "id": 2,
            "name": "Andi",
            "email": "andi@example.com",
            "Role": "Kasir",
            "status": "active"
        }
    }
}
```

### PUT /karyawan/{id}

Auth required.
Request body

```json
{
    "nama_lengkap": "Andi",
    "users_id": 2,
    "jabatan": "Waiter",
    "shift": "Malam",
    "no_hp": "08123456789",
    "tgl_masuk": "2026-02-01",
    "alamat": "Bandung"
}
```

Response 200

```json
{
    "message": "Karyawan berhasil diperbarui.",
    "data": { "id": 1, "shift": "Malam" }
}
```

### DELETE /karyawan/{id}

Auth required.
Response 200

```json
{ "message": "Karyawan berhasil dihapus." }
```

---

## Users (Manajemen User)

### GET /users

Auth required.
Response 200

```json
{
    "data": [
        {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "Role": "Manajer",
            "status": "active",
            "email_verified_at": null,
            "created_at": "2026-02-27T10:30:00Z",
            "updated_at": "2026-02-27T10:30:00Z"
        }
    ]
}
```

### POST /users

Auth required.
Validation: email must be unique, password min 8 chars, Role in ['Manajer','Kasir'] or nullable
Request body

```json
{
    "name": "Admin",
    "email": "admin@example.com",
    "password": "secret123",
    "Role": "Kasir"
}
```

Response 201

```json
{
    "message": "User berhasil ditambahkan.",
    "data": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "Role": "Kasir",
        "status": "active",
        "email_verified_at": null,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z"
    }
}
```

### GET /users/{id}

Auth required.
Response 200

```json
{
    "data": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "Role": "Manajer",
        "status": "active",
        "email_verified_at": null,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T10:30:00Z",
        "karyawan": null
    }
}
```

### PUT /users/{id}

Auth required.
Validation: status in ['active','inactive'], password optional (min 8 chars if provided)
Request body

```json
{
    "name": "Admin",
    "email": "admin@example.com",
    "password": "secret123",
    "Role": "Manajer",
    "status": "active"
}
```

Response 200

```json
{
    "message": "User berhasil diperbarui.",
    "data": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "Role": "Manajer",
        "status": "active",
        "email_verified_at": null,
        "created_at": "2026-02-27T10:30:00Z",
        "updated_at": "2026-02-27T15:00:00Z"
    }
}
```

### DELETE /users/{id}

Auth required.
Response 200

```json
{ "message": "User berhasil dihapus." }
```

---

## Pesanan (Ordering)

### GET /pesanan

Auth required.
Returns list of available meja, waiters, and menus (with daily_stock > 0)
Response 200

```json
{
    "mejas": [
        {
            "id": 1,
            "no_meja": "A-001",
            "status": "Tersedia",
            "jumlah_orang": 4,
            "created_at": "2026-02-27T10:30:00Z",
            "updated_at": "2026-02-27T10:30:00Z"
        }
    ],
    "waiters": [
        {
            "id": 3,
            "nama_lengkap": "Andi",
            "jabatan": "Waiter",
            "users_id": 2,
            "shift": "Pagi",
            "no_hp": "08123456789",
            "tgl_masuk": "2026-02-01",
            "alamat": "Bandung"
        }
    ],
    "menus": [
        {
            "id": 1,
            "name": "Nasi Goreng",
            "daily_stock": 20,
            "kategori_menu_id": 1,
            "price": "25000",
            "stock": 50,
            "status": "Tersedia"
        }
    ]
}
```

### POST /pesanan

Auth required.
Create transaction with detail items, deduct daily_stock from menu, update meja status
Request body (JSON string)

```json
{
    "meja_id": 1,
    "items": "[{\"menu_id\":1,\"qty\":2,\"price\":25000,\"note\":\"Pedas\",\"waiter_name\":\"Andi\",\"order_type\":\"dine_in\"}]"
}
```

Notes on items

- items must be stringified JSON array
- Each item requires: menu_id, qty, price, note, waiter_name, order_type
- order_type values: dine_in or take_away
- qty will be deducted from menu.daily_stock
  Response 200

```json
{
    "success": true,
    "message": "Pesanan Meja berhasil disimpan dan stock menu telah dikurangi!",
    "transaction_id": 12,
    "no_trx": "TRX-20260227-1234"
}
```

Response 400 (stock not enough)

```json
{
    "success": false,
    "message": "Stock Nasi Goreng tidak cukup! Tersedia: 5, Diminta: 10"
}
```

Response 400 (empty items)

```json
{
    "success": false,
    "message": "Pesanan tidak boleh kosong!"
}
```

---

## Pembayaran (Payment)

### GET /pembayaran

Auth required.
Fetch unpaid transactions (status ordered) and optionally specific transaction details
Query params

- `trx_id` (optional): transaction id to fetch details
  Response 200 (without trx_id or trx not found)

```json
{
    "transaksi_aktif": [
        {
            "id": 12,
            "no_trx": "TRX-20260227-1234",
            "meja_id": 1,
            "status": "ordered",
            "meja": {
                "id": 1,
                "no_meja": "A-001",
                "status": "Terisi",
                "jumlah_orang": 4
            }
        }
    ],
    "selected_transaksi": null,
    "subtotal": 0,
    "pajak": 0,
    "grand_total": 0
}
```

Response 200 (with trx_id)

```json
{
  "transaksi_aktif": [...],
  "selected_transaksi": {
    "id": 12,
    "no_trx": "TRX-20260227-1234",
    "meja_id": 1,
    "status": "ordered",
    "meja": { "id": 1, "no_meja": "A-001" },
    "detail_transactions": [
      {
        "id": 1,
        "transaction_id": 12,
        "menu_id": 1,
        "jumlah_pesanan": 2,
        "price": 25000,
        "subtotal": 50000,
        "note": "Pedas",
        "menu": { "id": 1, "name": "Nasi Goreng" }
      }
    ]
  },
  "subtotal": 50000,
  "pajak": 5000,
  "grand_total": 55000
}
```

### PUT /pembayaran/{id}

Auth required.
Process payment: validate amount >= grand_total, create payment record, update transaction status to paid, clear meja if dine_in
Validation: uang_diterima must be numeric >= 0 and >= grand_total
Request body

```json
{ "uang_diterima": 60000 }
```

Response 200

```json
{
    "message": "Pembayaran berhasil.",
    "payment": {
        "id": 3,
        "transaction_id": 12,
        "amount": 60000,
        "paid_at": "2026-02-27T15:30:00Z",
        "users_id": 1,
        "created_at": "2026-02-27T15:30:00Z",
        "updated_at": "2026-02-27T15:30:00Z"
    },
    "transaction": {
        "id": 12,
        "no_trx": "TRX-20260227-1234",
        "status": "paid"
    }
}
```

Response 422 (insufficient amount)

```json
{ "message": "Uang diterima kurang dari total tagihan!" }
```

---

## History (Transaction History)

### GET /history

Auth required.
Fetch all paid transactions with optional filtering
Query params

- `date` (optional): YYYY-MM-DD format to filter by date
- `search` (optional): partial no_trx to search
  Response 200

```json
{
    "transactions": [
        {
            "id": 12,
            "no_trx": "TRX-20260227-1234",
            "status": "paid",
            "order_type": "dine_in",
            "waiter_name": "Andi",
            "meja_id": 1,
            "users_id": 1,
            "subtotal": 50000,
            "pajak": 5000,
            "grandTotal": 55000,
            "kembalian": 5000,
            "meja": { "id": 1, "no_meja": "A-001" },
            "user": { "id": 1, "name": "Admin", "email": "admin@example.com" },
            "detail_transactions": [
                {
                    "id": 1,
                    "menu_id": 1,
                    "jumlah_pesanan": 2,
                    "price": 25000,
                    "subtotal": 50000,
                    "note": "Pedas",
                    "menu": {
                        "id": 1,
                        "name": "Nasi Goreng",
                        "kategori_menu_id": 1
                    }
                }
            ],
            "payment": {
                "id": 3,
                "amount": 60000,
                "paid_at": "2026-02-27T15:30:00Z",
                "user": { "id": 1, "name": "Admin" }
            },
            "created_at": "2026-02-27T15:00:00Z",
            "updated_at": "2026-02-27T15:30:00Z"
        }
    ]
}
```

---

## Pendapatan (Revenue & Analytics)

### GET /pendapatan

Auth required.
Fetch revenue reports including daily/weekly/monthly summaries, top menus, top tables, staff performance, and chart data
Query params

- `periode` (optional): `7_hari` (default), `bulan_ini`, `tahun_ini` - affects chart data
  Response 200

```json
{
    "pendapatan_hari_ini": 250000,
    "perubahan_harian": 10.5,
    "pendapatan_minggu_ini": 1500000,
    "perubahan_mingguan": -5.0,
    "pendapatan_bulan_ini": 6200000,
    "perubahan_bulanan": 12.0,
    "top_menus": [
        {
            "menu_id": 1,
            "total_terjual": 50,
            "total_omzet": 1250000,
            "menu": {
                "id": 1,
                "name": "Nasi Goreng",
                "price": "25000",
                "kategori_menu_id": 1
            }
        }
    ],
    "top_mejas": [
        {
            "meja_id": 1,
            "total_transaksi": 15,
            "total_tagihan": 750000,
            "grand_total": 825000,
            "meja": { "id": 1, "no_meja": "A-001" }
        }
    ],
    "performa_kasir": [
        {
            "users_id": 1,
            "total_transaksi": 20,
            "total_uang_masuk": 1100000,
            "user": { "id": 1, "name": "Admin", "email": "admin@example.com" }
        }
    ],
    "performa_waiter": [
        {
            "waiter_name": "Andi",
            "total_meja_dilayani": 25
        }
    ],
    "chart_data": {
        "labels": [
            "Fri, 21 Feb",
            "Sat, 22 Feb",
            "Sun, 23 Feb",
            "Mon, 24 Feb",
            "Tue, 25 Feb",
            "Wed, 26 Feb",
            "Thu, 27 Feb"
        ],
        "data": [150000, 200000, 175000, 225000, 180000, 200000, 250000]
    },
    "periode": "7_hari"
}
```

---

## Invoice

### GET /invoice/{id}/download

Auth required.
Response

- PDF file download `invoice-<no_trx>.pdf`
- Content-Type: application/pdf

---

## Data Models & Relationships

### Database Schema Overview

**Users**

- id (PK)
- name, email (unique), password (hashed)
- Role: enum['Owner', 'Manajer', 'Kasir'], nullable
- status: enum['active', 'inactive'], default active
- Relationships: hasOne(Karyawan), hasMany(transaction), hasMany(payment)

**Kategori Menu**

- id (PK)
- name (unique, string)
- Relationships: hasMany(Menu)

**Menu**

- id (PK)
- kategori_menu_id (FK)
- name (unique, string)
- price (string - sent as quoted number)
- stock (integer)
- daily_stock (integer - decreases with orders, resets daily)
- status: enum['Tersedia', 'Habis']
- Relationships: belongsTo(KategoriMenu), hasMany(DetailTransaction)

**Meja (Tables)**

- id (PK)
- no_meja (unique, format: A-001)
- jumlah_orang (integer)
- status: enum['Tersedia', 'Terisi', 'Reserved']
- Relationships: hasMany(Reservasi), hasMany(Transaction)

**Reservasi**

- id (PK)
- meja_id (FK)
- nama_pelanggan, no_telepon
- waktu_reservasi (datetime)
- status: enum['Pending', 'Selesai', 'Dibatalkan']
- Relationships: belongsTo(Meja)

**Transaction (Pesanan)**

- id (PK)
- users_id (FK) - cashier
- meja_id (FK) - table, nullable for take_away
- no_trx (unique, format: TRX-YYYYMMDD-XXXX)
- waiter_name (string)
- order_type: enum['dine_in', 'take_away']
- status: enum['ordered', 'paid']
- Relationships: belongsTo(User), belongsTo(Meja), hasMany(DetailTransaction), hasOne(Payment)

**DetailTransaction**

- id (PK)
- transaction_id (FK)
- menu_id (FK)
- jumlah_pesanan (integer)
- price (integer) - price per item
- subtotal (integer) - jumlah_pesanan \* price
- note (string, nullable)
- Relationships: belongsTo(Transaction), belongsTo(Menu)

**Payment**

- id (PK)
- transaction_id (FK, unique)
- users_id (FK) - cashier who processed
- amount (integer) - money received
- paid_at (datetime)
- Relationships: belongsTo(Transaction), belongsTo(User)

**Karyawan (Employee)**

- id (PK)
- users_id (FK, unique)
- nama_lengkap
- shift: enum['Pagi', 'Malam']
- jabatan: enum['Kasir', 'Waiter', 'Koki', 'Manajer']
- no_hp, alamat
- tgl_masuk (date)
- Relationships: belongsTo(User)

---

## Android Implementation Examples

### Retrofit Service Definition

```kotlin
interface RestorantApiService {
    @POST("auth/login")
    suspend fun login(@Body request: LoginRequest): Response<AuthResponse>

    @GET("kategori")
    suspend fun getKategori(): Response<KategoriListResponse>

    @POST("pesanan")
    suspend fun createOrder(@Body request: OrderRequest): Response<OrderResponse>

    @GET("pembayaran")
    suspend fun getPayments(@Query("trx_id") trxId: Int?): Response<PaymentListResponse>

    @PUT("pembayaran/{id}")
    suspend fun processPayment(@Path("id") transactionId: Int, @Body request: PaymentRequest): Response<PaymentResponse>
}
```

### Request/Response Models

```kotlin
data class LoginRequest(
    val email: String,
    val password: String
)

data class AuthResponse(
    val message: String,
    val token: String,
    val user: User
)

data class OrderRequest(
    val meja_id: Int,
    val items: String  // JSON string of items
)

data class OrderResponse(
    val success: Boolean,
    val message: String,
    val transaction_id: Int,
    val no_trx: String
)
```

### Error Handling Pattern

```kotlin
try {
    val response = apiService.login(credentials)
    if (response.isSuccessful) {
        // Handle 200 response
        val authData = response.body()
        saveToken(authData?.token)
    } else {
        // Handle error responses
        when (response.code()) {
            403 -> showError("Account inactive")
            422 -> showError("Invalid credentials")
            500 -> showError("Server error")
        }
    }
} catch (e: Exception) {
    showError("Network error: ${e.message}")
}
```

### Token Management

```kotlin
// Add interceptor to automatically include token
val httpClient = OkHttpClient.Builder()
    .addInterceptor { chain ->
        val original = chain.request()
        val token = getStoredToken()
        val request = if (token != null) {
            original.newBuilder()
                .header("Authorization", "Bearer $token")
                .build()
        } else {
            original
        }
        chain.proceed(request)
    }
    .build()

val retrofit = Retrofit.Builder()
    .baseUrl("http://192.168.1.100:8000/api/v1/")
    .client(httpClient)
    .addConverterFactory(GsonConverterFactory.create())
    .build()
```

---

## Testing the API

Use the following tools to test endpoints before integrating into Android:

1. **Postman** - Import the swagger/openapi spec
2. **cURL** - Command line testing
    ```bash
    curl -X POST http://localhost:8000/api/v1/auth/login \
      -H "Content-Type: application/json" \
      -d '{\"email\":\"user@example.com\",\"password\":\"secret\"}'
    ```
3. **Insomnia** - Similar to Postman
4. **Thunder Client** - VS Code extension

---

## Version History

**v1** (Current) - Feb 27, 2026

- Complete CRUD for Kategori, Menu, Meja, Karyawan, Users
- Transaction & Payment processing
- Reservasi management
- Revenue & Analytics endpoints
- History and Invoice generation

---

## Support & Notes

- All timestamps use UTC ISO 8601 format
- Database uses InnoDB with foreign key constraints
- Transactions use database transactions (rollback on error)
- Stock management is atomic per order
- Authentication uses Laravel Sanctum tokens
- No pagination limit constraint (but default is 10/15 items per page)
- Tax calculation: 10% of subtotal, always applied

For issues or questions, contact the development team.
