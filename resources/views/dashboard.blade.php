@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <!-- Users Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted text-uppercase mb-2">Total Users</h6>
                            <h2 class="mb-0">{{ $stats['users_count'] ?? 0 }}</h2>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">View Users</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted text-uppercase mb-2">Total Customers</h6>
                            <h2 class="mb-0">{{ $stats['customers_count'] ?? 0 }}</h2>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-user-friends fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-success">View Customers</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted text-uppercase mb-2">Total Suppliers</h6>
                            <h2 class="mb-0">{{ $stats['suppliers_count'] ?? 0 }}</h2>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-info">View Suppliers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-title {
        font-size: 0.875rem;
        font-weight: 600;
    }
    h2 {
        font-weight: 600;
    }
</style>
@endpush
@endsection 