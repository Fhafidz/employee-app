<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Enums\Gender;
use App\Enums\Religion;
use App\Enums\MaritalStatus;
use App\Enums\WorkingStatus;
use App\Enums\EmployeeStatus;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_casts_various_fields_to_enums()
    {
        $employee = Employee::factory()->create([
            'gender' => 'M',
            'religion' => 'islam',
            'marital_status' => 'single',
            'working_status' => 'full_time',
            'status' => 'active'
        ]);

        $this->assertInstanceOf(Gender::class, $employee->gender);
        $this->assertEquals(Gender::Male, $employee->gender);
        
        $this->assertInstanceOf(Religion::class, $employee->religion);
        $this->assertEquals(Religion::Islam, $employee->religion);

        $this->assertInstanceOf(MaritalStatus::class, $employee->marital_status);
        $this->assertEquals(MaritalStatus::Single, $employee->marital_status);

        $this->assertInstanceOf(WorkingStatus::class, $employee->working_status);
        $this->assertEquals(WorkingStatus::FullTime, $employee->working_status);

        $this->assertInstanceOf(EmployeeStatus::class, $employee->status);
        $this->assertEquals(EmployeeStatus::Active, $employee->status);
    }

    #[Test]
    public function it_formats_dates_via_accessors()
    {
        $employee = new Employee([
            'date_of_birth' => '1990-01-15',
            'hired_date' => '2024-03-01'
        ]);

        // Accessors should return formatted strings
        $this->assertEquals('15-01-1990', $employee->date_of_birth);
        $this->assertEquals('01-03-2024', $employee->hired_date);
    }
}
