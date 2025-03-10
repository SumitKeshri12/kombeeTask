@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Edit Customer</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to Customers</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="editCustomerForm" method="POST" action="{{ route('customers.update', $customer->id) }}">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label required">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label required">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label required">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                            id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city_id" class="form-label required">City</label>
                        <select class="form-select @error('city_id') is-invalid @enderror" 
                            id="city_id" name="city_id" required>
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" 
                                    {{ old('city_id', $customer->city_id) == $city->id ? 'selected' : '' }}>
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
                    <div class="col-md-12">
                        <label for="address" class="form-label required">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                            id="address" name="address" rows="3" required>{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Update Customer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .required:after {
        content: ' *';
        color: red;
    }
    .form-label {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#editCustomerForm').submit(function(e) {
        e.preventDefault();
        
        // Reset validation
        $('.is-invalid').removeClass('is-invalid');
        
        // Disable submit button and show spinner
        $('#submitBtn').prop('disabled', true);
        $('#spinner').removeClass('d-none');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success(response.message);
                window.location.href = "{{ route('customers.index') }}";
            },
            error: function(xhr) {
                // Enable submit button and hide spinner
                $('#submitBtn').prop('disabled', false);
                $('#spinner').addClass('d-none');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}`).siblings('.invalid-feedback').text(errors[key][0]);
                        toastr.error(errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
            }
        });
    });
});
</script>
@endpush
@endsection 