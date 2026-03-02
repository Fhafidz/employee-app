# Sistem Manajemen Pegawai (HRIS) - Laravel 12

Sistem Manajemen Pegawai yang premium, responsif, dan berperforma tinggi yang dibangun menggunakan Laravel 12. Proyek ini dirancang untuk memenuhi persyaratan penilaian Full Stack Developer, dengan fokus pada arsitektur kode yang bersih (clean architecture), UI/UX modern, dan pengelolaan data yang tangguh.

---

## 🚀 Fitur Utama

- **CRUD Komprehensif**: Menambah, Melihat, Mengubah, dan Menghapus (Soft-Delete) data pegawai.
- **DataTable Lanjutan**: Pemrosesan sisi server (Server-side) dengan pencarian global, filter per kolom, dan pengurutan.
- **Desain Responsif**: Dioptimalkan sepenuhnya untuk Desktop dan Mobile menggunakan Tailwind CSS dan tipografi Mona Sans.
- **Formulir Interaktif**:
    - **Dropzone.js**: Lampiran multi-dokumen dengan fitur tarik & lepas (drag & drop).
    - **FileInputJS**: Upload foto profil dengan pratinjau instan dan tombol hapus yang user-friendly.
    - **Select2**: Dropdown yang mendukung pencarian untuk semua bidang pilihan.
    - **DateRangePicker**: Filter rentang tanggal dan pemilihan tanggal standar.
- **Ekspor Data**: Ekspor daftar pegawai ke Excel, PDF, dan CSV langsung dari browser.
- **Recycle Bin**: Mengembalikan data pegawai yang dihapus atau menghapusnya secara permanen.
- **Akses API**: Endpoint JSON khusus untuk data pegawai.

---

## 📂 Dokumentasi Struktur & Fungsi Kode

Sistem ini menggunakan arsitektur **Service-Repository Pattern** untuk memisahkan tanggung jawab (Separation of Concerns), memudahkan pengujian, dan menjaga kode tetap bersih (Clean Code).

### 1. Model & Database (`app/Models`)

- **`Employee.php`**: Representasi data pegawai. Mengatur mass-assignment (`$fillable`), konstanta status (Gender, Agama, Status Kerja), dan **Accessors** untuk memformat tanggal secara otomatis agar sesuai dengan format UI (DD-MM-YYYY).
- **`EmployeeDocument.php`**: Mengelola data lampiran dokumen yang terhubung dengan pegawai.

### 2. Repositori (`app/Repositories`)

- **`Interfaces/EmployeeRepositoryInterface.php`**: Kontrak yang mendefinisikan metode apa saja yang harus tersedia untuk pengelolaan data pegawai.
- **`Eloquent/EmployeeRepository.php`**: Implementasi nyata menggunakan Eloquent. Mengandung logika query database yang kompleks, termasuk filter dinamis untuk DataTable dan pencarian data.

### 3. Layanan (`app/Services`)

- **`EmployeeService.php`**: Menangani logika bisnis yang tidak berhubungan langsung dengan database, seperti proses pengunggahan file (foto & dokumen), validasi file, dan penghapusan file fisik dari storage saat data dihapus.

### 4. Controller & Routing (`app/Http/Controllers`)

- **`EmployeeController.php`**: Mengatur alur (flow) aplikasi. Menghubungkan permintaan dari view ke Service atau Repository, lalu mengembalikan respon (view atau JSON).
- **`routes/web.php`**: Mendefinisikan endpoint URL sistem, termasuk prefix `employees` dan penamaan route yang konsisten.

### 5. Validasi (`app/Http/Requests`)

- **`StoreEmployeeRequest.php` & `UpdateEmployeeRequest.php`**: Mengisolasi logika validasi input dari controller. Memastikan NIK unik, format email benar, dan file yang diunggah sesuai ketentuan.

### 6. Frontend & UI (`resources/views`)

- **`layouts/app.blade.php`**: Layout utama yang memuat CSS/JS global, penataan font _Mona Sans_, dan skema warna premium.
- **`components/`**: Berisi komponen Blade yang dapat digunakan kembali:
    - `form-input.blade.php`: Input teks standar.
    - `form-select.blade.php`: Integrasi Select2.
    - `form-file.blade.php`: Integrasi FileInputJS untuk foto profil.
- **`employees/index.blade.php`**: Halaman utama yang menginisialisasi **DataTable**, filter rentang tanggal, dan fitur ekspor.
- **`assets/js/app-utils.js`**: **Inti dari interaksi frontend**. Mengelola inisialisasi otomatis untuk Select2, Datepicker, FileInputJS, dan menyediakan wrapper AJAX (`submitForm`) agar semua submit form berjalan tanpa refresh halaman.

---

## 🛠️ Stack Teknologi

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS, jQuery, DataTables.net, SweetAlert2, Dropzone.js, FileInputJS.
- **Database**: MySQL/MariaDB.
- **Library Ekspor**: jsPDF, xlsx.js, tableExport.

---

## 📥 Panduan Instalasi

1. **Clone & Install**:

    ```bash
    git clone <url-repository>
    cd employee-app
    composer install && npm install
    ```

2. **Environment**:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Atur koneksi DB di .env._

3. **Storage & Database**:

    ```bash
    php artisan storage:link
    php artisan migrate --seed
    ```

4. **Jalankan**:
    ```bash
    php artisan serve
    ```

---

## 🧪 Menjalankan Pengujian (Testing)

```bash
php artisan test
```

_Terdapat 24 test case yang memvalidasi fungsionalitas CRUD, Upload, dan Filtering._

## 📄 Lisensi

Tersedia di bawah [Lisensi MIT](LICENSE).
