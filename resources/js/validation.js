$(document).ready(function() {
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, "Letters and numbers only please");

    $("#registerForm").validate({
        rules: {
            first_name: {
                required: true,
                alphanumeric: true
            },
            last_name: {
                required: true,
                alphanumeric: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "/api/check-email",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            },
            contact_number: {
                required: true,
                pattern: /^([0-9\s\-\+\(\)]*)$/
            },
            postcode: {
                required: true,
                pattern: /^[0-9]{5,6}$/
            },
            'roles[]': {
                required: true
            },
            'hobbies[]': {
                required: true
            }
        },
        submitHandler: function(form) {
            const formData = new FormData(form);
            
            $.ajax({
                url: '/api/users',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                success: function(response) {
                    toastr.success('User created successfully');
                    window.location.href = '/users';
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                }
            });
        }
    });

    // City-State Dropdown
    $('#state').on('change', function() {
        const stateId = $(this).val();
        $.ajax({
            url: `/api/states/${stateId}/cities`,
            type: 'GET',
            success: function(cities) {
                let options = '<option value="">Select City</option>';
                cities.forEach(city => {
                    options += `<option value="${city.id}">${city.name}</option>`;
                });
                $('#city').html(options);
            }
        });
    });
}); 