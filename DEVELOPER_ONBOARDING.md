# API Developer Onboarding

This guide helps the next developer clone, configure, and run the backend quickly.

## 1. Tech Stack

- Laravel 12
- PHP 8.2+
- Composer
- Node.js + npm (for Vite assets)
- Database: SQLite (default) or MySQL

## 2. Clone and Enter Project

```bash
git clone <your-repo-url>
cd Attendance-Management/attendance-management-api
```

## 3. Install Dependencies

```bash
composer install
npm install
```

## 4. Environment Setup

Create `.env` from the example:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate app key:

```bash
php artisan key:generate
```

## 5. Database Setup

### Option A (Default, easiest): SQLite

Create SQLite database file:

```powershell
New-Item -Path database\database.sqlite -ItemType File -Force
```

Make sure `.env` contains:

```env
DB_CONNECTION=sqlite
```

Run migration + seeder:

```bash
php artisan migrate --seed
```

### Option B: MySQL

Update `.env` with MySQL values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_management
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan migrate --seed
```

## 6. Run the API

Recommended (all services together):

```bash
composer run dev
```

Manual:

```bash
php artisan serve
npm run dev
```

API base URL (default):

- `http://127.0.0.1:8000`
- API routes are in [routes/api.php](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-api\routes\api.php)

## 7. Useful Commands

```bash
php artisan test
php artisan config:clear
php artisan cache:clear
php artisan route:list
```

## 8. Important Folders

- [app/Actions](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-api\app\Actions): use-case/business logic
- [app/Http/Controllers](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-api\app\Http\Controllers): API controllers
- [app/Models](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-api\app\Models): Eloquent models
- [database/migrations](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-api\database\migrations): schema history
- [database/seeders](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-api\database\seeders): seed data

## 9. Handover Notes

- Keep API contracts stable for UI calls.
- If route/response changes, update UI API layer in:
  - [src/shared/api/http.ts](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-ui\src\shared\api\http.ts)
  - [src/features](D:\Year 3 Semester 2\SA\Attendance-Management\attendance-management-ui\src\features)
- Run `php artisan test` before handing over.
