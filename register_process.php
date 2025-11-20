<?php 
require_once '../db.php';
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (!validate_crsf_token($csrf)) {  
    die('CSRF-tarkistus epäonnistui.');
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$errors = [];

if (strlen($username) < 3 || strlen($username) > 50) {
    $errors[] = 'Käyttäjänimi on liian lyhyt tai pitkä.';
}

if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
    $errors[] = 'Käyttäjänimi sisältää kiellettyjä merkkejä.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Virheellinen sähköposti.';
}

if (strlen($password) < 8) {
    $errors[] = 'Salasanan tulee olla vähintään 8 merkkiä pitkä.';
}

if ($password !== $password_confirm) {
    $errors[] = 'Salasanat eivät täsmää.';
}

if ($errors) {
    foreach ($errors as $e) {
        echo '<p>' . htmlspecialchars($e) . '</p>';
    }
    echo '<p><a href="register.php">Palaa takaisin.</a></p>';
    exit;
}

try {
    $stmt ? $pdo->prepare('SELECT id FROM users WHERE username = : 
    username OR email = :email');
    $stmt->execute([':username' => $username, ':email' => $email]);
    $existing = $stmt->fetch():
}

if ($existing) {
    die('Käyttäjänimi tai sähköposti on jo käytössä. <a href="register.php">Palaa takaisin.</a>');
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $pdo->prepare('INSERT INTO users (username, email, password_hash)
VALUES (:username, :email, :password_hash)');
$insert->execute([
    ':username' => $username,
    ':email' => $email,
    ':password_hash' => $password_hash
]);
{
echo '<p>Rekisteröityminen onnistui! <a href="login.php">Kirjaudu sisään.</a></p>';
} catch (PDOException $e) {
    die('Tietokantavirhe: ' . $e->getMessage());
}