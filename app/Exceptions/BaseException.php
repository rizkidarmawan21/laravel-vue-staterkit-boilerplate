<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    protected string $requestId;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        $this->requestId = app('request-id') ?? 'unknown';
        $message = sprintf('%s (Request ID: %s)', $message, $this->requestId);
        parent::__construct($message, $code, $previous);
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }
}
