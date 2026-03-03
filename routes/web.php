<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

Route::prefix('employees')->name('employees.')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('index');
    Route::get('/data', [EmployeeController::class, 'getData'])->name('data'); // Untuk DataTables API
    Route::post('/', [EmployeeController::class, 'store'])->name('store');
    Route::get('/create', [EmployeeController::class, 'create'])->name('create');
    Route::get('/trash/bin', [EmployeeController::class, 'trashed'])->name('trashed');
    Route::get('/trash/data', [EmployeeController::class, 'getTrashedData'])->name('trashed.data');
    Route::post('/trash/restore/{id}', [EmployeeController::class, 'restore'])->name('restore');
    Route::delete('/trash/force/{id}', [EmployeeController::class, 'forceDelete'])->name('force_delete');
    Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
    Route::get('/{employee}/documents', [EmployeeController::class, 'getDocuments'])->name('documents');
    Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update'); 
    Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
});