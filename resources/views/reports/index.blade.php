<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>My Reports - Tradim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #070b18;
            color: #fff;
        }

        .report-card {
            background: #10172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
        }
    </style>
</head>

<body>

    <div class="container py-5">

        <a href="{{ route('home') }}" class="text-secondary text-decoration-none">
            &larr; Tradim
        </a>

        <h2 class="my-4">My Reports</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @forelse($reports as $report)

            <div class="report-card p-4 mb-3">

                <div class="d-flex justify-content-between gap-3">

                    <div>
                        <div class="fw-bold">
                            {{ $report->target_title }}
                        </div>

                        <div class="text-secondary small mt-1">
                            {{ ucfirst($report->target_type) }}
                            &bull;
                            {{ $report->reason_label }}
                            &bull;
                            {{ $report->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div>
                        <span class="badge text-bg-secondary">
                            {{ ucfirst($report->status) }}
                        </span>
                    </div>

                </div>

                @if($report->description)
                    <p class="text-secondary mt-3 mb-0">
                        {{ $report->description }}
                    </p>
                @endif

            </div>

        @empty

            <div class="report-card p-5 text-center text-secondary">
                You have not submitted any reports.
            </div>

        @endforelse

        <div class="mt-4">
            {{ $reports->links() }}
        </div>

    </div>

</body>

</html>