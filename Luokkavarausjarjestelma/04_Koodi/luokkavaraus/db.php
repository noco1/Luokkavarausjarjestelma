<?php
// db.php
session_start();
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'luokkavarausjarjestelma';


$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
http_response_code(500);
die(json_encode(['error' => 'DB connection failed']));
}
$conn->set_charset('utf8mb4');


function json_response($data, $code = 200) {
http_response_code($code);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
exit;
}