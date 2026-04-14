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

## Task 3 — What works

- **Search:** GET form on `posts/index.php`; filters by **title** or **content** (`LIKE`, wildcards escaped)
- **Pagination:** 5 posts per page; page numbers + Prev/Next; search query preserved in URLs
- **UI:** Toolbar, search bar, pagination, responsive tweaks (`assets/style.css`)

## Task 4 — What works

- **Prepared statements everywhere:** post list count, search, insert, update, delete, login/register checks
- **Server-side validation:** centralized checks in `includes/validation.php` for username, password, title, and content
- **Client-side validation:** `pattern`, `minlength`, and `maxlength` attributes on auth/post forms
- **Secure session handling:** cookie flags (`HttpOnly`, `SameSite=Lax`, conditional `Secure`) + session ID regeneration at login
- **User roles and permissions:** `users.role` (`admin` / `editor`) with role checks in `includes/auth.php`; only admin can delete posts

## Task 5 — Final project status

- All features from Tasks 1-4 integrated into a single working app
- Final testing and debugging pass completed
- Final report added: `FINAL_TEST_REPORT.md`
- Project is submission-ready

## Run locally (XAMPP)

1. Start Apache and MySQL.
2. Put the project under `htdocs` (e.g. `C:\xampp\htdocs\apexplanet-crud-project`).
3. Import `database/schema.sql` once (creates `blog`, `users`, `posts`).
   - For existing Task 2/3 DB, run once: `ALTER TABLE users ADD COLUMN role ENUM('admin','editor') NOT NULL DEFAULT 'editor';`
   - Optional: make your account admin: `UPDATE users SET role='admin' WHERE username='your_username';`
4. Open `http://localhost/apexplanet-crud-project/`
5. Register a user, then manage posts from **Posts** / **New post**.

## Task progress checklist

- [x] Task 1: Setting up development environment
- [x] Task 2: Basic CRUD application
- [x] Task 3: Advanced features implementation
- [x] Task 4: Security enhancements
- [x] Task 5: Final project and certification
