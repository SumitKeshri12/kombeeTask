@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Create Role</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back to Roles</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="permissions" class="form-label">Permissions</label>
                        <select class="form-control select2 @error('permissions') is-invalid @enderror" 
                            id="permissions" name="permissions[]" multiple required>
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->name }}" 
                                    {{ in_array($permission->name, old('permissions', [])) ? 'selected' : '' }}>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('permissions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Permissions'
    });
});
</script>
@endpush
@endsection 