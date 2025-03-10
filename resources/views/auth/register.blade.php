@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>
                            <div class="col-md-6">
                                <input id="first_name" type="text" 
                                    class="form-control @error('first_name') is-invalid @enderror" 
                                    name="first_name" value="{{ old('first_name') }}" required autofocus>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text" 
                                    class="form-control @error('last_name') is-invalid @enderror" 
                                    name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="contact_number" class="col-md-4 col-form-label text-md-end">{{ __('Contact Number') }}</label>
                            <div class="col-md-6">
                                <input id="contact_number" type="text" 
                                    class="form-control @error('contact_number') is-invalid @enderror" 
                                    name="contact_number" value="{{ old('contact_number') }}" required>
                                @error('contact_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="state" class="col-md-4 col-form-label text-md-end">{{ __('State') }}</label>
                            <div class="col-md-6">
                                <select id="state" class="form-control @error('state_id') is-invalid @enderror" name="state_id" required>
                                    <option value="">Select State</option>
                                </select>
                                @error('state_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="city" class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>
                            <div class="col-md-6">
                                <select id="city" class="form-control @error('city_id') is-invalid @enderror" name="city_id" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="postcode" class="col-md-4 col-form-label text-md-end">{{ __('Postcode') }}</label>
                            <div class="col-md-6">
                                <input id="postcode" type="text" 
                                    class="form-control @error('postcode') is-invalid @enderror" 
                                    name="postcode" value="{{ old('postcode') }}" required>
                                @error('postcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" 
                                        id="gender_male" value="male" required>
                                    <label class="form-check-label" for="gender_male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" 
                                        id="gender_female" value="female">
                                    <label class="form-check-label" for="gender_female">Female</label>
                                </div>
                                @error('gender')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">{{ __('Hobbies') }}</label>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hobbies[]" 
                                        id="hobby_reading" value="reading">
                                    <label class="form-check-label" for="hobby_reading">Reading</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hobbies[]" 
                                        id="hobby_sports" value="sports">
                                    <label class="form-check-label" for="hobby_sports">Sports</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hobbies[]" 
                                        id="hobby_music" value="music">
                                    <label class="form-check-label" for="hobby_music">Music</label>
                                </div>
                                @error('hobbies')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">
                                {{ __('Confirm Password') }}
                            </label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" 
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                                <a class="btn btn-link" href="{{ route('login') }}">
                                    {{ __('Already have an account? Login') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Load states
    $.get('/api/states', function(states) {
        let options = '<option value="">Select State</option>';
        states.forEach(state => {
            options += `<option value="${state.id}">${state.name}</option>`;
        });
        $('#state').html(options);
    });

    // Load cities when state changes
    $('#state').change(function() {
        const stateId = $(this).val();
        if (stateId) {
            $.get(`/api/states/${stateId}/cities`, function(cities) {
                let options = '<option value="">Select City</option>';
                cities.forEach(city => {
                    options += `<option value="${city.id}">${city.name}</option>`;
                });
                $('#city').html(options);
            });
        } else {
            $('#city').html('<option value="">Select City</option>');
        }
    });

    // Form validation
    $('#registerForm').validate({
        rules: {
            first_name: 'required',
            last_name: 'required',
            email: {
                required: true,
                email: true
            },
            contact_number: 'required',
            state_id: 'required',
            city_id: 'required',
            postcode: 'required',
            gender: 'required',
            'hobbies[]': {
                required: true,
                minlength: 1
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                equalTo: '#password'
            }
        },
        messages: {
            'hobbies[]': 'Please select at least one hobby',
            password_confirmation: {
                equalTo: 'Passwords do not match'
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            if (element.attr('type') === 'radio' || element.attr('type') === 'checkbox') {
                error.addClass('invalid-feedback d-block').insertAfter(element.closest('.col-md-6'));
            } else {
                error.addClass('invalid-feedback');
                element.closest('.col-md-6').append(error);
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endpush 