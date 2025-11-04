<?php
include 'db.php';

$sql = "SELECT v. varaus_id, k.nimi AS kayttaja, l.nimi AS luokka, t.nimi AS tunti,
               v.aloitusaika, v.lopetusaika
               FROM varaukset v
               JOIN kayttajat k ON v.kayttaja_id = k.kayttaja_id
               JOIN luokat l ON v.luokka_id = l.luokka_id
               JOIN tunnit t ON v.tunti_id = t.tunti_id
               ORDER BY v.aloitusaika ASC";
$result = $conn->query($sql);
$varaukset = [];

while ($row = $result->fetch_assoc())
{
    $varaukset[] = $row;
}
echo json_encode($varaukset);
?>