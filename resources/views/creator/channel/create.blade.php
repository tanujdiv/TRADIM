@extends('layouts.app')

@section('title', 'Create Channel - Tradim')

@section('content')

    <div class="container-fluid py-4">

        <div class="row justify-content-center">

            <div class="col-xl-7 col-lg-9">

                <div class="tradim-card">

                    <div class="mb-4">

                        <span class="hero-badge">
                            <i class="bi bi-broadcast"></i>
                            CREATOR
                        </span>

                        <h1 class="mt-3 mb-2">
                            Create your Tradim Channel
                        </h1>

                        <p class="text-muted">
                            Build your audience, upload videos,
                            go live and grow your creator brand.
                        </p>

                    </div>


                    @if($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                Please fix the following:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form action="{{ route('creator.channel.store') }}" method="POST">

                        @csrf


                        <div class="mb-4">

                            <label class="form-label">
                                Channel Name
                            </label>

                            <input type="text" name="name" class="form-control tradim-input" placeholder="e.g. Tanuj Tech"
                                value="{{ old('name') }}" required>

                            <small class="text-muted">
                                This will be your public channel name.
                            </small>

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Channel Handle
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    @
                                </span>

                                <input type="text" name="handle" class="form-control tradim-input" placeholder="tanujtech"
                                    value="{{ old('handle') }}" required>

                            </div>

                            <small class="text-muted">
                                Use letters, numbers, hyphens or underscores.
                            </small>

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Channel Description
                            </label>

                            <textarea name="description" rows="5" class="form-control tradim-input"
                                placeholder="Tell viewers about your channel...">{{ old('description') }}</textarea>

                        </div>


                        <div class="d-flex gap-3">

                            <button type="submit" class="btn-tradim">

                                <i class="bi bi-check-circle"></i>

                                Create Channel

                            </button>


                            <a href="{{ route('home') }}" class="btn-tradim-outline">

                                Cancel

                            </a>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection