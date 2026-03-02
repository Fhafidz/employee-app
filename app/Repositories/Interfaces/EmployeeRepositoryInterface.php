<?php

namespace App\Repositories\Interfaces;

interface EmployeeRepositoryInterface
{
    /**
     * Get all employees for DataTables with optional filters
     */
    public function getAllForDatatables($filters = []);

    /**
     * Create new employee
     */
    public function create(array $data);

    /**
     * Find employee by ID
     */
    public function findById($id);

    /**
     * Update employee
     */
    public function update($id, array $data);

    /**
     * Delete employee (soft delete)
     */
    public function delete($id);

    /**
     * Get employees dengan search dan filter
     */
    public function search($keyword);

    /**
     * Get employees dengan filter status
     */
    public function filterByStatus($status);

    /**
     * Get employees dengan filter department
     */
    public function filterByDepartment($department);
}