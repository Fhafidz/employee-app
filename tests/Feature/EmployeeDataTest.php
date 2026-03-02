<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that getData API returns employee data in correct format
     */
    public function test_get_data_returns_employees_in_correct_format()
    {
        // Create test employee
        $employee = Employee::factory()->create();

        // Call the data endpoint
        $response = $this->get(route('employees.data'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert JSON structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
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
                    'hired_date',
                    'status',
                ]
            ]
        ]);

        // Assert data is returned
        $response->assertJsonCount(1, 'data');
    }

    /**
     * Test getData with gender filter
     */
    public function test_get_data_filters_by_gender()
    {
        // Create male and female employees
        Employee::factory()->create(['gender' => 'M']);
        Employee::factory()->create(['gender' => 'F']);

        // Get data filtered by male
        $response = $this->get(route('employees.data', ['gender' => 'M']));

        $response->assertStatus(200);
        $data = $response->json('data');
        
        // Should only return male employees
        $this->assertCount(1, $data);
        $this->assertEquals('M', $data[0]['gender']);
    }

    /**
     * Test getData with department filter
     */
    public function test_get_data_filters_by_department()
    {
        Employee::factory()->create(['department' => 'IT']);
        Employee::factory()->create(['department' => 'HR']);

        $response = $this->get(route('employees.data', ['department' => 'IT']));

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(1, $data);
        $this->assertEquals('IT', $data[0]['department']);
    }

    /**
     * Test getData returns empty array when no employees exist
     */
    public function test_get_data_returns_empty_when_no_employees()
    {
        $response = $this->get(route('employees.data'));

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    /**
     * Test getData with multiple filters
     */
    public function test_get_data_filters_by_multiple_criteria()
    {
        Employee::factory()->create([
            'gender' => 'M',
            'department' => 'IT',
            'status' => 'active'
        ]);
        Employee::factory()->create([
            'gender' => 'F',
            'department' => 'IT',
            'status' => 'active'
        ]);
        Employee::factory()->create([
            'gender' => 'M',
            'department' => 'HR',
            'status' => 'active'
        ]);

        // Filter by gender and department
        $response = $this->get(route('employees.data', [
            'gender' => 'M',
            'department' => 'IT'
        ]));

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(1, $data);
        $this->assertEquals('M', $data[0]['gender']);
        $this->assertEquals('IT', $data[0]['department']);
    }
}
