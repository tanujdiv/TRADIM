<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Report;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');

        $allowed = [
            'all',
            'pending',
            'reviewing',
            'resolved',
            'rejected',
        ];

        if (!in_array($status, $allowed, true)) {
            $status = 'pending';
        }

        $reports = Report::query()
            ->with([
                'user',
                'reviewer',
            ])
            ->when(
                $status !== 'all',
                fn($query) => $query->where('status', $status)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending' => Report::where('status', 'pending')->count(),
            'reviewing' => Report::where('status', 'reviewing')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];

        return view(
            'admin.reports.index',
            compact(
                'reports',
                'counts',
                'status'
            )
        );
    }

    public function show(Report $report): View
    {
        $report->load([
            'user',
            'reviewer',
        ]);

        $target = $this->findTarget($report);

        return view(
            'admin.reports.show',
            compact(
                'report',
                'target'
            )
        );
    }

    public function update(
        Request $request,
        Report $report
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'reviewing',
                    'resolved',
                    'rejected',
                ]),
            ],

            'action_taken' => [
                'required',
                Rule::in([
                    'none',
                    'content_removed',
                ]),
            ],

            'admin_note' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        if (
            $validated['action_taken'] === 'content_removed'
            && $validated['status'] !== 'resolved'
        ) {
            return back()->withErrors([
                'action_taken' =>
                    'Content removal requires resolved status.',
            ]);
        }

        if (
            in_array($report->status, [
                'resolved',
                'rejected',
            ], true)
        ) {
            return back()->with(
                'error',
                'This report has already been closed.'
            );
        }

        if (
            $validated['action_taken'] === 'content_removed'
        ) {
            $target = $this->findTarget($report);

            if (!$target) {
                return back()->withErrors([
                    'action_taken' =>
                        'The reported content no longer exists.',
                ]);
            }

            /*
             * Content removal is deliberately limited to
             * videos and comments.
             *
             * Channel deletion requires a separate cleanup
             * workflow for subscriptions, videos and media.
             */
            if ($report->target_type === 'channel') {
                return back()->withErrors([
                    'action_taken' =>
                        'Channel removal is not available in this workflow.',
                ]);
            }

            DB::transaction(function () use ($report, $validated, $request, $target) {
                if ($target instanceof Video) {
                    $channel = $target->channel;

                    $target->delete();

                    if ($channel) {
                        $channel->update([
                            'video_count' => $channel
                                ->videos()
                                ->count(),
                        ]);
                    }
                }

                if ($target instanceof Comment) {
                    $video = $target->video;

                    $target->delete();

                    if ($video) {
                        $video->update([
                            'comments_count' => $video
                                ->comments()
                                ->whereNull('parent_id')
                                ->count(),
                        ]);
                    }
                }

                $this->closeReport(
                    $report,
                    $validated,
                    $request->user()->id
                );
            });

            if ($target instanceof Video) {
                if ($target->video_path) {
                    Storage::disk('public')
                        ->delete($target->video_path);
                }

                if ($target->thumbnail_path) {
                    Storage::disk('public')
                        ->delete($target->thumbnail_path);
                }
            }

            return redirect()
                ->route('admin.reports.index')
                ->with(
                    'success',
                    'Report resolved and content removed.'
                );
        }

        $this->closeReport(
            $report,
            $validated,
            $request->user()->id
        );

        return redirect()
            ->route('admin.reports.index')
            ->with(
                'success',
                'Report updated successfully.'
            );
    }

    private function closeReport(
        Report $report,
        array $data,
        int $adminId
    ): void {
        $report->update([
            'status' => $data['status'],
            'action_taken' => $data['action_taken'],
            'admin_note' => $data['admin_note'] ?? null,
            'reviewed_by' => $adminId,
            'reviewed_at' => in_array(
                $data['status'],
                ['resolved', 'rejected'],
                true
            ) ? now() : null,
        ]);
    }

    private function findTarget(
        Report $report
    ): Video|Channel|Comment|null {
        return match ($report->target_type) {
            'video' => Video::find($report->target_id),

            'channel' => Channel::find($report->target_id),

            'comment' => Comment::find($report->target_id),

            default => null,
        };
    }
}