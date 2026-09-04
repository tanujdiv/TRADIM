@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        <div class="mb-4">

            <h2 class="fw-bold mb-1">
                My Subscriptions
            </h2>

            <p class="text-muted">
                Channels you are subscribed to
            </p>

        </div>


        @if($channels->isEmpty())

            <div class="text-center py-5">

                <div style="font-size: 60px;">
                    👥
                </div>

                <h4 class="mt-3">
                    No subscriptions yet
                </h4>

                <p class="text-muted">
                    Subscribe to creators to follow their content.
                </p>

                <a href="{{ url('/') }}" class="btn btn-primary">
                    Explore Channels
                </a>

            </div>

        @else

            <div class="row g-4">

                @foreach($channels as $channel)

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body text-center">

                                {{-- Channel Avatar --}}
                                <div class="mb-3">

                                    @if($channel->avatar)

                                        <img src="{{ asset('storage/' . $channel->avatar) }}" alt="{{ $channel->name }}" width="80"
                                            height="80" class="rounded-circle" style="object-fit: cover;">

                                    @else

                                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                            style="
                                                            width: 80px;
                                                            height: 80px;
                                                            font-size: 28px;
                                                        ">
                                            {{ strtoupper(substr($channel->name, 0, 1)) }}
                                        </div>

                                    @endif

                                </div>


                                <h5 class="fw-bold">
                                    {{ $channel->name }}
                                </h5>


                                <p class="text-muted mb-3">

                                    {{ number_format($channel->subscriber_count) }}

                                    subscribers

                                </p>


                                <a href="{{ route('channels.show', $channel->handle) }}" class="btn btn-primary">
                                    View Channel
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            <div class="mt-4">

                {{ $channels->links() }}

            </div>

        @endif

    </div>

@endsection