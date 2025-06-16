<?php

namespace App\Services\Roles;

use App\Repositories\Roles\RoleRepository;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Exceptions\Roles\RoleServiceException;

class RoleService
{
    protected string $requestId;

    public function __construct(protected RoleRepository $repository)
    {
        $this->requestId = app('request-id') ?? 'unknown';
    }

    public function getAll(int $perPage = 10, bool $withPermissions = false)
    {
        try {
            return $this->repository->getAll($perPage, $withPermissions);
        } catch (Throwable $e) {
            Log::error('Error fetching roles', [
                'request_id' => $this->requestId,
                'per_page' => $perPage,
                'with_permissions' => $withPermissions,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            $role = $this->repository->create([
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role;
        } catch (Throwable $e) {
            Log::error('Error creating role', [
                'request_id' => $this->requestId,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw RoleServiceException::createFailed($e);
        }
    }

    public function update(Role $role, array $data)
    {
        try {
            $updated = $this->repository->update($role, [
                'name' => $data['name'],
            ]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $updated;
        } catch (Throwable $e) {
            Log::error('Error updating role', [
                'request_id' => $this->requestId,
                'role' => $role->name,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw RoleServiceException::updateFailed($e);
        }
    }

    public function delete(Role $role)
    {
        try {
            $result = $this->repository->delete($role);

            return $result;
        } catch (Throwable $e) {
            Log::error('Error deleting role', [
                'request_id' => $this->requestId,
                'role' => $role->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw RoleServiceException::deleteFailed($e);
        }
    }
}
