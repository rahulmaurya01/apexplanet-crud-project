<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/layout.php';

requireLogin();

$pdo = getDatabaseConnection();
$stmt = $pdo->query('SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC');
$posts = $stmt->fetchAll();

layoutHeader('Posts', 'posts');
?>
<h1>All posts</h1>
<p class="muted"><a class="btn primary inline" href="<?php echo htmlspecialchars(url('posts/create.php'), ENT_QUOTES, 'UTF-8'); ?>">Add new post</a></p>

<?php if ($posts === []) : ?>
    <p class="empty-state">No posts yet. Create your first one.</p>
<?php else : ?>
    <ul class="post-list">
        <?php foreach ($posts as $post) : ?>
            <li class="post-card">
                <h2><a href="<?php echo htmlspecialchars(url('posts/edit.php?id=' . (int) $post['id']), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></a></h2>
                <time datetime="<?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></time>
                <p class="excerpt"><?php
                    $raw = (string) $post['content'];
                    $short = strlen($raw) > 200 ? substr($raw, 0, 199) . '…' : $raw;
                    echo nl2br(htmlspecialchars($short, ENT_QUOTES, 'UTF-8'));
                ?></p>
                <div class="actions">
                    <a class="btn" href="<?php echo htmlspecialchars(url('posts/edit.php?id=' . (int) $post['id']), ENT_QUOTES, 'UTF-8'); ?>">Edit</a>
                    <form method="post" action="<?php echo htmlspecialchars(url('posts/delete.php'), ENT_QUOTES, 'UTF-8'); ?>" class="inline-form" onsubmit="return confirm('Delete this post?');">
                        <?php echo csrfField(); ?>
                        <input type="hidden" name="id" value="<?php echo (int) $post['id']; ?>">
                        <button type="submit" class="btn danger">Delete</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php
layoutFooter();
