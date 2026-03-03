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
     * Menampilkan halaman daftar pegawai
     */
    public function index()
    {
        return view('employees.index');
    }


    /**
     *  API untuk DataTables dengan dukungan filter
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
     * Metode untuk mengambil opsi pilihan form (Dropdown)
     */
    private function getFormOptions()
    {
        return [
            'genders' => \App\Enums\Gender::labels(),
            'religions' => \App\Enums\Religion::labels(),
            'maritalStatuses' => \App\Enums\MaritalStatus::labels(),
            'workingStatuses' => \App\Enums\WorkingStatus::labels(),
            'statuses' => \App\Enums\EmployeeStatus::labels(),
            'departments' => [
                'IT Development' => 'IT Development',
                'Human Resources' => 'Human Resources',
                'Finance' => 'Finance',
                'Marketing' => 'Marketing',
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
            $this->employeeService->createEmployee(
                $request->validated(), 
                $request->file('photo'),
                $request->file('documents')
            );

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil ditambahkan!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 
     *  Menampilkan form edit untuk pegawai yang dipilih
     */
    public function edit(Employee $employee)
    {
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
     * Mengambil daftar dokumen milik pegawai
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
     * Menampilkan halaman trash/recyclebin data pegawai
     */
    public function trashed()
    {
        return view('employees.trashed');
    }

    /**
     * Mengambil data pegawai yang telah dihapus (Recycle Bin) untuk DataTables
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
     * Mengembalikan data pegawai dari recycle bin (Restore)
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
     * Menghapus data pegawai secara permanen dari database & storage
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
