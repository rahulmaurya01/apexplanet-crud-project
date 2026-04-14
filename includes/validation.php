<?php

declare(strict_types=1);

function validateUsername(string $username): ?string
{
    if ($username === '' || strlen($username) > 100) {
        return 'Username is required (max 100 characters).';
    }
    if (!preg_match('/^[a-zA-Z0-9_]{3,100}$/', $username)) {
        return 'Username must be 3-100 chars and use only letters, numbers, underscore.';
    }

    return null;
}

function validatePassword(string $password): ?string
{
    if (strlen($password) < 8 || strlen($password) > 72) {
        return 'Password must be 8-72 characters.';
    }
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return 'Password must include uppercase, lowercase, and a number.';
    }

    return null;
}

function validatePostTitle(string $title): ?string
{
    if ($title === '' || strlen($title) > 255) {
        return 'Title is required (max 255 characters).';
    }

    return null;
}

function validatePostContent(string $content): ?string
{
    if ($content === '') {
        return 'Content is required.';
    }
    if (strlen($content) < 10) {
        return 'Content should be at least 10 characters.';
    }

    return null;
}
