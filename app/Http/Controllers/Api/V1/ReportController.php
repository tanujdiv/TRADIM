<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Services\ReportingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | My Reports
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): JsonResponse
    {
        $reports = Report::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(
                max(
                    1,
                    min(
                        $request->integer('per_page', 15),
                        50
                    )
                )
            );

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Report
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreReportRequest $request,
        ReportingService $service
    ): JsonResponse {
        $report = $service->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully.',
            'data' => $report,
        ], 201);
    }
}