<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luokkavarausjarjestelma";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error)
{
    die("Yhteys epäonnistui: " . $conn->connect_error);
}
?>