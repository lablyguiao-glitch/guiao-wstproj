# Personal Task Manager

Project Code: WST21-PM-2026-SF
Student Name: Lovely Guiao
Course & Year: BSIT-2 SEC10
Database Used: SQLite

Features:
- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status

Extra features:
- **Overdue tasks**: pending tasks past their due date are flagged in red, counted on the dashboard, and can be filtered.
- **Dashboard filters**: All, Pending, Overdue and Completed, each with a live count.
- **"Due today" and "in N days" labels** on every task.
- **Responsive design** for desktop and phone.

## Built with

- Laravel 12 (Routes → Controller → Model → Database → Blade)
- Blade views and Tailwind CSS 4
- SQLite database

## How it works

| Part | File |
|---|---|
| Routes | `routes/web.php` |
| Controller | `app/Http/Controllers/TaskController.php` |
| Model | `app/Models/Task.php` |
| Migration (`tasks` table) | `database/migrations/*_create_tasks_table.php` |
| Blade views | `resources/views/tasks/` and `resources/views/layouts/app.blade.php` |
| Sample data | `database/seeders/DatabaseSeeder.php` |
| Tests | `tests/Feature/TaskTest.php` |

### `tasks` table

| Field | Purpose |
|---|---|
| id | Task ID |
| task_name | Name of the task |
| description | Task details |
| status | Pending / Completed |
| due_date | Task deadline |

A task is **overdue** when its status is still *Pending* and its `due_date` is before today. It is calculated, not stored.

## Running it locally

Requirements: PHP 8.2+, Composer, and Node.js.

```bash
git clone <this-repo-url>
cd <repo-folder>

composer install
npm install

cp .env.example .env
php artisan key:generate

# create the SQLite database file (Windows PowerShell: New-Item database/database.sqlite)
touch database/database.sqlite
php artisan migrate --seed

npm run build
php artisan serve
```

Open http://127.0.0.1:8000.

Run the tests with `php artisan test`.

### Using MySQL instead

Create a database, then change these lines in `.env` and run `php artisan migrate --seed`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```
