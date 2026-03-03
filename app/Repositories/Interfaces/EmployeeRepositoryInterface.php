<?php

namespace App\Repositories\Interfaces;

interface EmployeeRepositoryInterface
{
    /**
     * Ambil semua data pegawai untuk DataTables dengan filter opsional
     */
    public function getAllForDatatables($filters = []);

    /**
     * Membuat data pegawai baru
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
     * Delete data pegawai (soft delete)
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