<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @auth
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                            href="{{ route('dashboard') }}">Dashboard</a>
                    </li>

                    <!-- Users Management Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active' : '' }}" 
                            href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Users Management
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="usersDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                                    href="{{ route('users.index') }}">Users</a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('roles.*') ? 'active' : '' }}" 
                                    href="{{ route('roles.index') }}">Roles</a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}" 
                                    href="{{ route('permissions.index') }}">Permissions</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Suppliers -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" 
                            href="{{ route('suppliers.index') }}">Suppliers</a>
                    </li>

                    <!-- Customers -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" 
                            href="{{ route('customers.index') }}">Customers</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->first_name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                </ul>
            @endauth
        </div>
    </div>
</nav>

@push('scripts')
<script>
$(document).ready(function() {
    // Add active class to parent dropdown if child is active
    $('.dropdown-item.active').closest('.nav-item.dropdown').find('.nav-link').addClass('active');
    
    // Handle dropdown hover
    $('.nav-item.dropdown').hover(
        function() {
            $(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
        },
        function() {
            $(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
        }
    );
});
</script>

<style>
.navbar .dropdown-menu {
    margin-top: 0;
}

.navbar .nav-link.active {
    font-weight: bold;
}

.dropdown-item.active {
    background-color: #e9ecef;
    color: #000;
}

@media (min-width: 992px) {
    .navbar .nav-item.dropdown:hover .dropdown-menu {
        display: block;
    }
}
</style>
@endpush 