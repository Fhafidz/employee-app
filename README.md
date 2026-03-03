# Sistem Manajemen Pegawai (Employee System) - Laravel 12

Sistem Manajemen Pegawai yang premium, modern, dan sangat responsif yang dibangun menggunakan **Laravel 12**. Proyek ini dirancang dengan fokus pada arsitektur kode yang bersih (**Clean Architecture**), performa tinggi, dan pengalaman pengguna (**UX**) yang optimal di semua perangkat.

---

## 🚀 Fitur Unggulan

- **Responsive DataTables (New)**: Tampilan tabel yang cerdas dengan fitur **Grouping Columns**. Menggabungkan ~17 kolom menjadi 7 kolom utama yang padat informasi (Identitas, Profil, Pekerjaan).
- **Premium Branding & UI**: Implementasi logo kustom, tipografi yang elegan (`font-medium` untuk data tabel), dan skema warna yang profesional.
- **Type-Safe avec PHP Enums**: Menggunakan **PHP 8.1+ Enums** untuk validasi data tingkat lanjut pada kolom Gender, Agama, Status Pernikahan, dan Status Karyawan, menjamin integritas data 100%.
- **CRUD Komprehensif**: Fitur lengkap Menambah, Melihat, Mengubah, dan Menghapus (dengan dukungan **Soft-Delete**).
- **Advanced Filtering & Search**: Pencarian global instan dan filter multi-kategori (Gender, Departemen, Jabatan, Status, serta rentang tanggal) yang terintegrasi langsung di header tabel.
- **Manajemen Media & Dokumen**:
    - **Bootstrap FileInput**: Unggah foto profil dengan pratinjau instan.
    - **Dropzone.js & SweetAlert2**: Lampiran multi-dokumen (PDF/JPG/DOC) yang bisa dilihat dan diunduh melalui modal interaktif.
- **Ekspor Data Multiformat**: Ekspor daftar pegawai ke **Excel** dan **PDF** secara instan dengan layout yang rapi.
- **Recycle Bin (Trash)**: Fitur keamanan data untuk mengembalikan data pegawai yang terhapus atau menghapusnya secara permanen.

---

## 📂 Arsitektur & Struktur Kode

Proyek ini menerapkan **Service-Repository Pattern** untuk memisahkan logika bisnis dari akses database, menjadikannya mudah dalam perawatan dan pengujian.

### 1. Struktur Backend (`app/`)

- **Enums & Casting**: Menggunakan folder [`app/Enums`](app/Enums) untuk standarisasi pilihan data. Model [`Employee.php`](app/Models/Employee.php) secara otomatis melakukan _casting_ nilai database menjadi objek Enum PHP.
- **Repositories**: Menggunakan [`EmployeeRepositoryInterface.php`](app/Repositories/Interfaces/EmployeeRepositoryInterface.php) sebagai kontrak untuk akses data yang fleksibel.
- **Services**: [`EmployeeService.php`](app/Services/EmployeeService.php) mengelola proses kompleks seperti penyimpanan file fisik dan transaksi database (Atomic Transactions).
- **Validation**: Menggunakan **Form Requests** modern yang terintegrasi dengan validasi Enum ([`StoreEmployeeRequest.php`](app/Http/Requests/StoreEmployeeRequest.php)).

### 2. Standar Dokumentasi

Seluruh komentar internal (`DocBlocks`) pada kode backend telah menggunakan **Bahasa Indonesia** secara konsisten untuk memudahkan kolaborasi tim lokal.

### 3. Struktur Frontend (`resources/views`)

- **Consolidated Table**: Desain tabel di [`index.blade.php`](resources/views/employees/index.blade.php) menggunakan teknik grouping dan tipografi `font-medium` untuk keterbacaan data.
- **Global Utilities**: [`app-utils.js`](public/assets/js/app-utils.js) menyediakan fungsi pembantu untuk inisialisasi Select2, Datepicker, dan penanganan AJAX error secara dinamis.

---

## 🛠️ Stack Teknologi

- **Core**: Laravel 12 (PHP 8.2+), MySQL.
- **Data Safety**: PHP 8.1+ Backed Enums.
- **Styles**: Tailwind CSS (Premium Look & Glassmorphism).
- **JavaScript Library**:
    - **jQuery 3.7** & **DataTables** (dengan Responsive Extension).
    - **SweetAlert2** untuk notifikasi dan dialog konfirmasi.
    - **Select2** untuk dropdown pencarian & dynamic tagging.
    - **Moment.js** & **DateRangePicker** untuk manajemen waktu.
    - **tableExport.js**, **jsPDF**, **xlsx.js** untuk ekspor data.

---

## 📥 Panduan Instalasi

1. **Persiapan Project**:

    ```bash
    git clone <url-repository>
    cd employee-app
    composer install
    npm install && npm run build
    ```

2. **Pengaturan Lingkungan**:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Sesuaikan database di file `.env`._

3. **Database & Storage**:

    ```bash
    php artisan storage:link
    php artisan migrate:fresh --seed
    ```

4. **Jalankan Aplikasi**:
    ```bash
    php artisan serve
    ```

---

## 🧪 Pengujian (Testing)

Proyek ini dilengkapi dengan **28 Automated Tests** (111 assertions) untuk memastikan stabilitas:

```bash
php artisan test
```

_Mencakup Unit Test untuk Enum & Accessors, serta Feature Test untuk alur CRUD, API DataTables, Validasi File, dan Recycle Bin._

---

## 📄 Lisensi

Copyright © 2026
