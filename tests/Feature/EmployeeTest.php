<?php

namespace Tests\Feature;

use App\Models\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Halaman create employee dapat diakses
     */
    public function test_create_employee_page_is_accessible(): void
    {
        $response = $this->get(route('employees.create'));
        $response->assertStatus(200);
        $response->assertViewIs('employees.create');
    }

    /**
     * Test: Menambah employee dengan data lengkap dan file
     */
    public function test_create_employee_with_valid_data(): void
    {
        Storage::fake('private');
        
        $data = [
            'identity_number' => '1234567890123456',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'gender' => 'M',
            'date_of_birth' => '15-01-1990',  // Format DD-MM-YYYY
            'address' => 'Jl. Contoh No. 123',
            'religion' => 'islam',
            'marital_status' => 'single',
            'department' => 'IT',
            'position' => 'Staff',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '01-01-2024',  // Format DD-MM-YYYY
            'photo' => UploadedFile::fake()->image('photo.jpg', 100, 100),
            'documents' => [
                UploadedFile::fake()->create('document1.pdf', 500),
                UploadedFile::fake()->create('document2.pdf', 500),
            ]
        ];

        $response = $this->post(route('employees.store'), $data);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data pegawai berhasil ditambahkan!']);

        // Verify data tersimpan di database
        $employee = Employee::where('email', 'john@example.com')->first();
        $this->assertNotNull($employee);
        $this->assertEquals('John Doe', $employee->full_name);
        $this->assertNotNull($employee->photo);
    }

    /**
     * Test: Validasi NIK maksimal 16 karakter
     */
    public function test_identity_number_validation_max_length(): void
    {
        $data = [
            'identity_number' => '123456789012345678',  // > 16 karakter
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'gender' => 'M',
            'date_of_birth' => '15-01-1990',  // Format DD-MM-YYYY
            'address' => 'Jl. Contoh No. 123',
            'religion' => 'islam',
            'marital_status' => 'single',
            'department' => 'IT',
            'position' => 'Staff',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '01-01-2024',  // Format DD-MM-YYYY
        ];

        $response = $this->postJson(route('employees.store'), $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['identity_number']);
    }

    /**
     * Test: Required fields validation
     */
    public function test_required_fields_validation(): void
    {
        $response = $this->postJson(route('employees.store'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
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
                 ]);
    }

    /**
     * Test: Email format validation
     */
    public function test_email_format_validation(): void
    {
        $data = [
            'identity_number' => '1234567890123456',
            'full_name' => 'John Doe',
            'email' => 'invalid-email',  // Invalid email
            'phone_number' => '081234567890',
            'gender' => 'M',
            'date_of_birth' => '15-01-1990',  // Format DD-MM-YYYY
            'address' => 'Jl. Contoh No. 123',
            'religion' => 'islam',
            'marital_status' => 'single',
            'department' => 'IT',
            'position' => 'Staff',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '01-01-2024',  // Format DD-MM-YYYY
        ];

        $response = $this->postJson(route('employees.store'), $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Halaman index employee dapat diakses
     */
    public function test_employee_index_page_is_accessible(): void
    {
        Employee::factory()->count(5)->create();

        $response = $this->get(route('employees.index'));
        $response->assertStatus(200);
        $response->assertViewIs('employees.index');
    }

    /**
     * Test: Edit employee page dapat diakses
     */
    public function test_edit_employee_page_is_accessible(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->get(route('employees.edit', $employee->id));
        $response->assertStatus(200);
        $response->assertViewIs('employees.edit');
    }

    /**
     * Test: Edit page menampilkan data employee dengan format yang benar
     */
    public function test_edit_page_displays_employee_data_correctly(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->get(route('employees.edit', $employee->id));

        $response->assertStatus(200);
        $response->assertViewHas('employee', function ($viewEmployee) use ($employee) {
            return $viewEmployee->id === $employee->id &&
                   $viewEmployee->full_name === $employee->full_name &&
                   $viewEmployee->email === $employee->email;
        });
    }

    /**
     * Test: Update employee dengan data baru
     */
    public function test_update_employee_with_valid_data(): void
    {
        Storage::fake('private');
        
        $employee = Employee::factory()->create();

        $updateData = [
            'identity_number' => $employee->identity_number,
            'full_name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '089876543210',
            'gender' => 'F',
            'date_of_birth' => '20-05-1995',  // Format DD-MM-YYYY
            'address' => 'Jl. Baru No. 456',
            'religion' => 'christianity_protestant',
            'marital_status' => 'married',
            'department' => 'HR',
            'position' => 'Manager',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '15-06-2023',  // Format DD-MM-YYYY
        ];

        $response = $this->putJson(route('employees.update', $employee->id), $updateData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data pegawai berhasil diperbarui!']);

        // Verify the update by querying the database
        $updatedEmployee = Employee::find($employee->id);
        $this->assertEquals('Updated Name', $updatedEmployee->full_name);
        $this->assertEquals('updated@example.com', $updatedEmployee->email);
    }

    /**
     * Test: Edit employee dengan pembaruan foto
     */
    public function test_edit_employee_with_photo_update(): void
    {
        Storage::fake('private');
        
        $employee = Employee::factory()->create();
        $oldPhoto = $employee->photo;

        $updateData = [
            'identity_number' => $employee->identity_number,
            'full_name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '089876543210',
            'gender' => 'F',
            'date_of_birth' => '20-05-1995',
            'address' => 'Jl. Baru No. 456',
            'religion' => 'christianity_protestant',
            'marital_status' => 'married',
            'department' => 'HR',
            'position' => 'Manager',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '15-06-2023',
            'photo' => UploadedFile::fake()->image('new_photo.jpg', 100, 100),
        ];

        $response = $this->putJson(route('employees.update', $employee->id), $updateData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data pegawai berhasil diperbarui!']);

        $updatedEmployee = Employee::find($employee->id);
        $this->assertNotNull($updatedEmployee->photo);
        $this->assertNotEquals($oldPhoto, $updatedEmployee->photo);
    }

    /**
     * Test: Edit employee dengan menambah dokumen
     */
    public function test_edit_employee_with_document_addition(): void
    {
        Storage::fake('private');
        
        $employee = Employee::factory()->create();
        $initialDocCount = $employee->documents()->count();

        $updateData = [
            'identity_number' => $employee->identity_number,
            'full_name' => $employee->full_name,
            'email' => 'realemail@example.com',
            'phone_number' => '081234567890',
            'gender' => $employee->gender,
            'date_of_birth' => $employee->date_of_birth,
            'address' => $employee->address,
            'religion' => $employee->religion,
            'marital_status' => $employee->marital_status,
            'department' => $employee->department,
            'position' => $employee->position,
            'working_status' => $employee->working_status,
            'status' => $employee->status,
            'hired_date' => $employee->hired_date,
            'documents' => [
                UploadedFile::fake()->create('new_doc.pdf', 500),
            ]
        ];

        $response = $this->putJson(route('employees.update', $employee->id), $updateData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data pegawai berhasil diperbarui!']);

        $employee->refresh();
        $this->assertGreaterThan($initialDocCount, $employee->documents()->count());
    }

    /**
     * Test: Edit employee dengan validasi error
     */
    public function test_edit_employee_validation_error(): void
    {
        $employee = Employee::factory()->create();
        $otherEmployee = Employee::factory()->create();  // Ciptakan employee lain

        $updateData = [
            'identity_number' => $otherEmployee->identity_number,  // Gunakan ID employee lain (akan trigger unique error)
            'full_name' => '',  // Wajib diisi
            'email' => 'invalid-email',  // Format email tidak valid
            'phone_number' => '081234567890',
            'gender' => 'M',
            'date_of_birth' => '15-01-1990',
            'address' => 'Jl. Contoh No. 123',
            'religion' => 'islam',
            'marital_status' => 'single',
            'department' => 'IT',
            'position' => 'Staff',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '01-01-2024',
        ];

        $response = $this->putJson(route('employees.update', $employee->id), $updateData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['identity_number', 'full_name', 'email']);
    }

    /**
     * Test: Edit employee yang tidak ditemukan (404)
     */
    public function test_edit_non_existent_employee_returns_404(): void
    {
        $response = $this->get(route('employees.edit', 99999));
        
        $response->assertStatus(404);
    }

    /**
     * Test: Update non-existent employee returns 404
     */
    public function test_update_non_existent_employee_returns_404(): void
    {
        $updateData = [
            'identity_number' => '1234567890123456',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'gender' => 'M',
            'date_of_birth' => '15-01-1990',
            'address' => 'Jl. Contoh No. 123',
            'religion' => 'islam',
            'marital_status' => 'single',
            'department' => 'IT',
            'position' => 'Staff',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '01-01-2024',
        ];

        $response = $this->putJson(route('employees.update', 99999), $updateData);
        
        $response->assertStatus(404);
    }

    /**
     * Test: Delete employee (soft delete)
     */
    public function test_delete_employee(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->delete(route('employees.destroy', $employee->id));

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data pegawai berhasil dihapus!']);

        // Verify soft delete by checking the record exists but with deleted_at set
        $deletedEmployee = Employee::withTrashed()->find($employee->id);
        $this->assertNotNull($deletedEmployee->deleted_at);
    }

    /**
     * Test: Photo file upload validation
     */
    public function test_photo_file_type_validation(): void
    {
        Storage::fake('private');
        
        $data = [
            'identity_number' => '1234567890123456',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'gender' => 'M',
            'date_of_birth' => '15-01-1990',  // Format DD-MM-YYYY
            'address' => 'Jl. Contoh No. 123',
            'religion' => 'islam',
            'marital_status' => 'single',
            'department' => 'IT',
            'position' => 'Staff',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '01-01-2024',  // Format DD-MM-YYYY
            'photo' => UploadedFile::fake()->create('photo.txt', 100),  // Invalid type (text file)
        ];

        $response = $this->postJson(route('employees.store'), $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['photo']);
    }

    /**
     * Test: Multiple documents upload
     */
    public function test_create_employee_with_multiple_documents(): void
    {
        Storage::fake('private');
        
        $data = [
            'identity_number' => '1234567890123456',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'gender' => 'M',
            'date_of_birth' => '15-01-1990',  // Format DD-MM-YYYY
            'address' => 'Jl. Contoh No. 123',
            'religion' => 'islam',
            'marital_status' => 'single',
            'department' => 'IT',
            'position' => 'Staff',
            'working_status' => 'full_time',
            'status' => 'active',
            'hired_date' => '01-01-2024',  // Format DD-MM-YYYY
            'documents' => [
                UploadedFile::fake()->create('cv.pdf', 500),
                UploadedFile::fake()->create('certificate.pdf', 500),
                UploadedFile::fake()->create('recommendation.pdf', 500),
            ]
        ];

        $response = $this->post(route('employees.store'), $data);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Data pegawai berhasil ditambahkan!']);

        // Verify ada 3 documents tersimpan
        $employee = Employee::where('email', 'john@example.com')->first();
        $this->assertEquals(3, $employee->documents()->count());
    }
}
