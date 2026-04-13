# ApexPlanet PHP CRUD Project

This project is organized in a **task-wise structure** so each internship milestone is tracked clearly.

## Task-wise folder structure

```text
apexplanet-crud-project/
|- assets/
|- config/
|- database/
|- includes/
|- posts/
|- tasks/
|  |- task-1-setup/
|  |- task-2-basic-crud/
|  |- task-3-advanced-features/
|  |- task-4-security-enhancements/
|  |- task-5-final-project/
|- index.php
|- login.php, register.php, logout.php
|- .env.example
|- .gitignore
|- README.md
```

## Task 2 — What works

- **Register / Login / Logout** with `password_hash` / `password_verify`
- **Session** for logged-in state; CRUD pages require login
- **Posts:** list, create, edit, delete (PDO prepared statements)
- **CSRF** tokens on mutating forms
- **Base URL** auto-detected from document root (`config/app.php`)

## Run locally (XAMPP)

1. Start Apache and MySQL.
2. Put the project under `htdocs` (e.g. `C:\xampp\htdocs\apexplanet-crud-project`).
3. Import `database/schema.sql` once (creates `blog`, `users`, `posts`).
4. Open `http://localhost/apexplanet-crud-project/`
5. Register a user, then manage posts from **Posts** / **New post**.

## Task progress checklist

- [x] Task 1: Setting up development environment
- [x] Task 2: Basic CRUD application
- [ ] Task 3: Advanced features implementation
- [ ] Task 4: Security enhancements
- [ ] Task 5: Final project and certification
