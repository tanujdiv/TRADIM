<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Services\ReportingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function create(
        string $type,
        int $id,
        ReportingService $service
    ): View {
        abort_unless(
            in_array($type, [
                'video',
                'channel',
                'comment',
            ], true),
            404
        );

        $target = $service->findTarget($type, $id);

        abort_unless($target, 404);

        return view('reports.create', [
            'target' => $target,
            'type' => $type,
            'targetId' => $id,
        ]);
    }

    public function store(
        StoreReportRequest $request,
        ReportingService $service
    ): RedirectResponse {
        $service->create(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('reports.index')
            ->with(
                'success',
                'Your report has been submitted.'
            );
    }

    public function index(Request $request): View
    {
        $reports = Report::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view(
            'reports.index',
            compact('reports')
        );
    }
}