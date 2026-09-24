@extends('admin.layout')

@section('title', 'Users')
@section('page_title', 'Users')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">Users</h2>
        <div class="tradim-muted">
            Manage Tradim users
        </div>
    </div>

</div>

<div class="tradim-card">

    <div class="p-4 border-bottom border-secondary border-opacity-25">

        <form method="GET">

            <div class="row g-2">

                <div class="col-md-6">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control tradim-input"
                        placeholder="Search name, username or email..."
                    >

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
                <th class="px-4">User</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th class="text-end pe-4">Actions</th>
            </tr>

            </thead>

            <tbody>

            @forelse($users as $user)

                <tr>

                    <td class="px-4">
                        <strong>{{ $user->name }}</strong>

                        <div class="small tradim-muted">
                            {{ $user->email }}
                        </div>
                    </td>

                    <td>
                        {{ $user->username }}
                    </td>

                    <td>
                        <span class="badge text-bg-secondary">
                            {{ $user->role }}
                        </span>
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

                    <td>
                        {{ $user->created_at?->format('d M Y') }}
                    </td>

                    <td class="text-end pe-4">

                        @if($user->role !== 'admin')

                            <form
                                method="POST"
                                action="{{ route('admin.users.toggle-status', $user) }}"
                                class="d-inline"
                            >
                                @csrf
                                @method('PATCH')

                                <button class="btn btn-sm btn-outline-warning">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>

                            </form>

                            <form
                                method="POST"
                                action="{{ route('admin.users.destroy', $user) }}"
                                class="d-inline"
                                onsubmit="return confirm('Delete this user?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>

                            </form>

                        @else

                            <span class="tradim-muted">
                                Admin
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center py-5">
                        No users found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="p-4 tradim-pagination">
        {{ $users->links() }}
    </div>

</div>

@endsection