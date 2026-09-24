@extends('admin.layout')

@section('title', 'Videos')
@section('page_title', 'Videos')

@section('content')

    <div class="mb-4">
        <h2 class="mb-1">Videos</h2>
        <div class="tradim-muted">
            Manage uploaded videos
        </div>
    </div>

    <div class="tradim-card">

        <div class="p-4 border-bottom border-secondary border-opacity-25">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-6">

                        <input type="text" name="search" value="{{ $search }}" class="form-control tradim-input"
                            placeholder="Search videos...">

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
                        <th class="px-4">Video</th>
                        <th>Channel</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($videos as $video)

                        <tr>

                            <td class="px-4">

                                <strong>
                                    {{ Str::limit($video->title, 45) }}
                                </strong>

                                <div class="small tradim-muted">
                                    {{ $video->slug }}
                                </div>

                            </td>

                            <td>
                                {{ $video->channel?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $video->category?->name ?? '-' }}
                            </td>

                            <td>
                                <span class="badge text-bg-secondary">
                                    {{ ucfirst($video->status) }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($video->views_count) }}
                            </td>

                            <td class="text-end pe-4">

                                <form method="POST" action="{{ route('admin.videos.destroy', $video) }}"
                                    onsubmit="return confirm('Delete this video permanently?');">

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
                            <td colspan="6" class="text-center py-5">
                                No videos found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-4 tradim-pagination">
            {{ $videos->links() }}
        </div>

    </div>

@endsection