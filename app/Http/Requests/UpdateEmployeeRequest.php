<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee')->id ?? $this->route('employee');
        
        return [
            'identity_number' => "required|string|max:16|unique:employees,identity_number,{$employeeId}",
            'full_name'       => 'required|string|max:255',
            'email'           => "required|email|unique:employees,email,{$employeeId}",
            'phone_number'    => 'required|string|max:15',
            'gender'          => 'required|in:M,F',
            'date_of_birth'   => 'required|date_format:d-m-Y',
            'address'         => 'required|string',
            'religion'        => 'required|in:islam,christianity_protestant,catholic,hindu,buddhism,confucianism',
            'marital_status'  => 'required|in:single,married,divorced',
            'department'      => 'required|string',
            'position'        => 'required|string',
            'working_status'  => 'required|in:full_time,part_time,contract,intern',
            'hired_date'      => 'required|date_format:d-m-Y',
            'status'          => 'required|in:active,inactive',
            'photo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Maks 2MB
        ];
    }

    /**
     * Custom pesan error dalam Bahasa Indonesia untuk UX yang lebih baik
     */
    public function messages(): array
    {
        return [
            'required'    => 'Kolom :attribute wajib diisi.',
            'email'       => 'Format :attribute tidak valid.',
            'unique'      => ':attribute ini sudah terdaftar di sistem.',
            'date_format' => 'Format tanggal harus DD-MM-YYYY.',
            'in'          => 'Pilihan :attribute tidak valid.',
            'image'       => 'File harus berupa gambar.',
            'mimes'       => 'Format gambar harus jpeg, png, atau jpg.',
            'max'         => [
                'file'   => 'Ukuran gambar maksimal adalah 2MB.',
                'string' => 'Teks maksimal :max karakter.',
            ],
        ];
    }

    /**
     * Mengubah nama atribut agar pesan error lebih enak dibaca (misal: "Kolom full_name wajib diisi" menjadi "Kolom Nama Lengkap wajib diisi")
     */
    public function attributes(): array
    {
        return [
            'identity_number' => 'NIK / Nomor Identitas',
            'full_name'       => 'Nama Lengkap',
            'phone_number'    => 'Nomor Telepon',
            'date_of_birth'   => 'Tanggal Lahir',
            'address'         => 'Alamat',
            'religion'        => 'Agama',
            'marital_status'  => 'Status Pernikahan',
            'department'      => 'Departemen',
            'position'        => 'Jabatan',
            'working_status'  => 'Status Pekerjaan',
            'hired_date'      => 'Tanggal Bergabung',
            'status'          => 'Status Karyawan',
            'photo'           => 'Foto Profil'
        ];
    }
}
