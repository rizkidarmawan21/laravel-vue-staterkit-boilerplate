<?php

namespace App\Exceptions\Roles;

use App\Exceptions\BaseException;

class RoleServiceException extends BaseException
{
    public static function createFailed(?\Throwable $previous = null): self
    {
        return new self('Gagal menambahkan role', 0, $previous);
    }

    public static function updateFailed(?\Throwable $previous = null): self
    {
        return new self('Gagal memperbarui role', 0, $previous);
    }

    public static function deleteFailed(?\Throwable $previous = null): self
    {
        return new self('Gagal menghapus role', 0, $previous);
    }
}
