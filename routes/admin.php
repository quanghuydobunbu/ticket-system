<?php

use App\Http\Controllers\Admin\ApiRolePermissionController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TicketTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VenueController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::resource('/admin/users', UserController::class);
// Route::resource('/admin/permissions', PermissionController::class);
// Route::resource('admin/role_premissions', RolePermissionController::class);

// Route::get('admin/api/role_permissions/getByRoleId/{role_id?}', [ApiRolePermissionController::class, 'getByRoleId'])->name('admin.api.role_has_permission.getRoleId');
// Trong routes/web.php (nếu dùng web middleware)
Route::get('/admin/api/role-has-permission/{role_id?}', [RolePermissionController::class, 'getRolePermissions'])->name('admin.api.role_has_permission.getRoleId');
// Route::get('admin/api/role_permissions/getByRoleId/{role_id?}', [ApiRolePermissionController::class, 'getByRoleId'])->name('admin.api.role_has_permission.getRoleId');
Route::resource('admin/role_premissions', RolePermissionController::class);
Route::resource('admin/permissions', PermissionController::class);
Route::resource('admin/categories', CategoryController::class);
Route::resource('admin/events', EventController::class);
Route::resource('admin/venues', VenueController::class);
Route::resource('admin/ticket-types', TicketTypeController::class);
Route::resource('admin/tickets', TicketController::class);
Route::patch('/tickets/{ticket}/checkin', [TicketController::class, 'checkin'])->name('tickets.checkin');
Route::resource('admin/bookings', BookingController::class);

// routes/web.php hoặc routes/api.php
Route::get('/check-in/scanner', [CheckInController::class, 'scanner'])->name('check-in.scanner');
Route::post('/api/check-in', [CheckInController::class, 'checkIn'])->name('api.check-in');