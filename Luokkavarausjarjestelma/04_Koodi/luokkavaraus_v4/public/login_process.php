<?php
//login_process.php
/*Tarkista CSRF-token.
Hae käyttäjä tietokannasta.
Vertaile salasanaa password_verify-funktiolla.
Tallenna sessioon käyttäjän id.*/

require_once '../db.php';
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header('Location: login.php');
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf))
{
    die('CSRF-tarkistus epäonnistui.');
}

$identifier = trim($_POST['identifier'] ?? '');
$password = $_POST['password'] ?? '';

if ($identifier === '' || $password === '')
{
    die('Täytä kaikki kentät. <a href="login.php">Takaisin</a>');
}

try
{
    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = :id OR email = :id');
    $stmt->execute(['id' => $identifier]);
    $user = $stmt->fetch();

    if (!$user)
    {
        die('Käyttäjää ei löydy. <a href="login.php">Takaisin</a>');
    }

    if (!password_verify($password, $user['password_hash']))
    {
        die('Virheellinen salasana. <a href="login.php">Takaisin</a>');
    }

    //kirjautuminen onnistui
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];

    header('Location: dashboard.php');
    exit;
}
catch (PDOException $e)
{
    die('Tietokantavirhe: ' . $e->getMessage());
}
?>