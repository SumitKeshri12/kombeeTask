@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Suppliers</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Add Supplier</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(!isset($suppliers))
                <div class="alert alert-info">
                    No suppliers found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table" id="suppliers-table">
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
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->city->name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($supplier->address, 30) }}</td>
                                    @if(auth()->user()->roles->contains('slug', 'super-admin'))
                                    <td>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" 
                                           class="btn btn-sm btn-primary">Edit</a>
                                        
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#suppliers-table').DataTable({
        "pageLength": 10,
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 5 } // Disable sorting on actions column
        ],
        "language": {
            "search": "Search suppliers:",
            "lengthMenu": "Show _MENU_ suppliers per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ suppliers"
        }
    });

    // Handle delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this supplier?')) {
            this.submit();
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