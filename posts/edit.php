<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/layout.php';

requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    http_response_code(404);
    exit('Post not found.');
}

$pdo = getDatabaseConnection();
$stmt = $pdo->prepare('SELECT id, title, content FROM posts WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$post = $stmt->fetch();

if ($post === false) {
    http_response_code(404);
    exit('Post not found.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();
    $title = trim((string) ($_POST['title'] ?? ''));
    $content = trim((string) ($_POST['content'] ?? ''));

    $error = validatePostTitle($title) ?? validatePostContent($content);
    if ($error !== null) {
        // Error already set by validators.
    } else {
        $upd = $pdo->prepare('UPDATE posts SET title = :t, content = :c WHERE id = :id');
        $upd->execute(['t' => $title, 'c' => $content, 'id' => $id]);
        $_SESSION['flash'] = 'Post updated.';
        header('Location: ' . url('posts/index.php'));
        exit;
    }
} else {
    $_POST['title'] = $post['title'];
    $_POST['content'] = $post['content'];
}

layoutHeader('Edit post', 'posts');
?>
<h1>Edit post</h1>
<?php if ($error !== '') : ?>
    <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="post" class="form-card">
    <?php echo csrfField(); ?>
    <label for="title">Title</label>
    <input type="text" id="title" name="title" required maxlength="255"
           value="<?php echo htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <label for="content">Content</label>
    <textarea id="content" name="content" rows="10" required minlength="10" maxlength="5000"><?php echo htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

    <div class="form-actions">
        <button type="submit" class="btn primary">Update post</button>
        <a class="btn" href="<?php echo htmlspecialchars(url('posts/index.php'), ENT_QUOTES, 'UTF-8'); ?>">Back to list</a>
    </div>
</form>
<?php
layoutFooter();
