<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['M', 'F']);

        return [
            'identity_number' => fake()->unique()->numerify('33##############'),
            'full_name' => fake()->name($gender === 'M' ? 'male' : 'female'),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->unique()->numerify('08##########'),
            'gender' => $gender,
            'date_of_birth' => fake()->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
            'address' => fake()->address(),
            'religion' => fake()->randomElement(['islam', 'christianity_protestant', 'catholic', 'hindu', 'buddhism', 'confucianism']),
            'marital_status' => fake()->randomElement(['single', 'married', 'divorced']),
            'department' => fake()->randomElement(['IT Development', 'Human Resources', 'Finance', 'Marketing', 'Operations', 'Quality Assurance']),
            'position' => fake()->randomElement(['Staff', 'Senior Staff', 'Supervisor', 'Manager', 'Director', 'Specialist']),
            'working_status' => fake()->randomElement(['full_time', 'part_time', 'contract', 'intern']),
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']), 
            'hired_date' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'photo' => null,
        ];
    }
}
