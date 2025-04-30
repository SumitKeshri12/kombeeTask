// Import libraries
import './bootstrap';
import jQuery from 'jquery';
import 'bootstrap';
import '@popperjs/core';
import 'select2';
import toastr from 'toastr';
import 'datatables.net';
import 'datatables.net-bs5';
import Echo from 'laravel-echo';
import axios from 'axios';

// Make jQuery globally available
window.$ = window.jQuery = jQuery;
window.toastr = toastr;
window.axios = axios;

// Initialize plugins when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    // Initialize Toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 3000
    };

    // Setup AJAX CSRF token for jQuery
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });

    // Setup Axios defaults
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.withCredentials = true;

    // Get the CSRF token from the meta tag
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found');
    }

    // If user is authenticated, set the API token
    const userToken = document.querySelector('meta[name="user-token"]');
    if (userToken) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${userToken.content}`;
    }

    // Initialize Echo
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        wssPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: false,
        // scheme: import.meta.env.VITE_REVERB_SCHEME,
        // enabledTransports: ['ws', 'wss'],
        disableStats: true,
    });
});

// Initialize DataTables
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('.datatable').DataTable();
    }
});
