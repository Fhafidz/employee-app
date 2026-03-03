<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;

class EmployeeService
{
    protected $employeeRepository;

    // Dependency Injection melalui Constructor
    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Create employee dengan transaction handling & document upload
     */
    public function createEmployee(array $data, $photoFile = null, $documentFiles = null)
    {
        try {
            // Wrap dalam transaction untuk atomicity
            return DB::transaction(function () use ($data, $photoFile, $documentFiles) {
                // 1. Handling Format Tanggal (dari DatePicker d-m-Y ke Y-m-d)
                if (isset($data['date_of_birth'])) {
                    $data['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $data['date_of_birth'])->format('Y-m-d');
                }
                if (isset($data['hired_date'])) {
                    $data['hired_date'] = Carbon::createFromFormat('d-m-Y', $data['hired_date'])->format('Y-m-d');
                }

                // 2. Handling File Upload (Foto)
                if ($photoFile) {
                    $filename = time() . '_' . preg_replace('/\s+/', '_', $photoFile->getClientOriginalName());
                    $photoFile->storeAs('employees', $filename, 'public');
                    $data['photo'] = $filename;
                }

                // 3. Simpan data melalui Repository
                $employee = $this->employeeRepository->create($data);

                // 4. Handling Document Uploads (Dropzone files)
                if ($documentFiles && is_array($documentFiles)) {
                    foreach ($documentFiles as $file) {
                        $this->storeDocument($employee, $file);
                    }
                }

                return $employee;
            });

        } catch (\Exception $e) {
            Log::error('Error creating employee: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menyimpan satu file dokumen
     */
    private function storeDocument(Employee $employee, $file)
    {
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $path = 'documents/employees/' . $employee->id;
        
        $file->storeAs($path, $filename, 'public');

        \App\Models\EmployeeDocument::create([
            'employee_id' => $employee->id,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path . '/' . $filename,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    /**
     * Update employee dengan transaction handling & document handling
     */
    public function updateEmployee($id, array $data, $photoFile = null, $documentFiles = null)
    {
        try {
            return DB::transaction(function () use ($id, $data, $photoFile, $documentFiles) {
                $employee = $this->employeeRepository->findById($id);

                // 1. Handling Format Tanggal
                if (isset($data['date_of_birth'])) {
                    $data['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $data['date_of_birth'])->format('Y-m-d');
                }
                if (isset($data['hired_date'])) {
                    $data['hired_date'] = Carbon::createFromFormat('d-m-Y', $data['hired_date'])->format('Y-m-d');
                }

                // 2. Handling File Upload
                if ($photoFile) {
                    // Hapus foto lama jika ada
                    if ($employee->photo && Storage::disk('public')->exists('employees/' . $employee->photo)) {
                        Storage::disk('public')->delete('employees/' . $employee->photo);
                    }

                    $filename = time() . '_' . preg_replace('/\s+/', '_', $photoFile->getClientOriginalName());
                    $photoFile->storeAs('employees', $filename, 'public');
                    $data['photo'] = $filename;
                }

                // 3. Update data melalui Repository (pass model instance untuk hindari query find redundan)
                $updated = $this->employeeRepository->update($employee, $data);

                // 4. Handle new documents (jika ada)
                if ($documentFiles && is_array($documentFiles)) {
                    foreach ($documentFiles as $file) {
                        $this->storeDocument($employee, $file);
                    }
                }

                return $updated;
            });

        } catch (\Exception $e) {
            Log::error('Error updating employee: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete employee dengan handling file
     */
    public function deleteEmployee($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $employee = $this->employeeRepository->findById($id);
                
                // Hapus file fisik foto sebelum menghapus data
                if ($employee->photo && Storage::disk('public')->exists('employees/' . $employee->photo)) {
                    Storage::disk('public')->delete('employees/' . $employee->photo);
                }

                return $this->employeeRepository->delete($employee);
            });

        } catch (\Exception $e) {
            Log::error('Error deleting employee: ' . $e->getMessage());
            throw $e;
        }
    }
}

