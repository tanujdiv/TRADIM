@extends('layouts.app')

@section('title', 'Upload Video - Tradim')

@section('content')

    <div class="container-fluid py-4">

        <div class="row justify-content-center">

            <div class="col-xl-9">

                <div class="mb-4">

                    <span class="hero-badge">

                        <i class="bi bi-cloud-arrow-up"></i>

                        CREATOR STUDIO

                    </span>

                    <h1 class="mt-3 mb-1">
                        Upload a Video
                    </h1>

                    <p class="text-muted">
                        Share your content with the Tradim community.
                    </p>

                </div>


                @if($errors->any())

                    <div class="alert alert-danger">

                        <strong>
                            Upload failed
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


                <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf


                    <div class="row g-4">


                        {{-- LEFT --}}

                        <div class="col-lg-8">


                            <div class="tradim-card mb-4">

                                <h4 class="mb-4">
                                    Video Information
                                </h4>


                                <div class="mb-4">

                                    <label class="form-label">
                                        Video Title
                                    </label>

                                    <input type="text" name="title" class="form-control tradim-input"
                                        placeholder="Enter an attractive title..." value="{{ old('title') }}"
                                        maxlength="255" required>

                                </div>


                                <div class="mb-4">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <textarea name="description" rows="7" class="form-control tradim-input"
                                        placeholder="Tell viewers what your video is about...">{{ old('description') }}</textarea>

                                </div>


                                <div class="mb-4">

                                    <label class="form-label">
                                        Category
                                    </label>

                                    <select name="category_id" class="form-select tradim-input">

                                        <option value="">
                                            Select Category
                                        </option>


                                        @foreach($categories as $category)

                                            <option value="{{ $category->id }}" @selected(
                                                old('category_id')
                                                == $category->id
                                            )>

                                                {{ $category->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="mb-2">

                                    <label class="form-label">
                                        Visibility
                                    </label>

                                    <select name="visibility" class="form-select tradim-input" required>

                                        <option value="public" @selected(
                                            old('visibility')
                                            === 'public'
                                            || !old('visibility')
                                        )>
                                            Public
                                        </option>


                                        <option value="unlisted" @selected(
                                            old('visibility')
                                            === 'unlisted'
                                        )>
                                            Unlisted
                                        </option>


                                        <option value="private" @selected(
                                            old('visibility')
                                            === 'private'
                                        )>
                                            Private
                                        </option>

                                    </select>

                                </div>

                            </div>


                            {{-- VIDEO FILE --}}

                            <div class="tradim-card mb-4">

                                <h4 class="mb-3">
                                    Video File
                                </h4>

                                <p class="text-muted small">
                                    MP4, WebM, MOV or AVI.
                                    Maximum 100 MB for this development stage.
                                </p>


                                <label for="video" class="upload-box">

                                    <i class="bi bi-cloud-arrow-up" style="font-size:45px;"></i>

                                    <h5 class="mt-3">
                                        Choose your video
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Click here to select a video file.
                                    </p>

                                </label>


                                <input type="file" id="video" name="video"
                                    accept="video/mp4,video/webm,video/quicktime,video/x-msvideo" class="d-none" required>


                                <div id="videoName" class="mt-3 text-success small"></div>

                            </div>

                        </div>


                        {{-- RIGHT --}}

                        <div class="col-lg-4">


                            {{-- THUMBNAIL --}}

                            <div class="tradim-card mb-4">

                                <h5 class="mb-3">
                                    Thumbnail
                                </h5>

                                <p class="text-muted small">
                                    Upload a custom thumbnail for your video.
                                </p>


                                <label for="thumbnail" class="thumbnail-upload">

                                    <div id="thumbnailPreview">

                                        <i class="bi bi-image fs-1"></i>

                                        <p class="mb-0 mt-2">
                                            Choose thumbnail
                                        </p>

                                    </div>

                                </label>


                                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                                    class="d-none">

                            </div>


                            {{-- CHANNEL --}}

                            <div class="tradim-card mb-4">

                                <h5 class="mb-3">
                                    Publishing As
                                </h5>


                                <div class="d-flex align-items-center gap-3">

                                    <div style="
                                            width:50px;
                                            height:50px;
                                            border-radius:50%;
                                            background:linear-gradient(
                                                135deg,
                                                #7c3aed,
                                                #ec4899
                                            );
                                            color:white;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            font-weight:800;
                                        ">

                                        {{ strtoupper(
        substr(
            $channel->name,
            0,
            1
        )
    ) }}

                                    </div>


                                    <div>

                                        <strong>
                                            {{ $channel->name }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            @{{ $channel->handle }}
                                        </small>

                                    </div>

                                </div>

                            </div>


                            {{-- BUTTONS --}}

                            <div class="tradim-card">

                                <button type="submit" class="btn-tradim w-100 mb-3">

                                    <i class="bi bi-upload"></i>

                                    Publish Video

                                </button>


                                <a href="{{ route('creator.dashboard') }}" class="btn-tradim-outline w-100">

                                    Cancel

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <style>
        .upload-box {

            min-height: 220px;

            border: 2px dashed rgba(139, 92, 246, .35);

            border-radius: 15px;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            background: rgba(124, 58, 237, .04);

            transition: .2s;

        }


        .upload-box:hover {

            border-color: #8b5cf6;

            background: rgba(124, 58, 237, .08);

        }


        .thumbnail-upload {

            min-height: 180px;

            border: 2px dashed rgba(139, 92, 246, .35);

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            text-align: center;

            cursor: pointer;

            overflow: hidden;

        }


        .thumbnail-upload img {

            width: 100%;

            height: 180px;

            object-fit: cover;

        }
    </style>


    <script>

        document
            .getElementById('video')
            .addEventListener('change', function () {

                const file = this.files[0];

                const nameBox =
                    document.getElementById('videoName');


                if (file) {

                    nameBox.innerHTML =
                        '<i class="bi bi-check-circle"></i> ' +
                        file.name;

                } else {

                    nameBox.innerHTML = '';

                }

            });


        document
            .getElementById('thumbnail')
            .addEventListener('change', function () {

                const file = this.files[0];

                const preview =
                    document.getElementById(
                        'thumbnailPreview'
                    );


                if (file) {

                    const reader =
                        new FileReader();


                    reader.onload = function (event) {

                        preview.innerHTML =

                            '<img src="' +
                            event.target.result +
                            '" alt="Thumbnail Preview">';

                    };


                    reader.readAsDataURL(file);

                }

            });

    </script>

@endsection