<?php

namespace App\Services\Roles;

use App\Repositories\Roles\RoleRepository;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(protected RoleRepository $repository) {}

    public function getAll(int $perPage = 10, bool $withPermissions = false)
    {
        return $this->repository->getAll($perPage, $withPermissions);
    }

    public function create(array $data)
    {
        $role = $this->repository->create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function update(Role $role, array $data)
    {
        $updated = $this->repository->update($role, [
            'name' => $data['name'],
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $updated;
    }

    public function delete(Role $role)
    {
        return $this->repository->delete($role);
    }
}
