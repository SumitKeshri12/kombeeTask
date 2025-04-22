@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Users Management</h2>
        </div>
        <div class="col-md-6 text-end">
            @if(auth()->user()->roles->contains('slug', 'super-admin'))
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('users.export') }}">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('users.export-pdf') }}">
                                <i class="fas fa-file-pdf"></i> Export to PDF
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
            <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($users->isEmpty())
                <div class="alert alert-info">
                    No other users found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table" id="users-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                                    @if(auth()->user()->roles->contains('slug', 'super-admin'))
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                                <a href="{{ route('users.edit', $user->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                
                                                @if($user->id !== auth()->id())
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger delete-user" 
                                                            data-id="{{ $user->id }}">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable if there are users
    if ($('#users-table tbody tr').length > 0) {
        $('#users-table').DataTable({
            "pageLength": 10,
            "order": [[0, "asc"]],
            "language": {
                "search": "Search users:",
                "lengthMenu": "Show _MENU_ users per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ users"
            }
        });
    }

    // Handle delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this user?')) {
            this.submit();
        }
    });

    // Show success/error messages
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    // Export functionality
    $('.export-btn').click(function(e) {
        e.preventDefault();
        const format = $(this).data('format');
        window.location.href = `/api/users/export/${format}`;
    });
});
</script>
@endpush 