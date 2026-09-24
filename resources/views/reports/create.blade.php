<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report Content - Tradim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #070b18;
            color: #f8fafc;
            min-height: 100vh;
        }

        .report-card {
            background: #10172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 16px;
        }

        .form-control,
        .form-select {
            background: #0b1020;
            border-color: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background: #0b1020;
            color: #fff;
            border-color: #7c3aed;
            box-shadow: none;
        }

        .btn-purple {
            background: #7c3aed;
            color: #fff;
        }

        .btn-purple:hover {
            background: #6d28d9;
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container py-5">

        <div class="mx-auto" style="max-width:650px">

            <a href="{{ url()->previous() }}" class="text-secondary text-decoration-none">
                &larr; Back
            </a>

            <div class="report-card p-4 mt-4">

                <h3>Report content</h3>

                <p class="text-secondary">
                    Tell us why you are reporting this {{ $type }}.
                </p>

                <div class="alert alert-secondary">
                    {{ $type === 'video' ? $target->title : ($type === 'channel' ? $target->name : Str::limit($target->comment ?: $target->body, 120)) }}
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('reports.store') }}">
                    @csrf

                    <input type="hidden" name="target_type" value="{{ $type }}">
                    <input type="hidden" name="target_id" value="{{ $targetId }}">

                    <div class="mb-4">
                        <label class="form-label">Reason</label>

                        <select name="reason" class="form-select" required>
                            <option value="">Select a reason</option>

                            @foreach([
                                    'spam' => 'Spam',
                                    'harassment' => 'Harassment',
                                    'hate_speech' => 'Hate speech',
                                    'violence' => 'Violence',
                                    'sexual_content' => 'Sexual content',
                                    'misinformation' => 'Misinformation',
                                    'copyright' => 'Copyright',
                                    'other' => 'Other',
                                ] as $value => $label)

                                <option value="{{ $value }}" @selected(old('reason') === $value)>
                                    {{ $label }}
                                </option>

                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Additional details (optional)</label>

                        <textarea name="description" rows="5" maxlength="2000" class="form-control"
                            placeholder="Describe the issue...">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-purple w-100">
                        Submit report
                    </button>
                </form>

            </div>

        </div>

    </div>

</body>

</html>