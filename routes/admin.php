<?php

use App\Http\Controllers\Admin\ApiRolePermissionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', function () {
    return view('admin.dashbroad');
})->name('admin.dashboard');

Route::resource('/admin/users', UserController::class);
// Route::resource('/admin/permissions', PermissionController::class);
// Route::resource('admin/role_premissions', RolePermissionController::class);

// Route::get('admin/api/role_permissions/getByRoleId/{role_id?}', [ApiRolePermissionController::class, 'getByRoleId'])->name('admin.api.role_has_permission.getRoleId');

Route::get('admin/api/role_permissions/getByRoleId/{role_id?}', [ApiRolePermissionController::class, 'getByRoleId'])->name('admin.api.role_has_permission.getRoleId');
Route::resource('admin/role_premissions', RolePermissionController::class);
Route::resource('admin/categories', CategoryController::class);
Route::resource('admin/events', EventController::class);
Route::resource('admin/venues', VenueController::class);
