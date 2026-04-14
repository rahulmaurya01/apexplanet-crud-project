<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/search.php';
require_once __DIR__ . '/../includes/layout.php';

requireLogin();

const POSTS_PER_PAGE = 5;

$searchRaw = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
if (strlen($searchRaw) > 200) {
    $searchRaw = substr($searchRaw, 0, 200);
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$pdo = getDatabaseConnection();
$hasSearch = $searchRaw !== '';

if ($hasSearch) {
    $like = '%' . escapeLikePattern($searchRaw) . '%';
    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM posts WHERE title LIKE :q OR content LIKE :q2');
    $countStmt->execute(['q' => $like, 'q2' => $like]);
} else {
    $countStmt = $pdo->query('SELECT COUNT(*) FROM posts');
}
$totalPosts = (int) $countStmt->fetchColumn();

$totalPages = max(1, (int) ceil($totalPosts / POSTS_PER_PAGE));
if ($page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * POSTS_PER_PAGE;

if ($hasSearch) {
    $like = '%' . escapeLikePattern($searchRaw) . '%';
    $stmt = $pdo->prepare(
        'SELECT id, title, content, created_at FROM posts
         WHERE title LIKE :q OR content LIKE :q2
         ORDER BY created_at DESC
         LIMIT :lim OFFSET :off'
    );
    $stmt->bindValue(':q', $like, PDO::PARAM_STR);
    $stmt->bindValue(':q2', $like, PDO::PARAM_STR);
    $stmt->bindValue(':lim', POSTS_PER_PAGE, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare(
        'SELECT id, title, content, created_at FROM posts
         ORDER BY created_at DESC
         LIMIT :lim OFFSET :off'
    );
    $stmt->bindValue(':lim', POSTS_PER_PAGE, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$posts = $stmt->fetchAll();

/**
 * @param array<string, string|int> $extra
 */
function postsListUrl(array $extra = []): string
{
    $base = url('posts/index.php');
    $query = array_merge($_GET, $extra);
    $filtered = array_filter(
        $query,
        static fn ($v) => $v !== null && $v !== ''
    );
    $qs = http_build_query($filtered);

    return $qs === '' ? $base : $base . '?' . $qs;
}

layoutHeader('Posts', 'posts');
?>
<div class="page-head">
    <h1>All posts</h1>
    <a class="btn primary" href="<?php echo htmlspecialchars(url('posts/create.php'), ENT_QUOTES, 'UTF-8'); ?>">Add new post</a>
</div>

<form class="search-bar" method="get" action="<?php echo htmlspecialchars(url('posts/index.php'), ENT_QUOTES, 'UTF-8'); ?>">
    <label class="sr-only" for="q">Search posts</label>
    <input type="search" id="q" name="q" placeholder="Search by title or content…" maxlength="200"
           value="<?php echo htmlspecialchars($searchRaw, ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit" class="btn primary">Search</button>
    <?php if ($hasSearch) : ?>
        <a class="btn" href="<?php echo htmlspecialchars(url('posts/index.php'), ENT_QUOTES, 'UTF-8'); ?>">Clear</a>
    <?php endif; ?>
</form>

<p class="page-meta">
    <?php if ($totalPosts === 0) : ?>
        No posts found<?php echo $hasSearch ? ' for your search.' : '.'; ?>
    <?php else : ?>
        Showing <?php echo (int) ($offset + 1); ?>–<?php echo (int) min($offset + POSTS_PER_PAGE, $totalPosts); ?>
        of <?php echo $totalPosts; ?> post<?php echo $totalPosts === 1 ? '' : 's'; ?>
        <?php if ($hasSearch) : ?>
            matching “<?php echo htmlspecialchars($searchRaw, ENT_QUOTES, 'UTF-8'); ?>”
        <?php endif; ?>
        · Page <?php echo $page; ?> of <?php echo $totalPages; ?>
    <?php endif; ?>
</p>

<?php if ($posts === []) : ?>
    <p class="empty-state"><?php echo $hasSearch ? 'Try different keywords or clear the search.' : 'No posts yet. Create your first one.'; ?></p>
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

    <?php if ($totalPages > 1) : ?>
        <nav class="pagination" aria-label="Post pages">
            <?php if ($page > 1) : ?>
                <a class="btn" href="<?php echo htmlspecialchars(postsListUrl(['page' => $page - 1]), ENT_QUOTES, 'UTF-8'); ?>">← Previous</a>
            <?php else : ?>
                <span class="btn disabled" aria-disabled="true">← Previous</span>
            <?php endif; ?>

            <div class="page-numbers">
                <?php
                $window = 2;
                $start = max(1, $page - $window);
                $end = min($totalPages, $page + $window);
                if ($start > 1) {
                    echo '<a class="page-link" href="' . htmlspecialchars(postsListUrl(['page' => 1]), ENT_QUOTES, 'UTF-8') . '">1</a>';
                    if ($start > 2) {
                        echo '<span class="ellipsis">…</span>';
                    }
                }
                for ($i = $start; $i <= $end; $i++) {
                    if ($i === $page) {
                        echo '<span class="page-link current" aria-current="page">' . $i . '</span>';
                    } else {
                        echo '<a class="page-link" href="' . htmlspecialchars(postsListUrl(['page' => $i]), ENT_QUOTES, 'UTF-8') . '">' . $i . '</a>';
                    }
                }
                if ($end < $totalPages) {
                    if ($end < $totalPages - 1) {
                        echo '<span class="ellipsis">…</span>';
                    }
                    echo '<a class="page-link" href="' . htmlspecialchars(postsListUrl(['page' => $totalPages]), ENT_QUOTES, 'UTF-8') . '">' . $totalPages . '</a>';
                }
                ?>
            </div>

            <?php if ($page < $totalPages) : ?>
                <a class="btn" href="<?php echo htmlspecialchars(postsListUrl(['page' => $page + 1]), ENT_QUOTES, 'UTF-8'); ?>">Next →</a>
            <?php else : ?>
                <span class="btn disabled" aria-disabled="true">Next →</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
<?php
layoutFooter();
