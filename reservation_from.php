<?php
require_once 'classes.php';
session_start();


if (empty($_SESSION['user_id'])) {
header('Location: login.php');
exit;
}

$db = new DB('127.0.0.1', 'varausdb', 'dbuser', 'dbpass');
$pdo = $db->pdo();
$classModel = new ClassModel($pdo);
$resModel = new ReservationModel($pdo);


$errors = [];