<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\Users\UserRepository;
use App\Exceptions\Users\UserServiceException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserService
{
    protected string $requestId;

    public function __construct(protected UserRepository $repository)
    {
        $this->requestId = app('request-id') ?? 'unknown';
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        try {
            return $this->repository->getPaginated($perPage);
        } catch (Throwable $e) {
            Log::error('Error fetching paginated users', [
                'request_id' => $this->requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?User
    {
        try {
            return $this->repository->findById($id);
        } catch (Throwable $e) {
            Log::error('Error finding user', [
                'request_id' => $this->requestId,
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function create(array $data): User
    {
        try {
            $user = $this->repository->create($data);
            Log::info('User created successfully', [
                'request_id' => $this->requestId,
                'id' => $user->id
            ]);
            return $user;
        } catch (Throwable $e) {
            Log::error('Error creating user', [
                'request_id' => $this->requestId,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw UserServiceException::createFailed($e);
        }
    }

    public function update(User $user, array $data): User
    {
        try {
            $updatedUser = $this->repository->update($user, $data);
            Log::info('User updated successfully', [
                'request_id' => $this->requestId,
                'id' => $user->id
            ]);
            return $updatedUser;
        } catch (Throwable $e) {
            Log::error('Error updating user', [
                'request_id' => $this->requestId,
                'id' => $user->id,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw UserServiceException::updateFailed($e);
        }
    }

    public function delete(User $user): bool
    {
        try {
            $result = $this->repository->delete($user);
            if ($result) {
                Log::info('User deleted successfully', [
                    'request_id' => $this->requestId,
                    'id' => $user->id
                ]);
            }
            return $result;
        } catch (Throwable $e) {
            Log::error('Error deleting user', [
                'request_id' => $this->requestId,
                'id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw UserServiceException::deleteFailed($e);
        }
    }
}
