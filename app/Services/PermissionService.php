<?php
namespace App\Services;

use App\Models\Permission;
use App\Repositories\PermissionRepositoryInterface;

class PermissionService{
    protected $permissionRepository;
    public function __construct(PermissionRepositoryInterface $permissionRepository){
        $this->permissionRepository = $permissionRepository;
    }
    public function getAllPermisison(){
        return $this->permissionRepository->getPermisison();
    }
    public function createPermission(array $data){
        $permissionData = [
            'name' => $data['permission_name'],
            'description' => $data['description']
        ];
        $permission = $this->permissionRepository->createPermission($permissionData);
        return $permission;
    }
    public function deletePermission($id){
        $permission = $this->permissionRepository->findById($id);
        return $this->permissionRepository->delete($permission);
    }
    public function findById($id){
        return $this->permissionRepository->findById($id);
    }
    public function updatePermission(array $data, $id){
        $permission = $this->permissionRepository->findById($id);
        $permissionData = [
            'name' => $data['name'],
            'description' => $data['description']
        ];
        return $this->permissionRepository->update($permission, $permissionData);
    }
}