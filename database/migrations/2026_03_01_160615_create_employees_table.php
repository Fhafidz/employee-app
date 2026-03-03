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
            
            // Data Employee
            $table->id();
            $table->string('identity_number', 16)->unique();
            $table->string('full_name')->index();
            $table->string('email')->unique();
            $table->string('phone_number', 15);
            $table->enum('gender', ['M', 'F']);
            $table->date('date_of_birth');
            $table->text('address');
            $table->enum('religion', ['islam', 'christianity_protestant', 'catholic', 'hindu', 'buddhism', 'confucianism']);
            $table->enum('marital_status', ['single', 'married', 'divorced']);
            $table->string('department')->index();
            $table->string('position')->index();
            $table->enum('working_status', ['full_time', 'part_time', 'contract', 'intern'])->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->date('hired_date')->index();
            $table->string('photo')->nullable();

            // Soft Deletes
            $table->softDeletes();

            $table->timestamps();
            
            // Composite Indexes untuk Query Performance
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