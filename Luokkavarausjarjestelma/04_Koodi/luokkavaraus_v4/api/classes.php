<?php
//api/classes.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query("SELECT id, name, capacity, location FROM classes ORDER BY name ASC");
$classes = $stmt->fetchAll();

echo json_encode($classes, JSON_UNESCAPED_UNICODE);