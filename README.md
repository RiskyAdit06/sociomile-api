# 🧩 Sociomile Mini System – Backend API

Mini versi Sociomile Omnichannel Customer Support System yang berfokus pada:

- **Authentication & Authorization**
- **Multi-Tenancy**
- **Ticket Management**
- **Conversation (Chat)**

_Project ini dibuat sebagai bagian dari Engineering Take-Home Coding Assignment._

---

## 🛠 Tech Stack

- **Backend:** Laravel (REST API)
- **Database:** MySQL
- **Authentication:** JWT (Access Token + Refresh Token)
- **Architecture:** MVC + Middleware
- **API Style:** RESTful

---

## 🚀 Cara Menjalankan Aplikasi

1. **Clone Repository**
    ```sh
    git clone <https://github.com/RiskyAdit06/sociomile-api/>
    cd sociomile-api
    ```

2. **Install Dependency**
    ```sh
    composer install
    ```

3. **Setup Environment**
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. **Migration**
    ```sh
    php artisan migrate
    ```

5. **Jalankan Server**
    ```sh
    php artisan serve
    ```
    API akan berjalan di: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔐 Authentication & Authorization

### Login

- Autentikasi menggunakan **email + password**
- Menghasilkan:
  - `access_token` (**JWT**)
  - `refresh_token`

### Token Flow

- **Access Token:** Digunakan untuk mengakses API, exp. ~1 menit (contoh).
- **Refresh Token:** Untuk mendapatkan access token baru, disimpan di database, exp. 14 hari.

**Refresh Token Endpoint:**  
`POST /api/refresh-token`

---

### 👤 Role & Authorization

#### Role user:
- `admin`
- `agent`

Endpoint dilindungi dengan Role Middleware, contoh:
```php
Route::middleware(['role:admin,agent'])
```

#### Contoh Hak Akses

| Endpoint      | Admin | Agent |
| ------------- |:-----:|:-----:|
| List ticket   | ✅    | ✅    | 
| Create ticket | ✅    | ❌    | 
| Assign ticket | ✅    | ❌    | 
| Update status | ✅    | ✅    | 
| Chat          | ✅    | ✅    |

---

## 🏢 Multi-Tenancy

- Setiap tabel utama memiliki kolom `tenant_id`
- Semua query difilter berdasarkan `tenant_id` user yang login  
  Contoh:
  ```php
  Ticket::where('tenant_id', auth()->user()->tenant_id)
  ```
- 🔒 User tenant A **tidak bisa** mengakses data tenant B

---

## 🎫 Ticket Management

### Struktur Ticket

- `id`
- `title`
- `description`
- `status` (`open`, `in_progress`, `resolved`, `closed`)
- `priority` (`low`, `medium`, `high`)
- `assigned_agent_id`
- `customer_id`
- `tenant_id`
- `created_at`
- `updated_at`

### Endpoint Ticket

| Method | Endpoint                   | Description                 |
| ------ | -------------------------- | ---------------------------|
| GET    | `/api/tickets`             | List ticket (filter status/agent) |
| POST   | `/api/tickets`             | Create ticket (customer only)     |
| PATCH  | `/api/tickets/{id}/status` | Update status                    |
| PATCH  | `/api/tickets/{id}/assign` | Assign agent                     |

---

## 💬 Conversation (Chat)

- Setiap ticket memiliki conversation log
- **Fitur:**
    - Agent & customer bisa mengirim pesan
    - Pesan tersimpan berurutan berdasarkan waktu

### Endpoint Conversation

| Method | Endpoint                              |
| ------ | ------------------------------------- |
| GET    | `/api/tickets/{id}/conversations`     |
| POST   | `/api/tickets/{id}/conversations`     |

---

## 📦 Database Design (Ringkas)

### Tabel Utama

- `users`
- `tickets`
- `conversations`
- `refresh_tokens`

### Relasi

- User → Ticket (customer/agent)
- Ticket → Conversation
- User → Refresh Token

### Index Utama

- `tenant_id`
- `assigned_agent_id`
- `status`

---

## ⚠️ Fitur yang Belum Sempat Dikerjakan

| Fitur                   | Keterangan & Alasan                                                                                            |
|-------------------------|----------------------------------------------------------------------------------------------------------------|
| Event / Async Processing| Belum ada event system (misal: trigger saat ticket resolved). Fokus ke core requirement, butuh desain queue, dsb|
| Redis (Cache/Rate Limit)| Belum implementasi cache atau rate limit (tidak krusial untuk core logic)                                      |
| Docker & docker-compose | Belum ada docker setup (fokus pada API, infra bisa ditambah belakangan)                                         |
| Unit Testing            | Belum ada unit test (fokus ke arsitektur & correctness, sudah dipersiapkan untuk ke depan)                      |
| OpenAPI / Swagger       | Dokumentasi belum otomatis, masih manual. Endpoint masih sedikit, Swagger mudah ditambah nanti                  |

---
