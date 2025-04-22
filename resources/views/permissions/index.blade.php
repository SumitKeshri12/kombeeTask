@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Permissions Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('permissions.create') }}" class="btn btn-primary">Add Permission</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Used in Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->roles->pluck('name')->join(', ') }}</td>
                                @if(auth()->user()->roles->contains('slug', 'super-admin'))
                                <td>
                                    <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this permission?')">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $permissions->links() }}
        </div>
    </div>
</div>
@endsection 