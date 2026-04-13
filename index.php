<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/layout.php';

if (isLoggedIn()) {
    header('Location: ' . url('posts/index.php'));
    exit;
}

$dbOk = false;
$dbMessage = '';

try {
    getDatabaseConnection()->query('SELECT 1');
    $dbOk = true;
    $dbMessage = 'Connected to MySQL successfully.';
} catch (Throwable $e) {
    $dbMessage = 'Database: ' . $e->getMessage();
}

layoutHeader('Home', '');
?>
<section class="hero">
    <h1>Blog CRUD — Task 2</h1>
    <p class="lead">Register or log in to create, read, update, and delete posts.</p>
    <p class="db-pill <?php echo $dbOk ? 'ok' : 'bad'; ?>"><strong>Status:</strong> <?php echo htmlspecialchars($dbMessage, ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="hero-actions">
        <a class="btn primary" href="<?php echo htmlspecialchars(url('register.php'), ENT_QUOTES, 'UTF-8'); ?>">Register</a>
        <a class="btn" href="<?php echo htmlspecialchars(url('login.php'), ENT_QUOTES, 'UTF-8'); ?>">Login</a>
    </div>
</section>
<?php
layoutFooter();
