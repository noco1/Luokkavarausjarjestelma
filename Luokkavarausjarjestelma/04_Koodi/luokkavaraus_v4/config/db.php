<?php
// config/db.php
$host = 'localhost';
$db = 'booking_db';
$user = 'db_user';
$pass = 'db_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    //heitä poikkeus virheissä
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //assosiaatiotaulukko
    PDO::ATTR_EMULATE_PREPARES   => false, //käytä oikeita prepared statementteja
];

try
{
    $pdo = new PDO($dsn, $user, $pass, $options);
}
catch (\PDOException $e)
{
    //Tarkempi virhelogi sovellukseen, älä tulosta tuotantoympäristössä
    error_log('DB connection error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Tietokantayhteydessä tapahtui virhe.';
    exit;
}