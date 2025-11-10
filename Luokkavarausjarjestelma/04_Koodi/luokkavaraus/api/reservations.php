<?php
// api/reservations.php
require_once __DIR__ . '/../utils.php';
$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'GET') {
// optional: classroom_id to filter
$classroom = $_GET['classroom_id'] ?? null;
if ($classroom) {
$stmt = $conn->prepare('SELECT r.*, u.full_name as user_name, c.name as classroom_name FROM reservations r JOIN users u ON r.user_id = u.id JOIN classrooms c ON r.classroom_id = c.id WHERE r.classroom_id = ? ORDER BY r.start_time');
$stmt->bind_param('s', $classroom);
$stmt->execute();
$res = $stmt->get_result();
} else {
$res = $conn->query('SELECT r.*, u.full_name as user_name, c.name as classroom_name FROM reservations r JOIN users u ON r.user_id = u.id JOIN classrooms c ON r.classroom_id = c.id ORDER BY r.start_time');
}


$rows = [];
while ($r = $res->fetch_assoc()) $rows[] = $r;
json_response($rows);
}


require_login();
$user = current_user();
$input = json_decode(file_get_contents('php://input'), true);


if ($method === 'POST') {
// Create reservation
    $classroom_id = $input['classroom_id'] ?? null;
    $start_time = $input['start_time'] ?? null;
    $end_time = $input['end_time'] ?? null;
    $purpose = $input['purpose'] ?? null;
    $participants = isset($input['participants']) ?
intval($input['participants']) : null;
    if (!$classroom_id || !$start_time || !$end_time) json_response(['error'
=> 'Missing fields'], 400);
    // Check time validity
    if (strtotime($end_time) <= strtotime($start_time))
json_response(['error' => 'end_time must be after start_time'], 400);
    // Overlap check (classroom, pending/confirmed)
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM reservations WHERE
    classroom_id = ? AND status IN ('pending','confirmed') AND (start_time < ?
    AND end_time > ?)");

    $stmt->bind_param('sss', $classroom_id, $end_time, $start_time);
    $stmt->execute();
    $cnt = $stmt->get_result()->fetch_assoc()['cnt'];

    if ($cnt > 0) json_response(['error' => 'Overlapping reservation'], 409);
    $id = generate_uuid($conn);
    $created_by = $user['id'];
    $stmt = $conn->prepare('INSERT INTO reservations (id, user_id,
    classroom_id, start_time, end_time, purpose, participants, status,
    created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $status = 'pending';
    $stmt->bind_param('sssssisss', $id, $user['id'], $classroom_id,
    $start_time, $end_time, $purpose, $participants, $status, $created_by);
    
    if ($stmt->execute()) json_response(['status' => 'success', 'id' =>
    $id]);
    json_response(['error' => $conn->error], 500);
}

if ($method === 'PUT') {
    // Update reservation (only owner or teacher/admin)
    $reservation_id = $input['id'] ?? null;
    $start_time = $input['start_time'] ?? null;
    $end_time = $input['end_time'] ?? null;
    $status = $input['status'] ?? null; // optional status change
    if (!$reservation_id) json_response(['error' => 'Missing id'], 400);
    // Load reservation
    $stmt = $conn->prepare('SELECT * FROM reservations WHERE id = ? LIMIT 
1');
    $stmt->bind_param('s', $reservation_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if (!$row) json_response(['error' => 'Not found'], 404);
    // permission check
    if ($row['user_id'] !== $user['id'] && !$is_teacher_or_admin())
json_response(['error' => 'Forbidden'], 403);
    // apply changes, with overlap check if times changed
    $new_start = $start_time ?? $row['start_time'];
    $new_end = $end_time ?? $row['end_time'];
    if (strtotime($new_end) <= strtotime($new_start)) json_response(['error'
=> 'Invalid times'], 400);

    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM reservations WHERE
classroom_id = ? AND status IN ('pending','confirmed') AND (start_time < ?
AND end_time > ?) AND id <> ?");
    $stmt->bind_param('ssss', $row['classroom_id'], $new_end, $new_start,
$reservation_id);
    $stmt->execute();
    $cnt = $stmt->get_result()->fetch_assoc()['cnt'];
    if ($cnt > 0) json_response(['error' => 'Overlapping reservation'], 409);
    // update
    $updStmt = $conn->prepare('UPDATE reservations SET start_time = ?,
end_time = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
    $new_status = $status ?? $row['status'];
    $updStmt->bind_param('ssss', $new_start, $new_end, $new_status,
$reservation_id);
    if ($updStmt->execute()) json_response(['status' => 'success']);
    json_response(['error' => $conn->error], 500);
}

if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $reservation_id = $input['id'] ?? null;
    if (!$reservation_id) json_response(['error' => 'Missing id'], 400);
    $stmt = $conn->prepare('SELECT * FROM reservations WHERE id = ? LIMIT
1');
    $stmt->bind_param('s', $reservation_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) json_response(['error' => 'Not found'], 404);

    if ($row['user_id'] !== $user['id'] && !$is_teacher_or_admin())
json_response(['error' => 'Forbidden'], 403);
    $del = $conn->prepare('DELETE FROM reservations WHERE id = ?');
    $del->bind_param('s', $reservation_id);
    if ($del->execute()) json_response(['status' => 'success']);
    json_response(['error' => $conn->error], 500);
}

json_response(['error' => 'Method not allowed'], 405);