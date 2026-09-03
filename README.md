# Laravel Project & Task API

This project is a Laravel backend API built to practice real application development concepts such as authentication, authorization, database relationships, and API route protection.

The project includes:
- User authentication
- Password reset using email
- Project management
- Task management
- Admin-only access control
- Validation using Form Requests
- Model-based business logic
- Policies for authorization

---

## Project Structure

### Controllers
- `UserController`  
  Handles user registration, login, logout, password reset, and authenticated user retrieval.

- `ProjectController`  
  Handles project creation, retrieval, task listing, and project-related data.

- `TaskController`  
  Handles task creation, reading, and relationship with projects.

### Middleware
- `AdminMiddleware`  
  Restricts access to admin-only routes, such as listing all projects.

### Requests
- `RegisterUserRequest`
- `LoginUserRequest`
- `ForgetPasswordRequest`
- `ResetPasswordRequest`
- `StoreProjectRequest`
- `StoreTaskRequest`

These are used to validate incoming API requests before processing.

### Models
- `User`
- `Project`
- `Task`

These models define the application data and relationships between users, projects, and tasks.

### Policies
- `ProjectPolicy`
- `TaskPolicy`

These policies control which users are allowed to perform actions such as viewing, updating, or deleting projects and tasks.

### Mail
- `ForgetPassMail`

Used to send password reset emails to users.

### Providers
- `AppServiceProvider`

Registers application-level services and bootstrapping logic.

---

## Main Features

### Authentication
- Register user
- Login user
- Get authenticated user
- Logout user
- Forgot password
- Reset password

### Projects
- Create project
- View project details
- View all projects
- View tasks belonging to a project
- Admin-only access to all projects

### Tasks
- Create task
- View all tasks
- View task details
- View project related to a task

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

> Note: GET /api/projects is protected using the admin middleware.

---

## What I Learned

This project helped me understand:
- Laravel routing
- Controller-based API structure
- Request validation
- Authentication with Sanctum
- Database relationships
- Middleware and authorization
- Policies for access control
- Sending email from Laravel
- Building a structured backend API

---

## Project Status

### Completed
- Laravel API setup
- User authentication
- Password reset flow
- Project logic
- Task logic
- Protected routes
- Admin middleware
- Validation layer
- User, project, and task models
- Policies for authorization

### Future Improvements
- Standardized API response format
- More advanced authorization rules
- Automated testing
- Better error handling
- Filtering and pagination
- Frontend integration

---

## Run the Project

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve