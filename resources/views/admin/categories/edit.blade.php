@extends('admin.layout')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-8">

        <div class="tradim-card p-4">

            <h4 class="mb-4">
                Edit Category
            </h4>

            <form
                method="POST"
                action="{{ route('admin.categories.update', $category) }}"
            >

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        class="form-control tradim-input"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        value="{{ old('slug', $category->slug) }}"
                        class="form-control tradim-input"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control tradim-input"
                    >{{ old('description', $category->description) }}</textarea>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Icon
                        </label>

                        <input
                            type="text"
                            name="icon"
                            value="{{ old('icon', $category->icon) }}"
                            class="form-control tradim-input"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Sort Order
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order', $category->sort_order) }}"
                            min="0"
                            class="form-control tradim-input"
                        >

                    </div>

                </div>

                <div class="form-check mb-4">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        id="is_active"
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="is_active"
                    >
                        Active
                    </label>

                </div>

                <div class="d-flex gap-2">

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="btn btn-outline-light"
                    >
                        Cancel
                    </a>

                    <button class="btn btn-primary">
                        Update Category
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection