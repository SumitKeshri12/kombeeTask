# Laravel Role-Based Access Control Project

A robust Laravel application implementing Role-Based Access Control (RBAC) using Spatie Permission package. This project provides user management with different access levels and permissions.

## Features

-   User Authentication
-   Role-Based Access Control (RBAC)
-   Three default roles: Super Admin, Admin, and User
-   User Management
-   City Management
-   Profile Management with hobbies and personal information
-   API Support with Laravel Passport
-   Soft Delete Implementation

## Requirements

-   PHP >= 8.1
-   Laravel 10.x
-   MySQL/MariaDB
-   Composer
-   Node.js & NPM

## Installation

1. Clone the repository:

```bash
git clone <repository-url>
cd <project-folder>
```

2. Install PHP dependencies:

```bash
composer install
```

3. Copy the environment file:

```bash
cp .env.example .env
```

4. Configure your database in the `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Run database migrations and seeders:

```bash
php artisan migrate
php artisan db:seed
```

7. Install Laravel Passport:

```bash
php artisan passport:install
```

## Default Users

The system comes with two default user types:

1. Super Admin:

    - Email: superadmin@example.com
    - Role: Super Admin

2. Regular User:
    - Email: john@example.com
    - Role: User

## Role Management

To assign roles to users, you can use the following Artisan commands:

```bash
# Assign Super Admin role
php artisan role:assign-super-admin {email}

# Assign User role
php artisan role:assign-user {email}
```

## Features by Role

### Super Admin

-   Full access to all features
-   User management
-   Role and permission management
-   City management
-   View all users' profiles

### Admin

-   Limited user management
-   City management
-   View assigned users' profiles

### User

-   View and edit own profile
-   Update personal information
-   Manage hobbies and preferences

## API Endpoints

The application provides RESTful API endpoints for:

-   User Management
-   Role Management
-   Permission Management
-   City Management
-   Profile Management

API documentation is available at `/api/documentation` when running in development mode.

## Database Structure

Key tables in the database:

-   `users`: Stores user information
-   `roles`: Defines available roles
-   `permissions`: Lists all permissions
-   `model_has_roles`: Maps users to roles
-   `model_has_permissions`: Maps users to permissions
-   `role_has_permissions`: Maps roles to permissions
-   `cities`: Stores city information

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security

If you discover any security-related issues, please email [your-email] instead of using the issue tracker.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Acknowledgments

-   [Laravel](https://laravel.com)
-   [Spatie Permission Package](https://github.com/spatie/laravel-permission)
-   [Laravel Passport](https://laravel.com/docs/10.x/passport)
