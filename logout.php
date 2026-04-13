<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

logoutUser();
header('Location: ' . url('index.php'));
exit;
