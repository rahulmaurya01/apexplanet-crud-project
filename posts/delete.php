<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/csrf.php';

requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('posts/index.php'));
    exit;
}

validateCsrf();

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id < 1) {
    header('Location: ' . url('posts/index.php'));
    exit;
}

$pdo = getDatabaseConnection();
$stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
$stmt->execute(['id' => $id]);

$_SESSION['flash'] = 'Post deleted.';
header('Location: ' . url('posts/index.php'));
exit;
