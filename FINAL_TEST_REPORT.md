# Final Test Report (Task 5)

This document captures the final integration and testing pass for the PHP + MySQL CRUD application.

## Environment

- OS: Windows 10
- Stack: XAMPP (Apache + MySQL), PHP 8.2
- App URL: `http://localhost/apexplanet-crud-project/`
- Database: `blog`

## Integration checklist

- [x] Authentication (register/login/logout)
- [x] Password hashing and verification
- [x] Session-based access control
- [x] CRUD operations for posts
- [x] Search by title/content
- [x] Pagination on posts listing
- [x] Security controls (CSRF, validation, role-based permissions)

## Functional test cases

1. Register with valid credentials -> success
2. Register with invalid username/password -> validation error
3. Login with correct credentials -> success
4. Login with wrong credentials -> rejected
5. Create post -> appears in posts list
6. Edit post -> updated content shown
7. Delete post as admin -> success
8. Delete post as editor -> blocked (button hidden and endpoint restricted)
9. Search posts by keyword -> filtered results
10. Pagination with multiple posts -> page navigation works
11. Open protected pages while logged out -> redirected to login

## Security checks

- [x] PDO prepared statements used in data access paths
- [x] CSRF tokens on mutating forms
- [x] Server-side validation for auth/posts forms
- [x] Session cookie flags (`HttpOnly`, `SameSite=Lax`, conditional `Secure`)
- [x] Session ID regenerated on successful login
- [x] Role-based delete permission (`admin` only)

## Final status

Task 5 completed: project is integrated, tested, and submission-ready.
