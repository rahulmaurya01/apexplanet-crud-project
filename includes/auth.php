<?php

declare(strict_types=1);

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['username']);
}

function currentUserId(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function currentUsername(): ?string
{
    return isset($_SESSION['username']) ? (string) $_SESSION['username'] : null;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: ' . url('login.php'));
        exit;
    }
}

function loginUser(int $userId, string $username): void
{
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
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
