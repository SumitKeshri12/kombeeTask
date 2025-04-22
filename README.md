# Laravel Role-Based Access Control Project

A robust Laravel application implementing Role-Based Access Control (RBAC) using Spatie Permission package. This project provides user management with different access levels and permissions.

## Features

-   User Authentication with Laravel Passport
-   Role-Based Access Control (RBAC) using Spatie Permissions
-   Three default roles: Super Admin, Admin, and User
-   User Management
-   Supplier Management
-   City Management
-   Profile Management with hobbies and personal information
-   API Support with Laravel Passport
-   Soft Delete Implementation
-   Idempotency Support for API Endpoints

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
php artisan migrate --seed
```

7. Install Laravel Passport:

```bash
php artisan passport:install
```

## Default Users

The system comes with three default users:

1. Super Admin:

    - Email: superadmin@example.com
    - Password: password
    - Role: Super Admin

2. Regular User (John):

    - Email: john@example.com
    - Password: password
    - Role: User

3. Regular User (Jane):
    - Email: jane@example.com
    - Password: password
    - Role: User

## API Authentication

The API uses Laravel Passport for authentication. Here's how to authenticate:

1. Login to get access token:

```http
POST /api/login
Content-Type: application/json
Accept: application/json

{
    "email": "superadmin@example.com",
    "password": "password"
}
```

2. Use the token for subsequent requests:

```http
GET /api/suppliers
Authorization: Bearer your_access_token_here
Accept: application/json
```

### Important Authentication Notes:

-   Access tokens are valid for 15 days
-   Refresh tokens are valid for 30 days
-   Personal access tokens expire in 6 months
-   Always include the `Accept: application/json` header
-   The `Authorization` header must use the `Bearer` prefix

## API Endpoints

### Authentication Endpoints

```http
POST /api/login - Login user
POST /api/register - Register new user
POST /api/logout - Logout user (requires authentication)
```

### User Management

```http
GET /api/users - List all users
POST /api/users - Create new user
GET /api/users/{id} - Get user details
PUT /api/users/{id} - Update user
DELETE /api/users/{id} - Delete user
```

### Supplier Management

```http
GET /api/suppliers - List all suppliers
POST /api/suppliers - Create new supplier
GET /api/suppliers/{id} - Get supplier details
PUT /api/suppliers/{id} - Update supplier
DELETE /api/suppliers/{id} - Delete supplier
```

### Role & Permission Management

```http
GET /api/roles - List all roles
POST /api/roles - Create new role
GET /api/roles/{id} - Get role details
PUT /api/roles/{id} - Update role
DELETE /api/roles/{id} - Delete role
POST /api/roles/{role}/permissions - Attach permissions to role
DELETE /api/roles/{role}/permissions - Detach permissions from role
```

### Location Management

```http
GET /api/states - List all states
GET /api/states/{state}/cities - Get cities for a state
```

## API Idempotency

This project implements idempotency for API requests to prevent duplicate operations. To use idempotency:

1. Include an `Idempotency-Key` header in your POST, PUT, PATCH, or DELETE requests:

```http
POST /api/suppliers
Idempotency-Key: 123e4567-e89b-12d3-a456-426614174000
```

2. The server will:
    - Process the request normally if the key hasn't been used before
    - Return the cached response if the same key is used again
    - Include an `X-Idempotent-Replayed: true` header for cached responses

Example using curl:

```bash
curl -X POST https://your-api.com/api/suppliers \
  -H "Authorization: Bearer your_token_here" \
  -H "Idempotency-Key: 123e4567-e89b-12d3-a456-426614174000" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name": "Supplier Name"}'
```

## Database Structure

Key tables in the database:

-   `users`: Stores user information
-   `roles`: Defines available roles
-   `permissions`: Lists all permissions
-   `model_has_roles`: Maps users to roles
-   `model_has_permissions`: Maps users to permissions
-   `role_has_permissions`: Maps roles to permissions
-   `cities`: Stores city information
-   `states`: Stores state information
-   `suppliers`: Stores supplier information
-   `oauth_access_tokens`: Stores API access tokens
-   `oauth_refresh_tokens`: Stores API refresh tokens

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
