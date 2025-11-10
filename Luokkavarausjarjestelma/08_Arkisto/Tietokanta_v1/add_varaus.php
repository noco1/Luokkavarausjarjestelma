<?php
include 'db.php';
$user = $_SESSION['kayttaja'] ?? null;

if(!$user)
{
    http_response_code(401);
    exit("Kirjaudu ensin sisään.");
}

$data = json_decode(file_get_contents("php://input"), true);
$kayttaja_id = $user['kayttaja_id'];
$luokka_id = $data["luokka_id"];
$tunti_id = $data["tunti_id"];
$aloitusaika = $data["aloitusaika"];
$lopetusaika = $data["lopetusaika"];

$stmt = $conn->prepare("INSERT INTO varaukset (kayttaja_id, luokka_id, tunti_id, aloitusaika, lopetusaika)
                        VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiss", $kayttaja_id, $luokka_id)