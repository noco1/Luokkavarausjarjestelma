<?PHP
$host = "localhost";
$user = "root";
$password = "";
$dbname = "luokkavarausjarjestelma";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error)
{
    die("Tietokantayhteys epäonnistui: " . $conn->connect_error;)
}
?>