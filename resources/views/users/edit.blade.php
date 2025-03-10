@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Edit User</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                            id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                            id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                            id="contact_number" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}">
                        @error('contact_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" class="form-control @error('postcode') is-invalid @enderror" 
                            id="postcode" name="postcode" value="{{ old('postcode', $user->postcode) }}">
                        @error('postcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city_id" class="form-label">City</label>
                        <select class="form-control select2 @error('city_id') is-invalid @enderror" 
                            id="city_id" name="city_id" required>
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" 
                                    {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control @error('gender') is-invalid @enderror" 
                            id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            @foreach($genderOptions as $value => $label)
                                <option value="{{ $value }}" 
                                    {{ old('gender', $user->gender) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="roles" class="form-label">Roles</label>
                        <select class="form-control select2 @error('roles') is-invalid @enderror" 
                            id="roles" name="roles[]" multiple required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                    {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Hobbies</label>
                        <div class="row ps-3">
                            @foreach($hobbyOptions as $value => $label)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="hobbies[]" value="{{ $value }}" 
                                            id="hobby_{{ $value }}"
                                            {{ in_array($value, old('hobbies', $user->hobbies ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hobby_{{ $value }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('hobbies')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password">
                        <small class="form-text text-muted">Leave blank to keep current password</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update User</button>
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
        theme: 'bootstrap-5'
    });

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