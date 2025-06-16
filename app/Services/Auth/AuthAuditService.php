<?php

namespace App\Services\Auth;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthAuditService
{
    protected string $requestId;

    public function __construct()
    {
        $this->requestId = app('request-id') ?? 'unknown';
    }

    /**
     * Log login audit
     */
    public function logLogin(User $user, Request $request): void
    {
        try {
            $this->createAuditLog([
                'user_type' => get_class($user),
                'user_id' => $user->id,
                'event' => 'login',
                'auditable_type' => get_class($user),
                'auditable_id' => $user->id,
                'old_values' => null,
                'new_values' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'login_time' => now()->toDateTimeString(),
                ],
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'tags' => 'authentication,login',
            ]);

            Log::info('User login audit logged', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to log login audit', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Log logout audit
     */
    public function logLogout(User $user, Request $request): void
    {
        try {
            $this->createAuditLog([
                'user_type' => get_class($user),
                'user_id' => $user->id,
                'event' => 'logout',
                'auditable_type' => get_class($user),
                'auditable_id' => $user->id,
                'old_values' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'session_active' => true,
                ],
                'new_values' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'logout_time' => now()->toDateTimeString(),
                    'session_active' => false,
                ],
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'tags' => 'authentication,logout',
            ]);

            Log::info('User logout audit logged', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to log logout audit', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Log failed login attempt
     */
    public function logFailedLogin(string $email, Request $request): void
    {
        try {
            $this->createAuditLog([
                'user_type' => null,
                'user_id' => null,
                'event' => 'login_failed',
                'auditable_type' => User::class,
                'auditable_id' => null,
                'old_values' => null,
                'new_values' => [
                    'email' => $email,
                    'failed_login_attempt' => true,
                    'attempt_time' => now()->toDateTimeString(),
                ],
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'tags' => 'authentication,login_failed,security',
            ]);

            Log::warning('Failed login attempt audit logged', [
                'request_id' => $this->requestId,
                'email' => $email,
                'ip_address' => $request->ip(),
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to log failed login audit', [
                'request_id' => $this->requestId,
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Create audit log entry
     */
    protected function createAuditLog(array $data): void
    {
        Audit::create($data);
    }
}
