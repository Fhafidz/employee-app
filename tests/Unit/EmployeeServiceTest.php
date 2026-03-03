<?php

namespace Tests\Unit;

use App\Services\EmployeeService;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Models\Employee;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class EmployeeServiceTest extends TestCase
{
    protected $repository;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(EmployeeRepositoryInterface::class);
        $this->service = new EmployeeService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_formats_dates_before_saving()
    {
        // Data input from DatePicker (d-m-Y)
        $inputData = [
            'date_of_birth' => '15-01-1990',
            'hired_date' => '01-03-2024',
            'full_name' => 'Test Employee'
        ];

        // Expected data passed to repository (Y-m-d)
        $expectedSavedData = [
            'date_of_birth' => '1990-01-15',
            'hired_date' => '2024-03-01',
            'full_name' => 'Test Employee'
        ];

        $employee = new Employee($expectedSavedData);

        $this->repository->shouldReceive('create')
            ->once()
            ->with($expectedSavedData)
            ->andReturn($employee);

        $result = $this->service->createEmployee($inputData);
        
        $this->assertEquals($employee, $result);
    }

    /** @test */
    public function it_handles_photo_upload()
    {
        Storage::fake('public');
        $photoFile = UploadedFile::fake()->image('profile.png');

        $inputData = ['full_name' => 'John Doe'];
        
        // Use any() for the data match because time() prefix makes it dynamic
        $this->repository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function($data) {
                return $data['full_name'] === 'John Doe' && isset($data['photo']);
            }))
            ->andReturn(new Employee());

        $this->service->createEmployee($inputData, $photoFile);

        // Verify storage (prefix might differ, but should be in 'employees' directory)
        $files = Storage::disk('public')->files('employees');
        $this->assertCount(1, $files);
    }
}
