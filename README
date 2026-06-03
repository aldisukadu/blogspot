# 📝 Solo Blog — Dokumentasi Proyek

Proyek ini adalah **Blog Pribadi** berbasis PHP Native dan Bootstrap 5.  
Dibangun untuk memahami konsep **relasi database 1-M (One-to-Many)** dan **CRUD** (Create, Read, Update, Delete).

---

## 🗂️ Struktur Folder Lengkap

```
blogspot/
├── assets/
│   └── style.css          → Semua CSS kustom (terpisah dari Bootstrap)
├── config/
│   └── database.php       → Koneksi ke MySQL
├── layout/
│   ├── header.php         → Kerangka atas halaman (Navbar + head HTML)
│   └── footer.php         → Kerangka bawah halaman (Footer + Bootstrap JS)
├── posts/
│   ├── index.php          → Dashboard: daftar semua artikel (tabel)
│   ├── create.php         → Form tulis artikel baru
│   ├── store.php          → Proses simpan artikel ke database
│   ├── edit.php           → Form edit artikel (sudah terisi data lama)
│   ├── update.php         → Proses simpan perubahan artikel
│   └── delete.php         → Proses hapus artikel
├── categories/
│   ├── index.php          → Daftar kategori + form tambah kategori
│   └── delete.php         → Proses hapus kategori
├── index.php              → Halaman depan (tampilan untuk pembaca)
├── post.php               → Halaman detail satu artikel
├── blogspot_db.sql        → Script SQL untuk membuat database & tabel
└── README.md              → File ini
```

---

## 🗃️ Struktur Database

Proyek ini menggunakan **dua tabel** yang saling terhubung:

```
categories          posts
+----+----------+   +----+-------------+---------+
| id | name     |   | id | category_id | title   |
| id | slug     |   | id | content     | ...     |
+----+----------+   +----+-------------+---------+
       ↑                        |
       └────────────────────────┘
         Relasi 1-M (satu kategori, banyak artikel)
```

**Relasi:** Satu kategori bisa punya **banyak** artikel.  
Satu artikel hanya boleh masuk ke **satu** kategori.  
Jika kategori dihapus → `category_id` artikel berubah jadi `NULL` (tidak ikut terhapus).

---

## 🚀 Cara Setup

### 1. Letakkan folder
Taruh folder `blogspot/` di dalam:
- **XAMPP** → `C:/xampp/htdocs/`
- **Laragon** → `C:/laragon/www/`

### 2. Buat database
- Buka `http://localhost/phpmyadmin`
- Klik tab **SQL**
- Copy-paste isi file `blogspot_db.sql`
- Klik **Go**

### 3. Buka di browser
```
http://localhost/blogspot/
```

---

## 🔗 Daftar Halaman

| URL | Keterangan |
|-----|-----------|
| `/blogspot/` | Halaman depan — tampilan pembaca |
| `/blogspot/post.php?id=1` | Detail artikel |
| `/blogspot/posts/index.php` | Dashboard penulis |
| `/blogspot/posts/create.php` | Form tulis artikel |
| `/blogspot/categories/index.php` | Kelola kategori |

---

## 🛡️ Konsep Keamanan yang Diterapkan

| Ancaman | Cara Menangkal |
|---------|---------------|
| SQL Injection | Prepared Statement (`?` placeholder + `bind_param`) |
| XSS | `htmlspecialchars()` di setiap output ke HTML |
| Akses langsung ke file proses | Cek `$_SERVER['REQUEST_METHOD'] !== 'POST'` |
| ID tidak valid | Casting `(int)` sebelum dipakai di query |

---

## 📂 Dokumentasi Per Folder

Setiap folder punya file `.md` penjelasan sendiri:

- `config/` → lihat `config/PENJELASAN.md`
- `assets/` → lihat `assets/PENJELASAN.md`
- `layout/` → lihat `layout/PENJELASAN.md`
- `posts/` → lihat `posts/PENJELASAN.md`
- `categories/` → lihat `categories/PENJELASAN.md`