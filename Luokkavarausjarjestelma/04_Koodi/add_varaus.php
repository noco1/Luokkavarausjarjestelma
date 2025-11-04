<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$kayttaja_id = $data["kayttaja_id"];
$luokka_id = $data["luokka_id"];
$tunti_id = $data["tunti_id"];
$aloitusaika = $data["aloitusaika"];
$lopetusaika = $data["lopetusaika"];

$sql = "INSERT INTO varaukset (kayttaja_id, luokka_id, tunti_id, aloitusaika, lopetusaika) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiss", $kayttaja_id, $luokka_id, $tunti_id, $aloitusaika, $lopetusaika);

if ($stmt->execute())
{
    echo json_encode["status" => "success", "message" => "Varaus lisätty onnistuneesti"];
}
else
{
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
