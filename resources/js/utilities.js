const Utilities = {
    // Handle API errors
    handleError: function(xhr) {
        if (xhr.responseJSON && xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            Object.keys(errors).forEach(key => {
                toastr.error(errors[key][0]);
            });
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        } else {
            toastr.error('An error occurred. Please try again.');
        }
    },

    // Format date
    formatDate: function(dateString) {
        return new Date(dateString).toLocaleDateString();
    },

    // Debounce function for search
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Update URL parameters without page reload
    updateUrlParams: function(params) {
        const url = new URL(window.location);
        Object.keys(params).forEach(key => {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        window.history.pushState({}, '', url);
    }
};

export default Utilities; 