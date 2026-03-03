<?php

namespace App\Repositories\Eloquent;

use App\Models\Employee;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    /**
     * Mengambil semua data pegawai untuk DataTables dengan filter opsional
     */
    public function getAllForDatatables($filters = [])
    {
        $query = Employee::latest();

        // Apply filters if provided
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }
        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }
        if (!empty($filters['position'])) {
            $query->where('position', $filters['position']);
        }
        if (!empty($filters['working_status'])) {
            $query->where('working_status', $filters['working_status']);
        }
        if (!empty($filters['marital_status'])) {
            $query->where('marital_status', $filters['marital_status']);
        }
        if (!empty($filters['religion'])) {
            $query->where('religion', $filters['religion']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Date of birth range filter
        if (!empty($filters['dob_from']) && !empty($filters['dob_to'])) {
            $from = $this->parseDateFilter($filters['dob_from']);
            $to   = $this->parseDateFilter($filters['dob_to']);
            if ($from && $to) {
                $query->whereBetween('date_of_birth', [$from, $to]);
            }
        }

        // Hired date range filter
        if (!empty($filters['hired_from']) && !empty($filters['hired_to'])) {
            $from = $this->parseDateFilter($filters['hired_from']);
            $to   = $this->parseDateFilter($filters['hired_to']);
            if ($from && $to) {
                $query->whereBetween('hired_date', [$from, $to]);
            }
        }

        return $query->get();
    }

    /**
     * Mengubah format tanggal DD/MM/YYYY menjadi Y-m-d agar bisa dibaca database
     */
    private function parseDateFilter(string $date): ?string
    {
        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', trim($date))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Create new employee record
     */
    public function create(array $data)
    {
        return Employee::create($data);
    }

    /**
     * 
     * Find employee by ID
     */
    public function findById($id)
    {
        return Employee::findOrFail($id); 
    }

    /**
     * Update employee record
     */
    public function update($id, array $data)
    {
        $employee = ($id instanceof Employee) ? $id : $this->findById($id);
        $employee->update($data);
        return $employee;
    }

    /**
     * Delete employee (soft delete)
     */
    public function delete($id)
    {
        $employee = ($id instanceof Employee) ? $id : $this->findById($id);
        return $employee->delete();
    }

    /**
     * Search employee by keyword (name, email, nik, phone)
     */
    public function search($keyword)
    {
        return Employee::where('full_name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhere('identity_number', 'like', "%{$keyword}%")
            ->orWhere('phone_number', 'like', "%{$keyword}%")
            ->latest()
            ->get();
    }

    /**
     * Filter by employment status (active/inactive)
     */
    public function filterByStatus($status)
    {
        return Employee::where('status', $status)->latest()->get();
    }

    /**
     * Filter by department
     */
    public function filterByDepartment($department)
    {
        return Employee::where('department', $department)->latest()->get();
    }
}