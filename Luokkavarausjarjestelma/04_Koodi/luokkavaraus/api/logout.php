<?php
require_once __DIR__ . '/../db.php';
session_destroy();
json_response(['status' => 'success']);