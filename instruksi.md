# Instruksi Pengembangan Aplikasi CBT (Computer Based Test)

## Konfigurasi Database

**Database Configuration:**
- **DB Name:** cbt_man4_pekanbaru
- **User:** root
- **Password:** root
- **Host:** 127.0.0.1
- **Port:** 8889
- **Connection:** MySQL

## 1. Autentikasi Admin

### Login Admin
- **URL:** `/admin/login`
- Setelah berhasil login akan diarahkan ke URL `/admin`

### RBAC (Role-Based Access Control)
- **Location:** `/admin`
- **Roles yang dibutuhkan:**
  - Admin
  - Guru
  - Pengawas

## 2. Halaman Utama Siswa

### Login Siswa CBT
- Halaman utama menampilkan form login untuk siswa
- Digunakan untuk login CBT siswa

## 3. Master Data

### 3.1 Tahun Ajaran
- **Fields:**
  - `id` (Primary Key)
  - `nama_tahun_ajaran` (String)
  - `status` (Enum: Aktif/Tidak Aktif)

### 3.2 Master Guru
- **Fields:**
  - `id_guru` (Primary Key)
  - `nama_guru` (String)

### 3.3 Mata Pelajaran
- **Fields:**
  - `id` (Primary Key)
  - `nama_mapel` (String)

### 3.4 Rombel (Rombongan Belajar)
- **Fields:**
  - `id` (Primary Key)
  - `tahun_ajaran_id` (Foreign Key ke Tahun Ajaran)
  - `semester` (Enum: Ganjil/Genap)
  - `nama_rombel` (String)
  - `wali_kelas` (Foreign Key ke Guru)

### 3.5 Rombel Mapel
- **Fields:**
  - `id` (Primary Key)
  - `rombel_id` (Foreign Key ke Rombel)
  - `mata_pelajaran_id` (Foreign Key ke Mata Pelajaran)
  - `guru_pengampu` (Foreign Key ke Guru)

### 3.6 Rombel Detail
- **Fields:**
  - `id` (Primary Key)
  - `rombel_id` (Foreign Key ke Rombel)
  - `nisn` (Foreign Key ke Siswa)

### 3.7 Siswa
- **Fields:**
  - `nisn` (Primary Key)
  - `nama_siswa` (String)
  - `jenis_kelamin` (Enum: L/P)

## 4. Pengaturan Sistem (Settings)

### Tabel Settings
- **Fields:**
  - `id` (Primary Key)
  - `key` (String) - Kunci pengaturan
  - `value` (String) - Nilai pengaturan
  - `group` (String) - Grup pengaturan

### Data Settings
#### Group Umum
- `key: nama_sekolah`, `value: [Nama Sekolah]`, `group: Umum`
- `key: alamat`, `value: [Alamat Sekolah]`, `group: Umum`
- `key: no_telp`, `value: [Nomor Telepon]`, `group: Umum`
- `key: email`, `value: [Email Sekolah]`, `group: Umum`

#### Group Setting
- `key: tahun_ajaran`, `value: [Tahun Ajaran Aktif]`, `group: Setting`
- `key: semester`, `value: [Ganjil/Genap]`, `group: Setting`

---

## Catatan Penting:
- Semua foreign key harus memiliki relasi yang tepat
- Gunakan soft delete untuk data yang mungkin dihapus
- Tambahkan timestamps (created_at, updated_at) pada setiap tabel
- Gunakan UUID atau auto-increment untuk primary key sesuai kebutuhan