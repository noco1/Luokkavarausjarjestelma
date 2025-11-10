<?php
// api/login.php
require_once __DIR__ . '/../db.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$pass = $input['password'] ?? '';


if (!$email || !$pass) {
json_response(['status' => 'error', 'message' => 'Missing credentials'], 400);
}


$stmt = $conn->prepare('SELECT id, email, password_hash, full_name, role, is_active FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
if (!$user) json_response(['status' => 'error', 'message' => 'Invalid credentials'], 401);
if (!$user['is_active']) json_response(['status' => 'error', 'message' => 'User disabled'], 403);


// Note: seed data used MD5 for passwords in SQL; production: use password_hash
$valid = false;
if (password_verify($pass, $user['password_hash'])) $valid = true;
// fallback for MD5 seeded passwords (only for initial demo)
if (!$valid && md5($pass) === $user['password_hash']) $valid = true;


if (!$valid) json_response(['status' => 'error', 'message' => 'Invalid credentials'], 401);


// remove password_hash before storing in session
unset($user['password_hash']);
$_SESSION['user'] = $user;
json_response(['status' => 'success', 'user' => $user]);