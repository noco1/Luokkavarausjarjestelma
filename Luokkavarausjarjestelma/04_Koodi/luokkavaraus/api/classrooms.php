<?php
// api/classrooms.php
require_once __DIR__ . '/../utils.php';


$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
$res = $conn->query('SELECT * FROM classrooms WHERE is_active = 1 ORDER BY name');
$rows = [];
while ($r = $res->fetch_assoc()) $rows[] = $r;
json_response($rows);
}


require_login();
if (!is_admin()) json_response(['error' => 'Forbidden'], 403);


if ($method === 'POST') {
$data = json_decode(file_get_contents('php://input'), true);
$id = generate_uuid($conn);
$name = $data['name'] ?? '';
$location = $data['location'] ?? null;
$capacity = intval($data['capacity'] ?? 0);
$resources = isset($data['resources']) ? json_encode($data['resources']) : json_encode(new stdClass());


$stmt = $conn->prepare('INSERT INTO classrooms (id, name, location, capacity, resources) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sssis', $id, $name, $location, $capacity, $resources);
if ($stmt->execute()) json_response(['status' => 'success', 'id' => $id]);
json_response(['status' => 'error', 'msg' => $conn->error], 500);
}


json_response(['error' => 'Method not allowed'], 405);