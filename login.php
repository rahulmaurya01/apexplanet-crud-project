<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/layout.php';

if (isLoggedIn()) {
    header('Location: ' . url('posts/index.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password'])) {
            loginUser((int) $row['id'], (string) $row['username']);
            $target = $_SESSION['redirect_after_login'] ?? null;
            unset($_SESSION['redirect_after_login']);
            $go = url('posts/index.php');
            if (is_string($target) && $target !== '' && str_starts_with($target, '/') && !str_starts_with($target, '//')) {
                $go = $target;
            }
            header('Location: ' . $go);
            exit;
        }
        $error = 'Invalid username or password.';
    }
}

layoutHeader('Login', 'login');
?>
<h1>Login</h1>
<?php if ($error !== '') : ?>
    <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="post" class="form-card">
    <?php echo csrfField(); ?>
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required maxlength="100" autocomplete="username"
           value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required autocomplete="current-password">

    <button type="submit" class="btn primary">Login</button>
</form>
<p class="muted">No account? <a href="<?php echo htmlspecialchars(url('register.php'), ENT_QUOTES, 'UTF-8'); ?>">Register</a></p>
<?php
layoutFooter();
