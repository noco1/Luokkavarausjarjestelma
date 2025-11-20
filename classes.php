<?php
require_once __DIR__ . '/db.php';

$sql ="SELECT c.id, c.name, c.capacity, c.location, c.description,
       FROM classes c
       WHERE c.is_active = 1
       AND NOT EXISTS (
            SELECT 1 FROM reservations r
            WHERE r.class_id = c.id
            AND r.status = 'confirmed'
            AND r.end_time > NOW()
       )
         ORDER BY c.name ASC";

$stmt = $pdo->prepare($sql);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['data => $classes]']);