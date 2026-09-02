# 🚀 Laravel Blog API

A production-oriented **RESTful Blog API** built with **Laravel 12**, developed as a practical backend project to apply and strengthen concepts in authentication, authorization, database design, Eloquent relationships, API architecture, validation, email workflows, and backend security.

The project is being developed incrementally, with each feature designed to solve a real backend requirement and reinforce Laravel fundamentals.

---

## 📌 Project Overview

This project is a backend API for a Blog application.

The current implementation focuses on building a solid backend foundation before completing the full Blog functionality.

The project currently includes:

- 🔐 Authentication & API Token Management
- 🔑 Password Recovery & Reset
- 📧 Email-based Password Reset
- ⏱️ Reset Token Expiration
- 📋 Task Management
- 📁 Project Management
- 🔗 Eloquent Relationships
- ⚡ Eager Loading
- 🗄️ Relational Database Design
- 🛡️ Protected API Routes
- ✅ Request Validation
- 🧪 API Testing with Postman

---

# 🏗️ Architecture

The project follows Laravel's standard layered backend architecture:

```text
Client
  │
  │ HTTP Request
  ▼
API Routes
  │
  ▼
Middleware
  │
  ├── Authentication
  └── Authorization
  │
  ▼
Form Request
  │
  └── Validation
  │
  ▼
Controller
  │
  ▼
Eloquent Model
  │
  ├── Relationships
  └── Eager Loading
  │
  ▼
MySQL Database
  │
  ▼
JSON Response
```

The goal is to keep responsibilities separated and make the API easier to maintain and extend.

---

# 🛠️ Tech Stack

| Technology         | Purpose              |
| ------------------ | -------------------- |
| PHP 8.2+           | Backend Language     |
| Laravel 12         | Backend Framework    |
| MySQL              | Relational Database  |
| Laravel Sanctum    | API Authentication   |
| Eloquent ORM       | Database Interaction |
| Laravel Migrations | Database Schema      |
| Form Requests      | Request Validation   |
| Laravel Mail       | Email Delivery       |
| Postman            | API Testing          |
| Git / GitHub       | Version Control      |

---

# 🔐 Authentication

Authentication is implemented using **Laravel Sanctum** and API Bearer Tokens.

## Authentication Flow

```text
Register
   │
   ▼
Create User
   │
   ▼
Login
   │
   ▼
Generate Sanctum Token
   │
   ▼
Client stores Token
   │
   ▼
Send Token with Protected Requests
   │
   ▼
Sanctum Middleware
   │
   ▼
Authenticated User
```

## Implemented Authentication Features

- User Registration
- User Login
- User Logout
- Get Authenticated User
- Forgot Password
- Password Reset
- Reset Token Generation
- Reset Token Expiration
- Password Reset Email

---

# 🔑 Password Reset

The password recovery system uses a secure token-based flow.

```text
POST /api/auth/forgot-password
          │
          ▼
Generate Reset Token
          │
          ▼
Store Token
          │
          ▼
Send Reset Email
          │
          ▼
User Opens Reset URL
          │
          ▼
POST /api/auth/reset-password
          │
          ▼
Validate Token
          │
          ▼
Check Token Expiration
          │
          ▼
Update Password
          │
          ▼
Invalidate Reset Token
```

### Token Expiration

The application checks the token creation time against the expiration window.

Example:

```php
$resetToken->created_at < now()->subMinutes(60)
```

`subMinutes(60)` calculates a timestamp representing **60 minutes before the current time**.

This prevents old reset links from remaining valid indefinitely.

---

# 📧 Email System

The password recovery workflow sends an email containing the password reset URL.

The reset URL contains the user's email and reset token.

Values used inside the URL are encoded using `urlencode()` to ensure they are safely represented as URL parameters.

The email functionality is implemented through Laravel's mail system.

> Mailgun was initially explored during development, but it is not intended to remain part of the final implementation.

---

# 📋 Task Management

The project includes a Task management module designed to practice relationships and relational database design.

## Task Fields

A task currently contains:

- Title
- Status
- Priority
- Assigned User
- Project
- Due Date

## Task Status

```text
pending
in_progress
completed
cancelled
```

## Task Priority

```text
low
medium
high
```

---

# 📁 Project Management

The Project module contains:

- Name
- Description
- Status
- Start Date
- End Date
- Creator

Projects are connected to users and tasks through Eloquent relationships.

---

# 🔗 Database Relationships

One of the main goals of this project is practicing proper relational database design.

Current relationship structure:

```text
User
 │
 ├──────────────┐
 │              │
 ▼              ▼
Projects      Tasks
 │              │
 │              │
 └──────┐  ┌────┘
        ▼  ▼
        Project
```

### User → Projects

A user can be associated with multiple projects.

