@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Edit Permission</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back to Permissions</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('permissions.update', $permission) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Permission Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                        <small class="form-text text-muted">
                            Use format: verb-subject (e.g., create-users, edit-roles)
                        </small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update Permission</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 