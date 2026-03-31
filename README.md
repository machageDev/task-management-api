# Task Management API - Internship Challenge

A Laravel-based RESTful API for managing tasks with specific business logic for status progression and priority sorting.

## Features
- Task CRUD: Create, List, Update Status, and Delete tasks.
- Smart Sorting: Tasks are sorted by Priority (High > Medium > Low) and then by Due Date.
- Status Guard: Enforces strict progression (Pending -> In Progress -> Done).
- Security: Only Done tasks can be deleted (returns 403 otherwise).
- Bonus: Daily summary report endpoint.

## Local Setup
1. Clone and Install:
   composer install
2. Environment:
   - Copy .env.example to .env.
   - Create a MySQL database named task_management_db.
   - Update DB_DATABASE, DB_USERNAME, and DB_PASSWORD in .env.
3. Database:
   php artisan migrate
4. Run:
   php artisan serve

## API Endpoints
- POST /api/tasks - Create a task.
- GET /api/tasks - List all tasks.
- PATCH /api/tasks/{id}/status - Update status.
- DELETE /api/tasks/{id} - Delete completed task.
- GET /api/tasks/report?date=YYYY-MM-DD - Daily summary report.

## Deployment
Deploy to Railway or Render by connecting your GitHub repo and adding a MySQL database add-on.