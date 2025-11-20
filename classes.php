<?php
class ClassModel {
private $pdo;
public function __construct(PDO $pdo) { $this->pdo = $pdo; }


public function listUpcoming() {
$stmt = $this->pdo->query("SELECT c.*,
(c.capacity - COALESCE(SUM(r.seats),0)) AS seats_left
FROM classes c
LEFT JOIN reservations r ON r.class_id = c.id AND r.status = 'confirmed'
WHERE c.start_time >= NOW()
GROUP BY c.id
ORDER BY c.start_time ASC");
return $stmt->fetchAll();
}


public function find($id) {
$stmt = $this->pdo->prepare("SELECT * FROM classes WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
return $stmt->fetch();
}
}


class ReservationModel {
private $pdo;
public function __construct(PDO $pdo) { $this->pdo = $pdo; }


public function create($userId, $classId, $seats) {
$this->pdo->beginTransaction();
try {
$stmt = $this->pdo->prepare("SELECT capacity - COALESCE(SUM(r.seats),0) AS seats_left
FROM classes c
LEFT JOIN reservations r ON r.class_id = c.id AND r.status = 'confirmed'
WHERE c.id = ?
GROUP BY c.id
FOR UPDATE");
$stmt->execute([$classId]);
$row = $stmt->fetch();
$left = $row ? (int)$row['seats_left'] : 0;
if ($left < $seats) {
$this->pdo->rollBack();
return [ 'success' => false, 'message' => 'Ei tarpeeksi vapaita paikkoja.' ];
}


$ins = $this->pdo->prepare("INSERT INTO reservations (user_id, class_id, seats) VALUES (?, ?, ?)");
$ins->execute([$userId, $classId, $seats]);
$this->pdo->commit();
return [ 'success' => true, 'reservation_id' => $this->pdo->lastInsertId() ];
} catch (Exception $e) {
$this->pdo->rollBack();
return [ 'success' => false, 'message' => $e->getMessage() ];
}
}


public function listByUser($userId) {
$stmt = $this->pdo->prepare("SELECT r.*, c.title, c.start_time FROM reservations r
JOIN classes c ON c.id = r.class_id
WHERE r.user_id = ? ORDER BY r.reserved_at DESC");
$stmt->execute([$userId]);
return $stmt->fetchAll();
}
}