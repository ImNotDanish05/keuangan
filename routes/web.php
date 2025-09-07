<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['web','auth','adminonly'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('expenses', ExpenseController::class)->except(['show']);
    Route::resource('incomes', IncomeController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // User management
    Route::resource('users', UserManagementController::class)->except(['show']);
    Route::patch('/users/{user}/toggle-approval', [UserManagementController::class, 'toggleApproval'])->name('users.toggleApproval');
});
