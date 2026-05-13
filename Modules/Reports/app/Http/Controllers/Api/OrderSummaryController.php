<?php

namespace Modules\Reports\app\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Reports\app\Services\OrderReportService;

class OrderSummaryController extends Controller
{
    public function __construct(private readonly OrderReportService $service) {}

    public function __invoke(Request $request): JsonResponse
    {
        $days = (int) $request->query('days', 30);
        $status = $request->query('status', 'all');

        $days = max(1, min($days, 365));

        return response()->json([
            'summary' => $this->service->summary(),
            'filtered' => $this->service->filtered($days, $status),
            'filters' => compact('days', 'status'),
        ]);
    }
}