```php
belongsToMany()
```

### User → Tasks

A user can have multiple assigned tasks.

```php
hasMany()
```

### Project → Tasks

A project can contain multiple tasks.

```php
hasMany()
```

### Task → Project

A task belongs to a project.

```php
belongsTo()
```

### Task → Assigned User

A task can belong to an assigned user.

```php
belongsTo()
```

---

# ⚡ Eloquent & Eager Loading

The project uses Eloquent relationships and eager loading to retrieve related data efficiently.

For example:

```php
$task = Task::with([
    'project:id,name,created_by',
    'project.creator:id,name',
    'assignedTo:id,name'
])->find($id);
```

This allows the API to retrieve the task together with its related project, project creator, and assigned user.

### Nested Relationship Loading

```text
Task
 │
 ├── assignedTo
 │     ├── id
 │     └── name
 │
 └── project
       ├── id
       ├── name
       └── creator
             ├── id
             └── name
```

This was an important part of learning how Laravel handles nested Eloquent relationships.

---

# 🗄️ Database Design

The project uses Laravel Migrations to manage the database schema.

The migrations include:

- Foreign Keys
- Nullable Foreign Keys
- Enum Columns
- Cascade Deletes
- Null-on-Delete
- Timestamps
- Relationships between entities

Example:

```php
foreignId('project_id')
    ->constrained('projects')
    ->cascadeOnDelete();
```

If a project is deleted, its related tasks are automatically deleted.

For assigned users:

```php
foreignId('assigned_to')
    ->nullable()
    ->constrained('users')
    ->nullOnDelete();
```

This allows a task to exist without an assigned user.

If the assigned user is deleted, the task remains but its `assigned_to` value becomes `NULL`.

---

# 🛡️ API Security

The API separates public and protected endpoints.

## Public Endpoints

Authentication endpoints that should be accessible without an authentication token:

```text
POST /api/auth/register
POST /api/auth/login
POST /api/auth/forgot-password
POST /api/auth/reset-password
```

## Protected Endpoints

Endpoints requiring authentication:

```text
GET  /api/auth/user
POST /api/auth/logout

Tasks routes
Projects routes
```

Protected requests use:

```http
Authorization: Bearer <SANCTUM_TOKEN>
```

Laravel Sanctum validates the token before allowing access to protected resources.

---

# 🌐 API Endpoints

## Authentication

| Method | Endpoint                    | Auth | Description             |
| ------ | --------------------------- | ---- | ----------------------- |
| POST   | `/api/auth/register`        | ❌   | Register a new user     |
| POST   | `/api/auth/login`           | ❌   | Login and receive token |
| GET    | `/api/auth/user`            | ✅   | Get authenticated user  |
| POST   | `/api/auth/logout`          | ✅   | Logout                  |
| POST   | `/api/auth/forgot-password` | ❌   | Request password reset  |
| POST   | `/api/auth/reset-password`  | ❌   | Reset password          |

---

## Tasks

| Method | Endpoint     | Auth | Description                |
| ------ | ------------ | ---- | -------------------------- |
| GET    | `/api/tasks` | ✅   | Get tasks                  |
| POST   | `/api/tasks` | ✅   | Create a task              |
| ...    | ...          | ✅   | Additional task operations |

---

## Projects

| Method | Endpoint        | Auth | Description                   |
| ------ | --------------- | ---- | ----------------------------- |
| GET    | `/api/projects` | ✅   | Get projects                  |
| POST   | `/api/projects` | ✅   | Create a project              |
| ...    | ...             | ✅   | Additional project operations |

> The API is still under development, so the endpoint list will continue to expand as new features are implemented.

---

# ✅ Validation

Laravel **Form Requests** are used to handle request validation.

This keeps validation logic separate from controllers and helps maintain cleaner code.

Example concept:

```text
HTTP Request
     │
     ▼
Form Request
     │
     ├── Valid
     │     ↓
     │   Controller
     │
     └── Invalid
           ↓
       Validation Error
```

---

# 🧪 API Testing

The API is currently tested using **Postman**.

Testing includes:

- Successful requests
- Invalid requests
- Validation failures
- Authentication
- Protected routes
- Missing Bearer Tokens
- Invalid authentication tokens
- Database constraints
- Relationships
- Password reset
- Token expiration
- Email workflows

---

# 🐛 Development & Debugging

During development, I worked through several real backend issues, including:

- Foreign key constraint errors
- Existing database tables during migrations
- Required database fields without defaults
- Incorrect Eloquent relationships
- `RelationNotFoundException`
- Sanctum authentication issues
- Missing Bearer tokens
- Composer dependency/version conflicts
- Email configuration problems

These problems were part of the learning process and helped me understand Laravel's behavior rather than simply following predefined tutorials.

