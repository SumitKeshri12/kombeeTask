@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Roles Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
                                <td>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection 