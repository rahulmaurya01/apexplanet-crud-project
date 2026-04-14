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
    $password2 = (string) ($_POST['password_confirm'] ?? '');

    $error = validateUsername($username) ?? validatePassword($password);
    if ($error !== null) {
        // Error already set by validators.
    } elseif ($password !== $password2) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $pdo = getDatabaseConnection();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:u, :p, :r)');
            $stmt->execute(['u' => $username, 'p' => $hash, 'r' => 'editor']);
            $_SESSION['flash'] = 'Registration successful. Please log in.';
            header('Location: ' . url('login.php'));
            exit;
        } catch (\PDOException $e) {
            if ((int) $e->errorInfo[1] === 1062) {
                $error = 'That username is already taken.';
            } else {
                $error = 'Could not register. Please try again.';
            }
        }
    }
}

layoutHeader('Register', 'register');
?>
<h1>Create account</h1>
<?php if ($error !== '') : ?>
    <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="post" class="form-card">
    <?php echo csrfField(); ?>
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required maxlength="100" minlength="3" pattern="[a-zA-Z0-9_]{3,100}" autocomplete="username"
           value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required minlength="8" maxlength="72" autocomplete="new-password">

    <label for="password_confirm">Confirm password</label>
    <input type="password" id="password_confirm" name="password_confirm" required minlength="8" maxlength="72" autocomplete="new-password">
    <p class="hint">Password should include uppercase, lowercase, and a number.</p>

    <button type="submit" class="btn primary">Register</button>
</form>
<p class="muted">Already have an account? <a href="<?php echo htmlspecialchars(url('login.php'), ENT_QUOTES, 'UTF-8'); ?>">Login</a></p>
<?php
layoutFooter();
