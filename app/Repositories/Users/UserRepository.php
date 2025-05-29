<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function __construct(protected User $model) {}

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('roles')->latest()->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return $this->model->with('roles')->find($id);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        $user = $this->model->create($data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