---

# 📈 Current Progress

| Module                  | Status |
| ----------------------- | ------ |
| Laravel Setup           | ✅     |
| Database Migrations     | ✅     |
| User Model              | ✅     |
| Register                | ✅     |
| Login                   | ✅     |
| Sanctum Authentication  | ✅     |
| Protected Routes        | ✅     |
| Logout                  | ✅     |
| Forgot Password         | ✅     |
| Reset Token             | ✅     |
| Token Expiration        | ✅     |
| Reset Password Email    | ✅     |
| Reset Password          | ✅     |
| Project Module          | 🟢     |
| Task Module             | 🟢     |
| Eloquent Relationships  | ✅     |
| Eager Loading           | ✅     |
| Form Request Validation | 🟢     |
| API Testing             | 🟢     |
| Authorization Rules     | 🟡     |
| Blog/Post Module        | 🟡     |
| Automated Tests         | 🔴     |
| API Documentation       | 🔴     |
| Deployment              | 🔴     |

### Legend

```text
🟢 Currently implemented / actively developed
🟡 Planned / partially implemented
🔴 Not started
```

---

# 🗺️ Roadmap

## Phase 1 — Backend Foundation

- [x] Laravel project setup
- [x] Database structure
- [x] Migrations
- [x] Models
- [x] Authentication
- [x] Sanctum
- [x] Protected routes

## Phase 2 — Core Modules

- [x] Projects
- [x] Tasks
- [x] Relationships
- [x] Eager Loading
- [x] Validation

## Phase 3 — Blog Features

- [ ] Posts
- [ ] Categories
- [ ] Comments
- [ ] Post relationships
- [ ] Post authorization

## Phase 4 — Security & Quality

- [ ] Advanced authorization
- [ ] Policies
- [ ] API Resources
- [ ] Better error handling
- [ ] Automated tests
- [ ] Feature tests
- [ ] Security improvements

## Phase 5 — Production Readiness

- [ ] API documentation
- [ ] Pagination
- [ ] Performance optimization
- [ ] Production configuration
- [ ] Deployment

---

# 🧠 What I Have Learned

This project has been a practical way to understand how a real Laravel backend is structured.

### Laravel

- Routing
- Controllers
- Models
- Migrations
- Middleware
- Form Requests
- Configuration
- Environment variables

### Authentication

- Authentication vs Authorization
- Laravel Sanctum
- API Tokens
- Bearer Authentication
- Protected Routes
- Logout
- Password Recovery
- Password Reset

### Database

- Relational Database Design
- Foreign Keys
- Constraints
- One-to-Many Relationships
- Many-to-Many Relationships
- Eloquent ORM
- Eager Loading
- Nested Relationships

### API Development

- RESTful APIs
- HTTP Methods
- HTTP Status Codes
- JSON Responses
- Request Validation
- Authentication Middleware
- Error Handling

### Email

- Laravel Mail
- Mailables
- Reset Emails
- Reset Tokens
- Token Expiration
- URL Encoding

### Debugging

More importantly, I learned how to investigate and solve backend problems instead of treating errors as isolated issues.

---

# 🔧 Installation

Clone the repository:

```bash
git clone https://github.com/OMDevOmarMagdy/blog.git
```

Navigate to the project:

```bash
cd blog
```

Install PHP dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the database inside `.env`.

Run migrations:

```bash
php artisan migrate
```

Start the development server:

```bash
php artisan serve
```

The API will be available through the Laravel development server.

---

# 🔐 Environment Configuration

The `.env` file should contain your local configuration.

Example:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Email configuration should also be provided through environment variables.

> Never commit `.env` or real credentials, API keys, tokens, or passwords to GitHub.

---

# 📂 Project Structure

The project follows Laravel's standard structure.

```text
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
│
├── Mail/
├── Models/
│
database/
├── migrations/
└── seeders/

routes/
└── api.php

config/
resources/
storage/
tests/
```

---

# 📌 Development Philosophy

This project is being built with a focus on **understanding backend development**, not simply completing features.

For every feature, the goal is to understand:

```text
Why?
 ↓
How?
 ↓
Implementation
 ↓
Testing
 ↓
Debugging
 ↓
Improvement
```

The project will continue evolving as I learn more about Laravel, backend architecture, databases, authentication, authorization, and security.

---

# 👨‍💻 Developer

## Omar Magdy

Computer Science & Artificial Intelligence Student
Backend Developer

### Current Focus

- PHP
- Laravel
- Node.js
- REST APIs
- Databases
- Authentication
- Backend Security
- Software Architecture

---

## ⭐ Project Status

**🚧 Actively Under Development**

This repository represents my practical progress while learning and applying Laravel backend development.

> **Learn → Build → Break → Debug → Understand → Improve.**
