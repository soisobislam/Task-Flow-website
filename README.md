# TaskFlow

TaskFlow is a Laravel 12 project and task management workspace for small teams. It provides role-aware authentication, manager-owned projects, project members, task assignment, status workflow, and responsive Blade/Tailwind screens.

Live wesite Link:
https://task-flow-website.onrender.com

## Features

- Session authentication with registration, login, logout, and CSRF protection
- Admin, manager, and employee roles on users
- Policy-backed project authorization
- Manager project creation with date validation
- Project member relationships
- Task creation, assignment, priority, status, deadlines, and detail views
- Employee-safe access boundaries and status workflow
- SQLite by default, with MySQL-compatible migrations
- Feature tests for guest access, roles, project ownership, and validation

## Demo accounts

All seeded accounts use the password `password`:

| Role | Email |
| --- | --- |
| Admin | admin@example.com |
| Manager | manager@example.com |
| Employee | employee@example.com |

## Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Open `http://localhost:8000`. To use MySQL, set `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` before migrating.

For Render, set both `APP_URL` and `ASSET_URL` to the exact HTTPS URL shown by Render, without a trailing slash. After changing either value, trigger a new deploy so Laravel rebuilds its cached configuration and generates CSS/JavaScript links for the correct host.

## Testing

```bash
php artisan test
```

## Project structure

- `app/Models`: users, projects, and tasks
- `app/Policies`: server-side authorization rules
- `app/Http/Controllers`: focused web controllers
- `app/Http/Requests`: reusable validation rules
- `resources/views`: Blade layouts and role-aware screens
- `database/migrations`: relational schema and foreign keys
- `database/seeders`: demo data for portfolio review

## Next improvements

Comments, secure attachment downloads, admin user management, richer role dashboards, filters, notifications, API resources, and expanded policy coverage are planned next.
