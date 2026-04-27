# Attendance Management System - Backend

A Laravel 12 API backend for an Attendance Management System providing comprehensive student attendance tracking, reporting, and administrative features.

## Table of Contents

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [API Endpoints](#api-endpoints)
- [Database](#database)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)

## Overview

This Laravel backend provides RESTful API endpoints for managing:

- **Students & Teachers** — User management and profiles
- **Classes & Sessions** — Class organization and attendance sessions
- **Attendance Records** — Track and manage student attendance
- **Academic Structure** — Grade levels, subjects, and terms
- **Reporting** — Generate attendance reports and exports
- **Role-Based Access Control** — Permissions and user roles
- **Blacklisting** — Manage restricted users

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ (for asset compilation)
- MySQL, PostgreSQL, SQLite, or other Laravel-supported database
- Git (optional, already removed from this workspace)

## Installation

1. **Clone or extract the project**

    ```bash
    cd Backend
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install Node.js dependencies**

    ```bash
    npm install
    ```

4. **Create environment file**

    ```bash
    cp .env.example .env
    ```

5. **Generate application key**

    ```bash
    php artisan key:generate
    ```

6. **Build frontend assets**
    ```bash
    npm run build
    ```

## Configuration

Update `.env` file with your settings:

```env
APP_NAME="Attendance Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# Add any other service credentials as needed
```

Run migrations to set up the database:

```bash
php artisan migrate
php artisan db:seed
```

## Running the Application

### Development Mode (Single Command)

```bash
composer run dev
```

This runs Laravel + Vite with hot module reloading.

### Development Mode (Manual)

**Terminal 1 — Laravel server:**

```bash
php artisan serve
```

**Terminal 2 — Vite dev server:**

```bash
npm run dev
```

### Production Build

```bash
npm run build
php artisan optimize
```

The application will be available at `http://localhost:8000`.

## Project Structure

```
app/
├── Actions/           # Business logic actions
│   ├── AcademicYear/
│   ├── AttendanceRecord/
│   ├── Classes/
│   ├── ClassSession/
│   ├── Student/
│   ├── Teacher/
│   └── ...
├── Http/
│   ├── Controllers/   # API controllers
│   └── Middleware/    # Request middleware
├── Models/            # Eloquent models
└── Providers/         # Service providers

config/               # Configuration files
database/
├── migrations/       # Database schema
└── seeders/          # Database seeds

resources/
├── css/              # Stylesheets
└── js/               # JavaScript/Vue components

routes/
├── api.php           # API routes
├── web.php           # Web routes
└── console.php       # Console commands
```

## API Endpoints

The backend provides RESTful API endpoints. Key resource groups:

- `/api/students` — Student management
- `/api/teachers` — Teacher management
- `/api/classes` — Class information
- `/api/attendance` — Attendance records
- `/api/academic-years` — Academic year management
- `/api/terms` — Term management
- `/api/users` — User management
- `/api/roles` — Role management
- `/api/permissions` — Permission management
- `/api/reports` — Attendance reports
- `/api/blacklist` — Blacklist management

See route definitions in `routes/api.php` for complete endpoint documentation.

## Database

### Models

Core models include:

- `User` — System users
- `Student` — Student information
- `Teacher` — Teacher information
- `Classes` — Class definitions
- `ClassSession` — Individual attendance sessions
- `AttendanceRecord` — Attendance tracking
- `GradeLevel` — Grade/level information
- `Subject` — Subjects offered
- `Term` — Academic terms
- `Role` — User roles
- `Permission` — System permissions
- `Blacklist` — Restricted accounts

See `app/Models/` for complete model definitions.

## Testing

Run all tests:

```bash
php artisan test
```

Run specific test file:

```bash
php artisan test tests/Feature/StudentTest.php
```

Run with coverage:

```bash
php artisan test --coverage
```

## Troubleshooting

### Port Already in Use

Change the port for `php artisan serve`:

```bash
php artisan serve --port=8001
```

### Database Connection Error

- Verify `.env` database credentials
- Ensure database server is running
- Check database user has necessary privileges

### Missing Dependencies

```bash
composer install
npm install
```

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Asset Issues

```bash
npm run build
php artisan optimize
```

## Support

For issues or questions, refer to:

- [Laravel Documentation](https://laravel.com/docs)
- Project documentation and comments in the codebase
