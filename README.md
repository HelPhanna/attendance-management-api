# Attendance Management API

Laravel 12 backend API for the Attendance Management System.
This service handles authentication, role-based authorization, attendance records, analytics, reporting, and school settings.

## Tech Stack

- PHP 8.2+
- Laravel 12
- Laravel Sanctum (API authentication)
- MySQL/PostgreSQL/SQLite (Laravel-supported databases)
- Vite + Tailwind CSS (asset pipeline)

## Prerequisites

- PHP 8.2 or newer
- Composer
- Node.js 18+
- npm
- A running database server

## Quick Start

1. Install dependencies:

```bash
composer install
npm install
```

2. Create environment file:

```bash
cp .env.example .env
```

3. Generate app key:

```bash
php artisan key:generate
```

4. Configure database in `.env`, then run:

```bash
php artisan migrate
php artisan db:seed
```

5. Start development:

```bash
composer run dev
```

Default app URL is `http://127.0.0.1:8000` unless changed in `.env`.

## Environment Notes

Important `.env` values:

- `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Any mail/export-related settings used in your environment

## Scripts

### Composer

- `composer run dev`: Run Laravel server, queue listener, logs (`pail`), and Vite concurrently
- `composer run test`: Clear config and run test suite
- `composer run setup`: Install dependencies, create `.env`, generate key, migrate, and build assets

### NPM

- `npm run dev`: Start Vite in development mode
- `npm run build`: Build frontend assets for production

## API Authentication

- Public auth endpoints are under `/api/auth` (`register`, `login`, `forgot-password`)
- Most endpoints require `auth:sanctum` token authentication
- Role middleware is used for restricted resources (for example `super_admin`, `admin`)

## Main API Route Groups

Defined in `routes/api.php`:

- `/api/auth`
- `/api/user-profile`
- `/api/permissions`
- `/api/roles`
- `/api/users`
- `/api/user-roles`
- `/api/rolespermissions`
- `/api/classes`
- `/api/grade-levels`
- `/api/grade-level-subjects`
- `/api/blacklists`
- `/api/class-teachers`
- `/api/students`
- `/api/teachers`
- `/api/enrollments`
- `/api/academic-year`
- `/api/term`
- `/api/class-session`
- `/api/settings`
- `/api/attendance-records`
- `/api/attendance-analytics`
- `/api/report-export`

For complete endpoint details and HTTP methods, see `routes/api.php` and corresponding controllers in `app/Http/Controllers`.

## Project Structure

```text
app/
  Http/Controllers/      # API controllers
  Models/                # Eloquent models
  Actions/               # Business logic actions
routes/
  api.php                # API routes
config/                  # Framework and app configuration
database/
  migrations/            # Schema changes
  seeders/               # Seed data
tests/                   # Feature and unit tests
```

## Testing

Run all tests:

```bash
php artisan test
```

Run a specific test file:

```bash
php artisan test tests/Feature/StudentTest.php
```

## Production Build

```bash
npm run build
php artisan optimize
```

## Troubleshooting

- Port conflict: `php artisan serve --port=8001`
- Clear caches: `php artisan optimize:clear`
- Re-run dependencies: `composer install && npm install`
- Recreate DB schema: `php artisan migrate:fresh --seed`

## Related Docs

- Backend API testing guide: `POSTMAN_BACKEND_TESTING.md`
- Laravel docs: <https://laravel.com/docs>
