<?php

namespace App\Repositories;

use App\Models\Permission;

interface PermissionRepositoryInterface{
    public function getPermisison();
    public function createPermission(array $data);
    public function update(Permission $permission, array $data);
    public function delete(Permission $permission);
    public function findById($id);
}
class PermissionRepository implements PermissionRepositoryInterface{
    public function getPermisison(){
        return Permission::paginate(6);
    }

    public function createPermission(array $data){
        return Permission::create($data);
    }

    public function update(Permission $permission, array $data){
        $permission->update($data);
        return $permission->fresh();
    }

    public function delete(Permission $permission){
        return $permission->delete();
    }
    public function findById($id){
        return Permission::findOrFail($id);
    }
}