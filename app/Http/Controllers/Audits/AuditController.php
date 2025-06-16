<?php

namespace App\Http\Controllers\Audits;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Services\Audits\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditController extends Controller
{
    public function __construct(protected AuditService $service) {}

    public function index(): Response
    {
        $filterOptions = $this->service->getFilterOptions();

        return Inertia::render('audits/Index', [
            'filterOptions' => $filterOptions,
        ]);
    }

    public function getData(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');
        $event = $request->input('event');
        $model = $request->input('model');
        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $audits = $this->service->getPaginated(
            $perPage,
            $search,
            $event,
            $model,
            $userId,
            $startDate,
            $endDate
        );

        // Transform data for frontend
        $audits->getCollection()->transform(function ($audit) {
            return [
                'id' => $audit->id,
                'no' => '', // Will be calculated in frontend
                'user_name' => $audit->user_name,
                'event' => $audit->event,
                'event_label' => $audit->event_label,
                'model_name' => $audit->model_name,
                'auditable_type' => $audit->auditable_type,
                'auditable_id' => $audit->auditable_id,
                'url' => $audit->url,
                'ip_address' => $audit->ip_address,
                'created_at' => $audit->created_at,
                'created_at_human' => $audit->created_at->diffForHumans(),
                'created_at_formatted' => $audit->created_at->format('d M Y H:i:s'),
            ];
        });

        return response()->json($audits);
    }

    public function show(Audit $audit): Response
    {
        $auditData = $this->service->formatAuditForDisplay($audit);

        return Inertia::render('audits/Detail', [
            'audit' => $auditData,
        ]);
    }

    public function getStats(): JsonResponse
    {
        $stats = $this->service->getStats();
        return response()->json($stats);
    }
}
