<?php

declare(strict_types=1);

/**
 * Escape special LIKE wildcards in user input for SQL LIKE patterns.
 */
function escapeLikePattern(string $value): string
{
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
}
