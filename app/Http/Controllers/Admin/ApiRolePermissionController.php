<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class ApiRolePermissionController extends Controller
{
    /**
     * Get permissions by role ID
     */
    public function getByRoleId($role_id = null)
    {
        if (empty($role_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Role ID không được để trống',
                'data' => []
            ]);
        }

        try {
            // Lấy danh sách permission_id của role này
            $permissions = RolePermission::where('role_id', $role_id)
                ->pluck('permission_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu thành công',
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }
}