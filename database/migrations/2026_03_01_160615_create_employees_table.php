<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            // 1. Primary Key
            $table->id();

            // 2. Identitas Utama & Kontak (Sering dicari/ditampilkan)
            $table->string('identity_number', 16)->unique();
            $table->string('full_name')->index(); // Di-index untuk pencarian teks di DataTables
            $table->string('email')->unique();
            $table->string('phone_number', 12);

            // 3. Data Pribadi (Jarang difilter berat, lebih untuk detail view)
            $table->enum('gender', ['M', 'F']);
            $table->date('date_of_birth');
            $table->text('address');
            $table->enum('religion', ['islam', 'christianity_protestant', 'catholic', 'hindu', 'buddhism', 'confucianism']);
            $table->enum('marital_status', ['single', 'married', 'divorced']);

            // 4. Data Perusahaan / Pekerjaan (Sangat sering difilter & diurutkan)
            $table->string('department')->index(); // Di-index untuk filter Select2 Departemen
            $table->string('position')->index(); // Di-index untuk filter Select2 Jabatan
            $table->enum('working_status', ['full_time', 'part_time', 'contract', 'intern'])->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index(); // Sering dipakai untuk "WHERE status = active"
            $table->date('hired_date')->index(); // Di-index untuk filter DateRangePicker

            // 5. Media
            $table->string('photo')->nullable();

            // 6. Soft Deletes & Timestamps
            $table->softDeletes();
            $table->timestamps();
            
            // 7. Composite Indexes untuk Query Performance
            $table->index(['department', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};