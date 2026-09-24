@extends('admin.layout')

@section('title', 'Channels')
@section('page_title', 'Channels')

@section('content')

    <div class="mb-4">

        <h2 class="mb-1">
            Channels
        </h2>

        <div class="tradim-muted">
            Manage creator channels
        </div>

    </div>

    <div class="tradim-card">

        <div class="p-4 border-bottom border-secondary border-opacity-25">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-6">

                        <input type="text" name="search" value="{{ $search }}" class="form-control tradim-input"
                            placeholder="Search channel...">

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
                        <th class="px-4">Channel</th>
                        <th>Owner</th>
                        <th>Subscribers</th>
                        <th>Videos</th>
                        <th>Total Views</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($channels as $channel)

                        <tr>

                            <td class="px-4">

                                <strong>
                                    {{ $channel->name }}
                                </strong>

                                <div class="small tradim-muted">
                                    {{ '@' . $channel->handle }}
                                </div>

                            </td>

                            <td>
                                {{ $channel->user?->name ?? '-' }}
                            </td>

                            <td>
                                {{ number_format($channel->subscriber_count) }}
                            </td>

                            <td>
                                {{ number_format($channel->video_count) }}
                            </td>

                            <td>
                                {{ number_format($channel->total_views) }}
                            </td>

                            <td class="text-end pe-4">

                                <form method="POST" action="{{ route('admin.channels.destroy', $channel) }}"
                                    onsubmit="return confirm('Delete this channel and its videos?');">

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
                                No channels found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-4 tradim-pagination">
            {{ $channels->links() }}
        </div>

    </div>

@endsection