@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Edit Role</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back to Roles</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            Permissions
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="select-all-permissions">
                                <label class="form-check-label" for="select-all-permissions">Select All</label>
                            </div>
                        </label>
                        <div class="row">
                            @foreach($permissions->chunk(4) as $chunk)
                                <div class="col-md-3">
                                    @foreach($chunk as $permission)
                                        <div class="form-check mb-2">
                                            <input type="checkbox" 
                                                class="form-check-input permission-checkbox" 
                                                name="permissions[]" 
                                                value="{{ $permission->name }}" 
                                                id="permission_{{ $permission->id }}"
                                                {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle "Select All" checkbox
    $('#select-all-permissions').on('change', function() {
        $('.permission-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Update "Select All" checkbox state based on individual checkboxes
    function updateSelectAllCheckbox() {
        var allChecked = $('.permission-checkbox:checked').length === $('.permission-checkbox').length;
        $('#select-all-permissions').prop('checked', allChecked);
    }

    // Initialize "Select All" checkbox state
    updateSelectAllCheckbox();

    // Update "Select All" checkbox when individual checkboxes change
    $('.permission-checkbox').on('change', function() {
        updateSelectAllCheckbox();
    });

    // Show success/error messages
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
});
</script>
@endpush
@endsection 