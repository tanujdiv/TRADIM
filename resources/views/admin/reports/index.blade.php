@extends('admin.layout')

@section('title', 'Reports')
@section('page_title', 'Reports')

@section('content')

    <div class="mb-4">
        <h2 class="mb-1">Content Reports</h2>
        <div class="tradim-muted">
            Review reported content and user complaints.
        </div>
    </div>

    <div class="row g-3 mb-4">

        @foreach($counts as $name => $count)

            <div class="col-md-3">
                <div class="tradim-card p-4">

                    <div class="tradim-muted">
                        {{ ucfirst($name) }}
                    </div>

                    <div class="fs-2 fw-bold mt-2">
                        {{ number_format($count) }}
                    </div>

                </div>
            </div>

        @endforeach

    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">

        @foreach([
                'all',
                'pending',
                'reviewing',
                'resolved',
                'rejected',
            ] as $item)

            <a href="{{ route('admin.reports.index', ['status' => $item]) }}"
                class="btn {{ $status === $item ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ ucfirst($item) }}
            </a>

        @endforeach

    </div>

    <div class="tradim-card">

        <div class="table-responsive">

            <table class="table tradim-table">

                <thead>
                    <tr>
                        <th class="px-4">Reported Content</th>
                        <th>Reported By</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($reports as $report)

                        <tr>
                            <td class="px-4">
                                <strong>
                                    {{ Str::limit($report->target_title, 45) }}
                                </strong>

                                <div class="small tradim-muted">
                                    {{ ucfirst($report->target_type) }}
                                    #{{ $report->target_id }}
                                </div>
                            </td>

                            <td>
                                {{ $report->user?->name ?? 'Deleted user' }}
                            </td>

                            <td>
                                {{ $report->reason_label }}
                            </td>

                            <td>
                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>

                            <td>
                                {{ $report->created_at->format('d M Y') }}
                            </td>

                            <td class="text-end pe-4">
                                <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-light">
                                    Review
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">
                                No reports found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-4 tradim-pagination">
            {{ $reports->links() }}
        </div>

    </div>

@endsection