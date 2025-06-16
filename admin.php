<?php
require_once 'config.php';

// Read log file
$lines = array_slice(file(__DIR__ . '/access.log', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES), -100);
$entries = array_map(fn($line) => json_decode($line, true), $lines);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Bridge Admin Dashboard</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; border: 1px solid #ccc; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h1>AI Bridge Admin</h1>
    <p>System Time: <?php echo date('Y-m-d H:i:s'); ?></p>
    <h2>Recent Logs (last <?php echo count($entries); ?>)</h2>
    <table>
        <thead><tr><th>Time</th><th>Action</th><th>Details</th><th>IP</th></tr></thead>
        <tbody>
        <?php foreach ($entries as $e): ?>
            <tr>
                <td><?php echo htmlspecialchars($e['timestamp']); ?></td>
                <td><?php echo htmlspecialchars($e['action']); ?></td>
                <td><pre><?php echo htmlspecialchars(json_encode($e['details'])); ?></pre></td>
                <td><?php echo htmlspecialchars($e['ip']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
