<?php

namespace App\Services\Audits;

use App\Models\Audit;
use App\Repositories\Audits\AuditRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuditService
{
    protected string $requestId;

    public function __construct(protected AuditRepository $repository)
    {
        $this->requestId = app('request-id') ?? 'unknown';
    }

    public function getPaginated(
        int $perPage = 10,
        ?string $search = null,
        ?string $event = null,
        ?string $model = null,
        ?string $userId = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): LengthAwarePaginator {
        try {
            return $this->repository->getPaginated(
                $perPage,
                $search,
                $event,
                $model,
                $userId,
                $startDate,
                $endDate
            );
        } catch (Throwable $e) {
            Log::error('Error fetching paginated audits', [
                'request_id' => $this->requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?Audit
    {
        try {
            return $this->repository->findById($id);
        } catch (Throwable $e) {
            Log::error('Error finding audit', [
                'request_id' => $this->requestId,
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getFilterOptions(): array
    {
        try {
            return [
                'events' => $this->repository->getEventOptions(),
                'models' => $this->repository->getModelOptions(),
            ];
        } catch (Throwable $e) {
            Log::error('Error getting filter options', [
                'request_id' => $this->requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getStats(): array
    {
        try {
            return $this->repository->getStats();
        } catch (Throwable $e) {
            Log::error('Error getting audit stats', [
                'request_id' => $this->requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function formatAuditForDisplay(Audit $audit): array
    {
        return [
            'id' => $audit->id,
            'user_name' => $audit->user_name,
            'event' => $audit->event,
            'event_label' => $audit->event_label,
            'model_name' => $audit->model_name,
            'auditable_type' => $audit->auditable_type,
            'auditable_id' => $audit->auditable_id,
            'old_values' => $audit->old_values,
            'new_values' => $audit->new_values,
            'url' => $audit->url,
            'ip_address' => $audit->ip_address,
            'user_agent' => $audit->user_agent,
            'created_at' => $audit->created_at,
            'created_at_human' => $audit->created_at->diffForHumans(),
            'created_at_formatted' => $audit->created_at->format('d M Y H:i:s'),
        ];
    }
}
