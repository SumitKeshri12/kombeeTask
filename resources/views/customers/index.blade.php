@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Customers</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="customers-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this customer?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let deleteCustomerId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    // Initialize DataTable with AJAX
    const table = $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers.data') }}",
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'city_name', name: 'city_name' },
            { data: 'address', name: 'address' },
            { 
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    // Handle Edit Click
    $(document).on('click', '.edit-customer', function() {
        const customerId = $(this).data('id');
        window.location.href = `{{ url('customers') }}/${customerId}/edit`;
    });

    // Handle Delete Click
    $(document).on('click', '.delete-customer', function() {
        deleteCustomerId = $(this).data('id');
        deleteModal.show();
    });

    // Handle Delete Confirmation
    $('#confirmDelete').click(function() {
        if (deleteCustomerId) {
            $.ajax({
                url: `{{ url('customers') }}/${deleteCustomerId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                    deleteModal.hide();
                },
                error: function(xhr) {
                    toastr.error('Error deleting customer');
                }
            });
        }
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