# Sistem Manajemen Pegawai (Employee System) - Laravel 12

## 🛠️ Stack Teknologi

- **Core**: Laravel 12 (PHP 8.2+), MySQL.
- **Styles**: Tailwind CSS
- **JavaScript Library**:
    - **jQuery 3.7** & **DataTables** (dengan Responsive Extension).
    - **SweetAlert2** untuk notifikasi dan dialog konfirmasi.
    - **Select2** untuk dropdown pencarian & dynamic tagging.
    - **Moment.js** & **DateRangePicker** untuk manajemen waktu.
    - **tableExport.js**, **jsPDF**, **xlsx.js** untuk ekspor data.
    - **fileinputjs & dropzone** untuk upload foto atau dokumen.

---

## 📥 Panduan Instalasi

1. **Persiapan Project**:

    ```bash
    git clone <url-repository>
    cd employee-app
    composer install
    npm install && npm run build (opsional)
    ```

2. **Pengaturan Lingkungan**:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Buka file `.env` dan sesuaikan kredensial MySQL. Pastikan database `employee_app` sudah dibuat di MySQL._

3. **Database & Storage**:

    Pastikan ekstensi `pdo_mysql` sudah aktif di `php.ini` Anda.

    ```bash
    php artisan storage:link
    php artisan migrate
    php artisan db:seed
    ```

4. **Jalankan Aplikasi**:
    ```bash
    php artisan serve
    ```

---

## 🧪 Pengujian (Testing)

    ```bash
    php artisan test
    ```

## 📄 Lisensi

Copyright © 2026
