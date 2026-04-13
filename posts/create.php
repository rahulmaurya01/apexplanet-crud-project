<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/layout.php';

requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();
    $title = trim((string) ($_POST['title'] ?? ''));
    $content = trim((string) ($_POST['content'] ?? ''));

    if ($title === '' || strlen($title) > 255) {
        $error = 'Title is required (max 255 characters).';
    } elseif ($content === '') {
        $error = 'Content is required.';
    } else {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('INSERT INTO posts (title, content) VALUES (:t, :c)');
        $stmt->execute(['t' => $title, 'c' => $content]);
        $_SESSION['flash'] = 'Post created.';
        header('Location: ' . url('posts/index.php'));
        exit;
    }
}

layoutHeader('New post', 'create');
?>
<h1>New post</h1>
<?php if ($error !== '') : ?>
    <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="post" class="form-card">
    <?php echo csrfField(); ?>
    <label for="title">Title</label>
    <input type="text" id="title" name="title" required maxlength="255"
           value="<?php echo htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <label for="content">Content</label>
    <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

    <div class="form-actions">
        <button type="submit" class="btn primary">Save post</button>
        <a class="btn" href="<?php echo htmlspecialchars(url('posts/index.php'), ENT_QUOTES, 'UTF-8'); ?>">Cancel</a>
    </div>
</form>
<?php
layoutFooter();
