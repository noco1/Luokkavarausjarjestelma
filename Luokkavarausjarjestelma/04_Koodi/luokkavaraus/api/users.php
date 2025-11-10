<?php
// api/users.php
require_once __DIR__ . '/../utils.php';
$method = $_SERVER['REQUEST_METHOD'];
require_login();
if (!is_admin()) json_response(['error' => 'Forbidden'], 403);

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? null;
    $full_name = $input['full_name'] ?? null;
    $role = $input['role'] ?? 'student';
    $password = $input['password'] ?? bin2hex(random_bytes(4));
    if (!$email || !$full_name) json_response(['error' => 'Missing fields'],
400);
    $id = generate_uuid($conn);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (id, email, password_hash,
full_name, role) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $id, $email, $hash, $full_name, $role);
    if ($stmt->execute()) json_response(['status' => 'success', 'id' => $id,
'password' => $password]);
    json_response(['error' => $conn->error], 500);
}
json_response(['error' => 'Method not allowed'], 405);