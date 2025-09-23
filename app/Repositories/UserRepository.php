<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function update(User $user, array $data);
    public function findById(int $id);
    public function getAllWithFilters(array $filters = [], int $perPage = 4);
    public function delete(User $user): bool;
    public function getAllRoles();
    
    // User Role methods
    public function createUserRole(int $userId, int $roleId);
    public function updateOrCreateUserRole(int $userId, int $roleId);
    public function deleteUserRole(int $userId): bool;
}

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function findById(int $id): ?User
    {
        return User::with('role')->find($id);
    }

    public function getAllWithFilters(array $filters = [], int $perPage = 4): LengthAwarePaginator
    {
        $query = User::with('role');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    public function createUserRole(int $userId, int $roleId): UserRole
    {
        return UserRole::create([
            'user_id' => $userId,
            'role_id' => $roleId,
            'granted_at' => now(),
        ]);
    }

    public function updateOrCreateUserRole(int $userId, int $roleId): UserRole
    {
        return UserRole::updateOrCreate(
            ['user_id' => $userId],
            ['role_id' => $roleId, 'granted_at' => now()]
        );
    }

    public function deleteUserRole(int $userId): bool
    {
        return UserRole::where('user_id', $userId)->delete() > 0;
    }
}