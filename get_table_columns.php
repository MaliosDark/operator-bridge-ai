<?php
require_once __DIR__ . '/common.php';


// Protect with key
log_action(__FILE__, ['action'=>'get_full_schema']);

// 1) Fetch all table names in the current database
$stmt = $pdo->prepare("
  SELECT TABLE_NAME
  FROM information_schema.TABLES
  WHERE TABLE_SCHEMA = :db
  ORDER BY TABLE_NAME
");
$stmt->execute([':db'=>DB_NAME]);
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 2) For each table, fetch its columns
$schema = [];
foreach ($tables as $table) {
    $colStmt = $pdo->prepare("
      SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table
      ORDER BY ORDINAL_POSITION
    ");
    $colStmt->execute([':db'=>DB_NAME, ':table'=>$table]);
    $schema[$table] = $colStmt->fetchAll(PDO::FETCH_ASSOC);
}

// 3) Return full schema
header('Content-Type: application/json');
echo json_encode([
    'database' => DB_NAME,
    'schema'   => $schema
], JSON_PRETTY_PRINT);
