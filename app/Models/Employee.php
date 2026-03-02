<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    // ===== ENUM CONSTANTS & TRANSLATIONS =====
    // Gender Constants
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';
    
    public static $genderLabels = [
        'M' => 'Laki-laki',
        'F' => 'Perempuan',
    ];

    // Religion Constants
    const RELIGION_ISLAM = 'islam';
    const RELIGION_CHRISTIANITY_PROTESTANT = 'christianity_protestant';
    const RELIGION_CATHOLIC = 'catholic';
    const RELIGION_HINDU = 'hindu';
    const RELIGION_BUDDHISM = 'buddhism';
    const RELIGION_CONFUCIANISM = 'confucianism';
    
    public static $religionLabels = [
        'islam' => 'Islam',
        'christianity_protestant' => 'Kristen Protestan',
        'catholic' => 'Katolik',
        'hindu' => 'Hindu',
        'buddhism' => 'Buddha',
        'confucianism' => 'Khonghucu',
    ];

    // Marital Status Constants
    const MARITAL_SINGLE = 'single';
    const MARITAL_MARRIED = 'married';
    const MARITAL_DIVORCED = 'divorced';
    
    public static $maritalStatusLabels = [
        'single' => 'Belum Menikah',
        'married' => 'Menikah',
        'divorced' => 'Cerai',
    ];

    // Working Status Constants
    const WORKING_FULLTIME = 'full_time';
    const WORKING_PARTTIME = 'part_time';
    const WORKING_CONTRACT = 'contract';
    const WORKING_INTERN = 'intern';
    
    public static $workingStatusLabels = [
        'full_time' => 'Full Time',
        'part_time' => 'Part Time',
        'contract' => 'Kontrak',
        'intern' => 'Magang',
    ];

    // Employee Status Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    
    public static $statusLabels = [
        'active' => 'Aktif',
        'inactive' => 'Non-Aktif',
    ];

    // Alternatif dari $guarded - lebih eksplisit & safer untuk mass assignment
    protected $fillable = [
        'identity_number',
        'full_name',
        'email',
        'phone_number',
        'gender',
        'date_of_birth',
        'address',
        'religion',
        'marital_status',
        'department',
        'position',
        'working_status',
        'status',
        'hired_date',
        'photo',
    ];

    // Cast hanya untuk created_at dan updated_at
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor untuk format date_of_birth ke DD-MM-YYYY
     */
    public function getDateOfBirthAttribute($value)
    {
        if (!$value || $value === '0000-00-00') return null;
        try {
            return \Carbon\Carbon::parse($value)->format('d-m-Y');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Accessor untuk format hired_date ke DD-MM-YYYY
     */
    public function getHiredDateAttribute($value)
    {
        if (!$value || $value === '0000-00-00') return null;
        try {
            return \Carbon\Carbon::parse($value)->format('d-m-Y');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Accessor untuk format created_at ke DD-MM-YYYY HH:MM:SS
     */
    public function getCreatedAtAttribute($value)
    {
        if (!$value) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Accessor untuk format updated_at ke DD-MM-YYYY HH:MM:SS
     */
    public function getUpdatedAtAttribute($value)
    {
        if (!$value) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Relationship dengan EmployeeDocument
     */
    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function deleteDocuments()
    {
        foreach ($this->documents as $doc) {
            \Storage::disk('public')->delete($doc->path);
            $doc->delete();
        }
    }
}
