<?php

namespace App\Repositories\Roles;

use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function getPaginated(int $perPage = 10)
    {
        $query = Role::query()->orderBy('name');

        return $query->paginate($perPage);
    }

    public function getAll(int $perPage = 10, bool $withPermissions = false )
    {
        $query = Role::query()->orderBy('name');

        if ($withPermissions) {
            $query->with('permissions');
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Role
    {
        return Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);
    }

    public function update(Role $role, array $data): bool
    {
        return $role->update([
            'name' => $data['name'],
        ]);
    }

    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
