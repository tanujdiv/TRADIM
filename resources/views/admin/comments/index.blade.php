@extends('admin.layout')

@section('title', 'Comments')
@section('page_title', 'Comments')

@section('content')

    <div class="mb-4">

        <h2 class="mb-1">
            Comments
        </h2>

        <div class="tradim-muted">
            Moderate video comments
        </div>

    </div>

    <div class="tradim-card">

        <div class="p-4 border-bottom border-secondary border-opacity-25">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-6">

                        <input type="text" name="search" value="{{ $search }}" class="form-control tradim-input"
                            placeholder="Search comments...">

                    </div>

                    <div class="col-md-auto">

                        <button class="btn btn-primary">
                            Search
                        </button>

                    </div>

                </div>

            </form>

        </div>

        <div class="table-responsive">

            <table class="table tradim-table">

                <thead>

                    <tr>
                        <th class="px-4">Comment</th>
                        <th>User</th>
                        <th>Video</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($comments as $comment)

                        <tr>

                            <td class="px-4">

                                {{ Str::limit($comment->comment, 80) }}

                            </td>

                            <td>
                                {{ $comment->user?->name ?? '-' }}
                            </td>

                            <td>
                                {{ Str::limit($comment->video?->title ?? '-', 35) }}
                            </td>

                            <td>
                                {{ $comment->created_at?->format('d M Y H:i') }}
                            </td>

                            <td class="text-end pe-4">

                                <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                                    onsubmit="return confirm('Delete this comment?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-5">
                                No comments found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-4 tradim-pagination">
            {{ $comments->links() }}
        </div>

    </div>

@endsection