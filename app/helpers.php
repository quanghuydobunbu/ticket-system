<?php

use Illuminate\Support\Facades\DB;
function HasPermission($user, $permission){
        $email = $user->email;
        $result = 0;   
        //check role
        $result = DB::table("users")
        ->join("user_roles","user_roles.user_id","=","users.id")
        ->join("role_permissions","user_roles.role_id","=","role_permissions.role_id")
        ->join("permissions","role_permissions.permission_id","=","permissions.id")
        ->where('users.email',"=", $email)
        ->where('permissions.name', '=', $permission)
        ->count();
        $hasPermission = $result > 0;
        return $hasPermission;
}
