# Task 4 - Security Enhancements

## Objective

Secure the application against common vulnerabilities with validation and role-based controls.

## Implemented

- **Prepared statements** across authentication and posts operations
- **Server-side validation** in `includes/validation.php`
  - Username format restrictions
  - Strong password rules
  - Post title/content validation
- **Client-side validation** via HTML attributes (`pattern`, `minlength`, `maxlength`)
- **Session hardening**
  - `HttpOnly`, `SameSite=Lax`, conditional `Secure` cookie config
  - `session_regenerate_id(true)` at login
- **RBAC (roles & permissions)**
  - `users.role` with `admin` / `editor`
  - Delete endpoint (`posts/delete.php`) restricted to admin only
  - UI reflects role and delete permission

## Database note

If your DB was created before Task 4, run:

```sql
ALTER TABLE users ADD COLUMN role ENUM('admin','editor') NOT NULL DEFAULT 'editor';
UPDATE users SET role='admin' WHERE username='your_username';
```

## Status

Completed.
