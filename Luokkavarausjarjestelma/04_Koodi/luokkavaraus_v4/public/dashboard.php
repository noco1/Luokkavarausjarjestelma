<?php
//dashboard.php
/*Näytä sisältö vain kirjautuneille käyttäjille.
Käytä require_login().*/

require_once '../functions.php';
require_login();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hallintapaneeli</title>
</head>
<body>
    <h1>Tervetuloa<h1>
    <p>Tämä sivu vaatii kirjautumisen.</p>
    <p><a href="logout.php">Kirjaudu ulos</a></p>
</body>
</html>