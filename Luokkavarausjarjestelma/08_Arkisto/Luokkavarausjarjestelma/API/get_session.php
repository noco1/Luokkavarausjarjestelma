<?php
require_once __DIR__ . '/../db.php';
json_response($_SESSION['user'] ?? null);