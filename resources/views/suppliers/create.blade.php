@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Create Supplier</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back to Suppliers</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="createSupplierForm" action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" required>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                            id="phone" name="phone" value="{{ old('phone') }}" required>
                        <div class="invalid-feedback" id="phone-error"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="city_id" class="form-label">City</label>
                        <select class="form-control select2 @error('city_id') is-invalid @enderror" 
                            id="city_id" name="city_id" required>
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" 
                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="city_id-error"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                            id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        <div class="invalid-feedback" id="address-error"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Create Supplier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select City',
        allowClear: true
    });

    // Reset form validation on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(`#${$(this).attr('id')}-error`).text('');
    });

    $('#createSupplierForm').submit(function(e) {
        e.preventDefault();
        
        // Reset validation
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Disable submit button and show spinner
        $('#submitBtn').prop('disabled', true);
        $('#spinner').removeClass('d-none');
        
        // Log form data
        console.log('Form data:', $(this).serialize());
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success:', response);
                toastr.success(response.message);
                window.location.href = '{{ route('suppliers.index') }}';
            },
            error: function(xhr, status, error) {
                // Enable submit button and hide spinner
                $('#submitBtn').prop('disabled', false);
                $('#spinner').addClass('d-none');

                console.log('Error status:', xhr.status);
                console.log('Error response:', xhr.responseText);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}-error`).text(errors[key][0]);
                        toastr.error(errors[key][0]);
                    });
                } else {
                    // Show the actual error message from the server
                    const errorMessage = xhr.responseJSON?.error || 'An error occurred. Please try again.';
                    toastr.error(errorMessage);
                    console.error('Error details:', errorMessage);
                }
            }
        });
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