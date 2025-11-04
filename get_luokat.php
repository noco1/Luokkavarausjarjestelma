<?php
include 'db.php';
$result = $conn->query("SELECT * FROM luokat");
$luokat = [];
while ($row = $result->fetch_assoc())
{
    $luokat[] = $row;
}
echo json_encode($luokat);
?>