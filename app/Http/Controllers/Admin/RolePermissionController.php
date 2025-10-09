<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'array'
        ]);

        $role_id = $request->role_id;
        $permission_ids = $request->permission_id ?? [];

        // XÓA HẾT permissions cũ của role này
        DB::table('role_permissions')
            ->where('role_id', $role_id)
            ->delete();

        // THÊM MỚI permissions đã chọn
        foreach ($permission_ids as $permission_id) {
            DB::table('role_permissions')->insert([
                'role_id' => $role_id,
                'permission_id' => $permission_id,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }

        return redirect()->route('role_premissions.index')
            ->with('success', 'Cập nhật quyền thành công!');
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

    /**
 * Get permissions của một role (API)
 */
    public function getRolePermissions($role_id)
    {
        try {
            // Lấy danh sách permission_id của role
            $permissions = DB::table('role_permissions')
                ->where('role_id', $role_id)
                ->pluck('permission_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}