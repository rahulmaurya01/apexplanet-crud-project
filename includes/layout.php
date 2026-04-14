<?php

declare(strict_types=1);

function layoutHeader(string $title, string $active = ''): void
{
    $user = currentUsername();
    $role = currentUserRole();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?> — Blog CRUD</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(url('assets/style.css'), ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="<?php echo htmlspecialchars(url('index.php'), ENT_QUOTES, 'UTF-8'); ?>">Blog CRUD</a>
        <nav class="nav">
            <?php if ($user !== null) : ?>
                <a href="<?php echo htmlspecialchars(url('posts/index.php'), ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $active === 'posts' ? 'active' : ''; ?>">Posts</a>
                <a href="<?php echo htmlspecialchars(url('posts/create.php'), ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $active === 'create' ? 'active' : ''; ?>">New post</a>
                <span class="user-pill"><?php echo htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ($role !== null) : ?>
                    <span class="role-pill"><?php echo htmlspecialchars(strtoupper($role), ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars(url('logout.php'), ENT_QUOTES, 'UTF-8'); ?>">Logout</a>
            <?php else : ?>
                <a href="<?php echo htmlspecialchars(url('login.php'), ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $active === 'login' ? 'active' : ''; ?>">Login</a>
                <a href="<?php echo htmlspecialchars(url('register.php'), ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $active === 'register' ? 'active' : ''; ?>">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container main">
    <?php
    if (!empty($_SESSION['flash'])) {
        $msg = (string) $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<p class="flash">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</p>';
    }
}

function layoutFooter(): void
{
    ?>
</main>
<footer class="site-footer">
    <div class="container">ApexPlanet — PHP &amp; MySQL Final Project (Tasks 1-5)</div>
</footer>
</body>
</html>
    <?php
}
