// Token management
// Token management
const TokenManager = {
    async getToken() {
        console.log('Getting token...');
        try {
            const response = await $.ajax({
                url: '/api/token',
                type: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            console.log('Token response:', response);
            if (response && response.token) {
                localStorage.setItem('api_token', response.token);
                return response.token;
            }
            throw new Error('Invalid token response');
        } catch (error) {
            console.error('Token error:', error);
            localStorage.removeItem('api_token');
            // Don't redirect automatically
            console.error('Authentication required');
            throw error;
        }
    },

    async makeRequest(url, options = {}) {
        console.log('Making request to:', url);
        try {
            let token = localStorage.getItem('api_token');
            
            // If no token exists, get a new one
            if (!token) {
                console.log('No token found, getting new token...');
                token = await this.getToken();
            }
            
            console.log('Using token:', token);
            const defaultOptions = {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            };

            const finalOptions = { ...defaultOptions, ...options };
            finalOptions.headers = { ...defaultOptions.headers, ...options.headers };

            console.log('Request options:', finalOptions);
            const response = await $.ajax(url, finalOptions);
            console.log('Request response:', response);
            return response;
        } catch (error) {
            console.error('Request error:', error);
            if (error.status === 401) {
                console.log('Unauthorized, trying to get new token...');
                localStorage.removeItem('api_token');
                try {
                    const newToken = await this.getToken();
                    return this.makeRequest(url, options);
                } catch (tokenError) {
                    console.error('Could not refresh token:', tokenError);
                    throw tokenError;
                }
            }
            throw error;
        }
    }
};

// Test if TokenManager is loaded
console.log('TokenManager loaded successfully');

// Example usage in your existing code:
$(document).ready(function() {
    // Fetch roles
    async function fetchRoles() {
        try {
            const roles = await TokenManager.makeRequest('/api/roles');
            let options = '';
            roles.forEach(role => {
                options += `<option value="${role.id}">${role.name}</option>`;
            });
            $('select[name="roles[]"]').html(options);
        } catch (error) {
            toastr.error('Error fetching roles');
        }
    }

    // Initial fetch of roles
    fetchRoles();

    // Form submission
    $('#createUserForm').validate({
        // ... validation rules ...
        submitHandler: async function(form) {
            try {
                await TokenManager.makeRequest('/api/users', {
                    type: 'POST',
                    data: $(form).serialize()
                });
                toastr.success('User created successfully');
                window.location.href = '/users';
            } catch (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.keys(xhr.responseJSON.errors).forEach(key => {
                        toastr.error(xhr.responseJSON.errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred');
                }
            }
            return false;
        }
    });
});