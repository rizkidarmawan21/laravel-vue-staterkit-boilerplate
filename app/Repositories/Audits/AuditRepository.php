<?php

namespace App\Repositories\Audits;

use App\Models\Audit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class AuditRepository
{
    public function getPaginated(
        int $perPage = 10,
        ?string $search = null,
        ?string $event = null,
        ?string $model = null,
        ?string $userId = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): LengthAwarePaginator {
        $query = Audit::query()
            ->with(['user', 'auditable'])
            ->latest();

        // Apply filters
        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($event) {
            $query->where('event', $event);
        }

        if ($model) {
            $query->where('auditable_type', $model);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Audit
    {
        return Audit::with(['user', 'auditable'])->find($id);
    }

    public function getEventOptions(): array
    {
        return Audit::select('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event', 'event')
            ->toArray();
    }

    public function getModelOptions(): array
    {
        return Audit::select('auditable_type')
            ->distinct()
            ->whereNotNull('auditable_type')
            ->orderBy('auditable_type')
            ->pluck('auditable_type', 'auditable_type')
            ->mapWithKeys(function ($type) {
                $className = class_basename($type);
                $label = match ($className) {
                    'User' => 'Pengguna',
                    'Partner' => 'Partner',
                    'Product' => 'Produk',
                    'Sales' => 'Penjualan',
                    'Warehouse' => 'Gudang',
                    default => $className
                };
                return [$type => $label];
            })
            ->toArray();
    }

    public function getStats(): array
    {
        $totalAudits = Audit::count();
        $todayAudits = Audit::whereDate('created_at', today())->count();
        $weekAudits = Audit::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthAudits = Audit::whereMonth('created_at', now()->month)->count();

        return [
            'total' => $totalAudits,
            'today' => $todayAudits,
            'week' => $weekAudits,
            'month' => $monthAudits,
        ];
    }
}
