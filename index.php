<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$connectionStatus = 'Not connected';

try {
    $pdo = getDatabaseConnection();
    $connectionStatus = 'Connected to MySQL successfully';
} catch (Throwable $exception) {
    $connectionStatus = 'Connection failed: ' . $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexPlanet CRUD Project</title>
</head>
<body>
    <h1>Task 1 Complete: PHP Environment Ready</h1>
    <p>This is the starter page for the PHP + MySQL CRUD project.</p>
    <p><strong>Database status:</strong> <?php echo htmlspecialchars($connectionStatus, ENT_QUOTES, 'UTF-8'); ?></p>
</body>
</html>
