<?php

declare(strict_types=1);

$projectRoot = realpath(__DIR__ . '/..');
$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

if ($projectRoot && $documentRoot && str_starts_with($projectRoot, $documentRoot)) {
    $relative = substr($projectRoot, strlen($documentRoot));
    $basePath = str_replace('\\', '/', $relative);
    define('BASE_PATH', $basePath === '' || $basePath === '/' ? '' : rtrim($basePath, '/'));
} else {
    define('BASE_PATH', '');
}

function url(string $path = ''): string
{
    $path = ltrim(str_replace('\\', '/', $path), '/');
    if (BASE_PATH === '') {
        return '/' . $path;
    }

    return BASE_PATH . '/' . $path;
}
