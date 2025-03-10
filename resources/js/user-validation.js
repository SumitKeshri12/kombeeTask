$(document).ready(function() {
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
                    type: "post"
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
                phoneUS: true
            },
            postcode: {
                required: true,
                pattern: /^[0-9]{5,6}$/
            }
        },
        messages: {
            // Add custom messages here
        },
        submitHandler: function(form) {
            $.ajax({
                url: '/api/users',
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(response) {
                    // Handle success
                },
                error: function(xhr) {
                    // Handle error
                }
            });
        }
    });
}); 