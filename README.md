# Laravel Project & Task API

This project is a Laravel backend API built to practice authentication, authorization, API routes, and database relationships.

The project includes:

- User authentication
- Password reset flow
- Project management
- Task management
- Protected routes using Sanctum
- Admin-only project listing

---

## Features

### Authentication

- User registration
- User login
- Get authenticated user
- User logout
- Forgot password
- Reset password

### Projects

- Create a project
- View a single project
- View all projects (admin only)
- View tasks related to a project

### Tasks

- View all tasks
- View a single task
- Create a task
- View the project related to a task

---

## Tech Stack

- PHP
- Laravel 12
- MySQL
- Laravel Sanctum
- Eloquent ORM
- Laravel Mail

---

## Main API Routes

### Auth Routes

- POST /api/auth/register
- POST /api/auth/login
- POST /api/auth/forgot-password
- POST /api/auth/reset-password
- GET /api/auth/user
- POST /api/auth/logout

### Task Routes

- GET /api/tasks
- GET /api/tasks/{id}
- POST /api/tasks
- GET /api/tasks/{id}/project

### Project Routes

- POST /api/projects
- GET /api/projects
- GET /api/projects/{id}/tasks
- GET /api/projects/{id}

> Note: GET /api/projects is protected with the admin middleware.

---

## Project Purpose

This project helped me learn:

- Laravel routing
- Controller structure
- Request validation
- Authentication with Sanctum
- Database relationships
- Middleware and authorization
- Email-based password reset
- Building a structured API

---

## Project Status

### Completed

- Laravel project setup
- User authentication
- Password reset flow
- Project CRUD logic
- Task CRUD logic
- Protected API routes
- Admin route for project listing
- Model and relationship setup

### Future Improvements

- Better API response format
- More advanced authorization rules
- Automated tests
- Error handling improvements
- More project and task filtering
- Frontend integration

---

## How to Run

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
