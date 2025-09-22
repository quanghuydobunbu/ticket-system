<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_role_has_permission = RolePermission::with('role')->with('permission')->get();
        $grouped_role_has_permission = $list_role_has_permission->groupBy('role.description');
        return view('admin.role_permission.index')
        ->with('grouped_role_has_permission', $grouped_role_has_permission); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $list_roles = Role::all();
        $list_permissions = Permission::all();
        return view('admin.role_permission.create')
            ->with('list_roles', $list_roles)
            ->with('list_permissions', $list_permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role_id = $request->role_id;
        $permission_ids = $request->permission_id ?? [];
        
        // Xóa tất cả quyền cũ của role này
        RolePermission::where('role_id', $role_id)->delete();
        
        // Thêm quyền mới
        foreach ($permission_ids as $permission_id) {
            RolePermission::create([
                'role_id' => $role_id,
                'permission_id' => $permission_id
            ]);
        }
        
        return redirect(route('role_premissions.index'))->with('success', 'Cập nhật quyền cho vai trò thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}