@extends('admin.layout')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Categories
            </h2>

            <div class="tradim-muted">
                Manage video categories
            </div>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            Add Category
        </a>

    </div>

    <div class="tradim-card">

        <div class="table-responsive">

            <table class="table tradim-table">

                <thead>

                    <tr>
                        <th class="px-4">Name</th>
                        <th>Slug</th>
                        <th>Videos</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <td class="px-4">
                                <strong>
                                    {{ $category->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $category->slug }}
                            </td>

                            <td>
                                {{ number_format($category->videos_count) }}
                            </td>

                            <td>
                                {{ $category->sort_order }}
                            </td>

                            <td>

                                @if($category->is_active)

                                    <span class="badge text-bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge text-bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="text-end pe-4">

                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-light">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline"
                                    onsubmit="return confirm('Delete this category?');">

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
                                No categories found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-4 tradim-pagination">
            {{ $categories->links() }}
        </div>

    </div>

@endsection