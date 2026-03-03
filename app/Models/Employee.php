<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    // Cast Enum dan Datetime
    protected $casts = [
        'gender' => \App\Enums\Gender::class,
        'religion' => \App\Enums\Religion::class,
        'marital_status' => \App\Enums\MaritalStatus::class,
        'working_status' => \App\Enums\WorkingStatus::class,
        'status' => \App\Enums\EmployeeStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Tabel Yang Boleh Diisi
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
