<?php

namespace App\Exceptions\Users;

use App\Exceptions\BaseException;

class UserServiceException extends BaseException
{
    public static function createFailed(?\Throwable $previous = null): self
    {
        return new self('Gagal menambahkan pengguna', 0, $previous);
    }

    public static function updateFailed(?\Throwable $previous = null): self
    {
        return new self('Gagal memperbarui pengguna', 0, $previous);
    }

    public static function deleteFailed(?\Throwable $previous = null): self
    {
        return new self('Gagal menghapus pengguna', 0, $previous);
    }
}
