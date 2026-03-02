<?php

namespace App\Http\Controllers;

use App\Models\employee;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\EmployeeService;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;


class EmployeeController extends Controller
{

    protected $employeeService;
    protected $employeeRepository;

    public function __construct(EmployeeService $employeeService, EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeService = $employeeService;
        $this->employeeRepository = $employeeRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employees.index');
    }


    /**
     * Menyediakan API untuk DataTables dengan dukungan filter
     */
    public function getData(Request $request)
    {
        $filters = [
            'gender'        => $request->input('gender'),
            'department'    => $request->input('department'),
            'position'      => $request->input('position'),
            'working_status'=> $request->input('working_status'),
            'marital_status'=> $request->input('marital_status'),
            'religion'      => $request->input('religion'),
            'status'        => $request->input('status'),
            'dob_from'      => $request->input('dob_from'),
            'dob_to'        => $request->input('dob_to'),
            'hired_from'    => $request->input('hired_from'),
            'hired_to'      => $request->input('hired_to'),
        ];

        $employees = $this->employeeRepository->getAllForDatatables($filters);
        
        return response()->json(
            ['data' => $employees],
            200,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    public function create()
    {
        return view('employees.create', $this->getFormOptions());
    }

    /**
     * Helper method to get form options
     */
    private function getFormOptions()
    {
        return [
            'genders' => Employee::$genderLabels,
            'religions' => Employee::$religionLabels,
            'maritalStatuses' => Employee::$maritalStatusLabels,
            'workingStatuses' => Employee::$workingStatusLabels,
            'statuses' => [
                'active' => 'Aktif',
                'inactive' => 'Non-Aktif'
            ],
            'departments' => [
                'IT' => 'IT',
                'HR' => 'HR',
                'Finance' => 'Finance',
                'Marketing' => 'Marketing',
                'IT Development' => 'IT Development',
                'Human Resources' => 'Human Resources',
                'Quality Assurance' => 'Quality Assurance',
                'Operations' => 'Operations'
            ],
            'positions' => [
                'Staff' => 'Staff',
                'Senior Staff' => 'Senior Staff',
                'Supervisor' => 'Supervisor',
                'Manager' => 'Manager',
                'Director' => 'Director',
                'Specialist' => 'Specialist'
            ]
        ];
    }

    /**
     * Menyimpan data pegawai baru
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            // Data otomatis tervalidasi sebelum masuk ke baris ini.
            // Kita cukup lempar data yang valid ke Service.
            $this->employeeService->createEmployee(
                $request->validated(), 
                $request->file('photo'),
                $request->file('documents')
            );

            // Mengembalikan response JSON untuk ditangkap oleh AJAX & SweetAlert di Frontend
            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil ditambahkan!'
            ], 200);

        } catch (\Exception $e) {
            // Jika ada error di Service (misal gagal simpan foto/database)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan form edit untuk pegawai yang dipilih
     * Menggunakan Route Model Binding (best practice)
     */
    public function edit(Employee $employee)
    {
        // Accessor di Model sudah handle formatting date_of_birth & hired_date ke DD-MM-YYYY
        return view('employees.edit', array_merge(
            compact('employee'),
            $this->getFormOptions()
        ));
    }

    /**
     * Proses update data
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        try {
            $this->employeeService->updateEmployee(
                $employee->id, 
                $request->validated(), 
                $request->file('photo'),
                $request->file('documents')
            );

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil diperbarui!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses hapus data
     */
    public function destroy(Employee $employee)
    {
        try {
            $this->employeeService->deleteEmployee($employee->id);

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dihapus!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get documents for an employee
     */
    public function getDocuments(Employee $employee)
    {
        try {
            $documents = $employee->documents()->get(['id', 'employee_id', 'path', 'original_filename', 'size', 'created_at']);
            
            return response()->json([
                'success' => true,
                'documents' => $documents
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display trashed (soft deleted) employees
     */
    public function trashed()
    {
        return view('employees.trashed');
    }

    /**
     * Get trashed employees data for DataTables
     */
    public function getTrashedData()
    {
        $employees = Employee::onlyTrashed()->get()->map(function($employee) {
            return [
                'id' => $employee->id,
                'identity_number' => $employee->identity_number,
                'full_name' => $employee->full_name,
                'email' => $employee->email,
                'phone_number' => $employee->phone_number,
                'gender' => $employee->gender,
                'date_of_birth' => $employee->date_of_birth,
                'address' => $employee->address,
                'religion' => $employee->religion,
                'marital_status' => $employee->marital_status,
                'department' => $employee->department,
                'position' => $employee->position,
                'working_status' => $employee->working_status,
                'hired_date' => $employee->hired_date,
                'status' => $employee->status,
                'deleted_at' => $employee->deleted_at->format('d-m-Y H:i')
            ];
        });

        return response()->json(['data' => $employees], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Restore a soft deleted employee
     */
    public function restore($id)
    {
        try {
            $employee = Employee::onlyTrashed()->find($id);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }

            $employee->restore();

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dikembalikan!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengembalikan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a soft deleted employee
     */
    public function forceDelete($id)
    {
        try {
            $employee = Employee::onlyTrashed()->find($id);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }

            // Delete associated documents and photos
            if ($employee->photo) {
                \Storage::disk('public')->delete('employees/' . $employee->photo);
            }
            
            $employee->documents()->forceDelete();
            $employee->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dihapus secara permanen!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
