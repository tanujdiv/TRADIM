@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <div class="tradim-muted">
                Tradim platform overview
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        @php
            $cards = [
                ['Users', $stats['users']],
                ['Active Users', $stats['active_users']],
                ['Channels', $stats['channels']],
                ['Videos', $stats['videos']],
                ['Published', $stats['published_videos']],
                ['Processing', $stats['processing_videos']],
                ['Categories', $stats['categories']],
                ['Comments', $stats['comments']],
                ['Pending Reports', $stats['pending_reports']],
            ];
        @endphp

        @foreach($cards as $card)

            <div class="col-xl-3 col-md-6">

                <div class="tradim-card tradim-stat">

                    <div class="tradim-stat-label">
                        {{ $card[0] }}
                    </div>

                    <div class="tradim-stat-value">
                        {{ number_format($card[1]) }}
                    </div>

                </div>

            </div>

        @endforeach

    </div>

    <div class="row g-4">

        <div class="col-xl-6">

            <div class="tradim-card">

                <div class="p-4 border-bottom border-secondary border-opacity-25">
                    <h5 class="mb-0">Latest Users</h5>
                </div>

                <div class="table-responsive">

                    <table class="table tradim-table">

                        <thead>
                            <tr>
                                <th class="px-4">Name</th>
                                <th>Status</th>
                                <th class="pe-4">Joined</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($latestUsers as $user)

                                <tr>

                                    <td class="px-4">
                                        <strong>
                                            {{ $user->name }}
                                        </strong>

                                        <div class="small tradim-muted">
                                            {{ $user->email }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($user->is_active)
                                            <span class="badge text-bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge text-bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="pe-4">
                                        {{ $user->created_at?->format('d M Y') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        No users found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-xl-6">

            <div class="tradim-card">

                <div class="p-4 border-bottom border-secondary border-opacity-25">
                    <h5 class="mb-0">Latest Videos</h5>
                </div>

                <div class="table-responsive">

                    <table class="table tradim-table">

                        <thead>
                            <tr>
                                <th class="px-4">Video</th>
                                <th>Status</th>
                                <th class="pe-4">Views</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($latestVideos as $video)

                                <tr>

                                    <td class="px-4">
                                        <strong>
                                            {{ Str::limit($video->title, 35) }}
                                        </strong>

                                        <div class="small tradim-muted">
                                            {{ $video->channel?->name ?? 'No channel' }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge text-bg-secondary">
                                            {{ ucfirst($video->status) }}
                                        </span>
                                    </td>

                                    <td class="pe-4">
                                        {{ number_format($video->views_count) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        No videos found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection