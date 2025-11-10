<?php
// utils.php
require_once __DIR__ . '/db.php';


function generate_uuid($conn) {
$res = $conn->query("SELECT UUID()");
$row = $res->fetch_row();
return $row[0];
}


function require_login() {
if (empty($_SESSION['user'])) {
json_response(['error' => 'Not authenticated'], 401);
}
}


function current_user() {
return $_SESSION['user'] ?? null;
}


function is_admin() {
$u = current_user();
return $u && $u['role'] === 'admin';
}


function is_teacher_or_admin() {
$u = current_user();
return $u && ($u['role'] === 'teacher' || $u['role'] === 'admin');
}