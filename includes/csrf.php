<?php

declare(strict_types=1);

function csrfToken(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrfField(): string
{
    $token = htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8');

    return '<input type="hidden" name="_csrf" value="' . $token . '">';
}

function validateCsrf(): void
{
    $sent = $_POST['_csrf'] ?? '';
    $valid = isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], (string) $sent);
    if (!$valid) {
        http_response_code(403);
        exit('Invalid security token. Please try again.');
    }
}
