@extends('admin.layout')

@section('title', 'Review Report')
@section('page_title', 'Review Report')

@section('content')

    <div class="mb-4">

        <a href="{{ route('admin.reports.index') }}" class="text-secondary text-decoration-none">
            &larr; Back to reports
        </a>

        <h2 class="mt-3">
            Review Report #{{ $report->id }}
        </h2>

    </div>

    <div class="row g-4">

        <div class="col-xl-7">

            <div class="tradim-card p-4">

                <h5 class="mb-4">
                    Report Details
                </h5>

                <div class="mb-3">
                    <div class="tradim-muted small">
                        Reported content
                    </div>

                    <div class="fw-bold">
                        {{ $report->target_title }}
                    </div>
                </div>

                <div class="mb-3">
                    <div class="tradim-muted small">
                        Content type
                    </div>

                    {{ ucfirst($report->target_type) }}
                    #{{ $report->target_id }}
                </div>

                <div class="mb-3">
                    <div class="tradim-muted small">
                        Reported by
                    </div>

                    {{ $report->user?->name ?? 'Deleted user' }}
                </div>

                <div class="mb-3">
                    <div class="tradim-muted small">
                        Reason
                    </div>

                    {{ $report->reason_label }}
                </div>

                <div class="mb-3">
                    <div class="tradim-muted small">
                        Description
                    </div>

                    <div class="mt-2">
                        {{ $report->description ?: 'No additional details.' }}
                    </div>
                </div>

                <div class="mb-3">
                    <div class="tradim-muted small">
                        Current status
                    </div>

                    <span class="badge text-bg-secondary">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>

                @if(!$target)
                    <div class="alert alert-warning">
                        The reported content no longer exists.
                    </div>
                @endif

                @if($report->reviewer)
                    <div class="mt-4 pt-3 border-top border-secondary">
                        Reviewed by:
                        {{ $report->reviewer->name }}

                        @if($report->reviewed_at)
                            <div class="tradim-muted small">
                                {{ $report->reviewed_at->format('d M Y H:i') }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>

        </div>

        <div class="col-xl-5">

            <div class="tradim-card p-4">

                <h5 class="mb-4">
                    Moderation Decision
                </h5>

                @if(in_array($report->status, ['resolved', 'rejected']))

                    <div class="alert alert-secondary">
                        This report is closed.
                    </div>

                    <div class="tradim-muted">
                        {{ $report->admin_note ?: 'No admin note.' }}
                    </div>

                @else

                    <form method="POST" action="{{ route('admin.reports.update', $report) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">
                                Status
                            </label>

                            <select name="status" class="form-select tradim-input" required>
                                <option value="reviewing">
                                    Reviewing
                                </option>

                                <option value="resolved">
                                    Resolved
                                </option>

                                <option value="rejected">
                                    Rejected
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Action
                            </label>

                            <select name="action_taken" class="form-select tradim-input" required>
                                <option value="none">
                                    No content removal
                                </option>

                                @if($target && in_array($report->target_type, ['video', 'comment']))
                                    <option value="content_removed">
                                        Remove reported content
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Admin note
                            </label>

                            <textarea name="admin_note" rows="5" maxlength="5000" class="form-control tradim-input"
                                placeholder="Reason for the moderation decision...">{{ old('admin_note') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"
                            onclick="return confirm('Confirm this moderation decision?')">
                            Save Decision
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

@endsection