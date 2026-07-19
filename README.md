# SistemBerpusatBPSM — Arkib Sistem Berpusat BPSM

![Status](https://img.shields.io/badge/status-arkib-lightgrey)
![PHP](https://img.shields.io/badge/PHP-native-777BB4)
![Database](https://img.shields.io/badge/database-MySQL%2FMariaDB-4479A1)

Repositori arkib untuk himpunan sub-sistem dalaman **Bahagian Pengurusan Sumber Manusia (BPSM), Kementerian Pendidikan Malaysia (KPM)**. Repo ini mengandungi satu aplikasi PHP lengkap (modul **MBJ**), eksport pangkalan data sistem berpusat BPSM, dan rujukan submodule kepada beberapa sub-sistem lain yang disimpan berasingan.

**Domain production:** https://sistemberpusatbpsm.akmalmarvis.com

---

## Kandungan

- [Ciri-Ciri Utama](#ciri-ciri-utama)
- [Teknologi](#teknologi)
- [Struktur Direktori](#struktur-direktori)
- [Pemasangan](#pemasangan)
- [Konfigurasi](#konfigurasi)
- [Deployment](#deployment)
- [Status Projek](#status-projek)
- [Kredit](#kredit)

---

## Ciri-Ciri Utama

### Modul MBJ (`Sistem BPSM/mbj/`) — aplikasi lengkap dalam repo ini

| Ciri | Penerangan |
|---|---|
| **Borang maklum balas MBJ** | Borang awam (`mbj-form.php`) untuk kutipan maklum balas Majlis Bersama Jabatan, dengan akses melalui token rawak (`bin2hex(random_bytes(32))`). |
| **Log Masuk Admin** | Log masuk sesi PHP ringkas (`login.php`) untuk akses dashboard. |
| **Dashboard** | Paparan dan pengurusan rekod maklum balas (`dashboard.php`, `v1dashboard.php`). |
| **CRUD Rekod** | `create.php`, `update.php`, `delete.php` untuk pengurusan data jadual `mbj`. |
| **Skema DB** | `db/mbj_feedback.sql` — pangkalan data `mbj_feedback`, jadual `mbj`. |

### Eksport pangkalan data sistem berpusat (`Sistem BPSM/bpsm.sql`)

Dump penuh pangkalan data `bpsm` (28 jadual) yang merangkumi modul-modul sistem berpusat:

- **Pengurusan aset** — `assets`, `asset_types`, `ict-aset`, `loans`, `loan_assets`
- **Stok ICT** — `ict_stock`, `stok_ict`, `cadangan_stok`, `justification_list`
- **Tempahan kenderaan** — `booking`, `car`, `driver`, `passenger`, `calendar_holiday`
- **Akaun & lesen** — `akaun_lesen`, `licenses`, `user_licenses`, `domains`, `passwords`
- **Organisasi** — `department`, `unit`, `users`, `mbj`
- **Rangka kerja Laravel** — `migrations`, `sessions`, `failed_jobs`, `password_reset_tokens`, `personal_access_tokens`

### Rujukan submodule

Folder `Sistem BPSM/bpsm`, `ictbpsm`, `pinas-2` dan `stk` ialah **rujukan git submodule (gitlink)** kepada repo berasingan. Fail `.gitmodules` tidak disertakan, jadi kandungan sub-sistem tersebut **tidak tersedia** dalam clone repo ini — folder akan kelihatan kosong.

---

## Teknologi

| Lapisan | Teknologi |
|---|---|
| Bahasa | **PHP native** (modul MBJ, sambungan `mysqli`) |
| Database | **MySQL / MariaDB** — `mbj_feedback` (modul MBJ), `bpsm` (sistem berpusat) |
| Frontend | HTML + CSS (`styles.css`) + JavaScript (`script.js`) |
| Hosting | cPanel JimatHosting (LiteSpeed) |

---

## Struktur Direktori

```
SistemBerpusatBPSM/
├── Sistem BPSM/
│   ├── mbj/                       # Aplikasi PHP modul MBJ (lengkap)
│   │   ├── index.php              # Sambungan DB + jana token borang
│   │   ├── login.php              # Log masuk admin
│   │   ├── dashboard.php          # Dashboard maklum balas
│   │   ├── mbj-form.php           # Borang maklum balas awam
│   │   ├── create.php / update.php / delete.php
│   │   ├── db/mbj_feedback.sql    # Skema DB modul MBJ
│   │   └── dump/                  # Fail lama/percubaan (bukan production)
│   ├── bpsm.sql                   # Dump penuh DB sistem berpusat (28 jadual)
│   ├── bpsm/                      # Submodule (kandungan tiada dalam repo ini)
│   ├── ictbpsm/                   # Submodule (kandungan tiada dalam repo ini)
│   ├── pinas-2/                   # Submodule (kandungan tiada dalam repo ini)
│   └── stk/                       # Submodule (kandungan tiada dalam repo ini)
└── db/
    └── akmalmar_sistembpsm.sql    # Dump kosong (header sahaja, tiada jadual)
```

---

## Pemasangan

Setup lokal modul MBJ menggunakan XAMPP:

```bash
git clone https://github.com/akmal4244/SistemBerpusatBPSM.git
# Salin "Sistem BPSM/mbj" ke C:\xampp\htdocs\mbj
```

1. Cipta pangkalan data dan import skema:
   ```sql
   CREATE DATABASE mbj_feedback;
   ```
   ```bash
   mysql -u root mbj_feedback < "Sistem BPSM/mbj/db/mbj_feedback.sql"
   ```
2. Kemaskini kredensial DB dalam `mbj/index.php` (lihat [Konfigurasi](#konfigurasi)).
3. Buka `http://localhost/mbj`.

Untuk sistem berpusat penuh, import `Sistem BPSM/bpsm.sql` ke pangkalan data `bpsm`.

---

## Konfigurasi

Kredensial DB modul MBJ ditetapkan terus dalam `Sistem BPSM/mbj/index.php`:

```php
$servername = "localhost";
$dbUsername = "<USER_DB>";
$dbPassword = "<PASSWORD_ANDA>";
$dbName     = "<NAMA_DB>";
```

Kredensial log masuk admin dalam `login.php` adalah nilai contoh (*hardcoded*) — **wajib ditukar** kepada mekanisme selamat sebelum sebarang deployment sebenar.

---

## Deployment

Deployment ke cPanel JimatHosting (doc root `/home2/akmalmar/public_html/sistemberpusatbpsm/`):

1. Muat naik folder modul yang diperlukan (contoh: `Sistem BPSM/mbj/`).
2. Cipta DB `akmalmar_<nama>` melalui cPanel dan import fail `.sql` berkaitan.
3. Kemaskini kredensial DB dalam fail PHP modul.
4. Pastikan folder aplikasi mempunyai kebenaran baca oleh web server.

---

## Status Projek

**Arkib** — repo ini berfungsi sebagai simpanan berpusat: satu modul lengkap (MBJ), eksport pangkalan data sistem berpusat, dan rujukan submodule kepada sub-sistem lain. Ia bukan aplikasi tunggal yang boleh dijalankan terus dari root repo.

---

## Kredit

**Sistem Dibangunkan Sepenuhnya Oleh Akmal Marvis © 2026**

Unit Teknikal & ICT, BPSM, KPM
