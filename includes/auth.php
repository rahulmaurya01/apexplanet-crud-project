<?php

declare(strict_types=1);

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
}

function currentUserId(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function currentUsername(): ?string
{
    return isset($_SESSION['username']) ? (string) $_SESSION['username'] : null;
}

function currentUserRole(): ?string
{
    return isset($_SESSION['role']) ? (string) $_SESSION['role'] : null;
}

function hasRole(string $role): bool
{
    return currentUserRole() === $role;
}

function canDeletePost(): bool
{
    return hasRole('admin');
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: ' . url('login.php'));
        exit;
    }
}

function requireRole(string $role): void
{
    requireLogin();
    if (!hasRole($role)) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function loginUser(int $userId, string $username, string $role): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
}

function logoutUser(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }
    session_destroy();
}
