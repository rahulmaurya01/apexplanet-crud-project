# Task 2 - Basic CRUD Application

## Objective

Build CRUD for blog posts and basic user authentication.

## Implemented

- MySQL `blog` database with `users` and `posts` (see `database/schema.sql`)
- Registration and login (`register.php`, `login.php`) with hashed passwords
- Session-based auth (`includes/auth.php`); CRUD requires login
- Post list, create, update, delete under `posts/`
- CSRF protection on POST forms (`includes/csrf.php`)

## Status

Completed.
