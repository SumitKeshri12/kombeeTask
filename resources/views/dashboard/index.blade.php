@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <!-- Quick Stats -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2 class="card-text">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Suppliers</h5>
                    <h2 class="card-text">{{ $totalSuppliers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Customers</h5>
                    <h2 class="card-text">{{ $totalCustomers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Roles</h5>
                    <h2 class="card-text">{{ $totalRoles }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Recent Activities
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->description }}</td>
                                        <td>{{ $activity->causer->name }}</td>
                                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize any dashboard-specific JavaScript here
});
</script>
@endpush 