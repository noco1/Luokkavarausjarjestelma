<?php
//register_process.php
/*Tarkistaa CSRF-token.
Validoi syötteet (pituus, sähköpostin muoto, salasanat täsmäävät).
Hashaa salasana turvallisesti.
Tallenna käyttäjä tietokantaan.*/

require_once '../db.php';
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header('Location: register.php');
    exit;
}

//CSRF

$csrf = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf))
{
    die('CSRF-tarkistus epäonnistui.');
}

//Syötteiden perusvalidointi
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$password_confirm = trim($_POST['password_confirm'] ?? '');

$errors = [];

if (strlen($username) < 3 || strlen($username) > 50)
{
    $errors[] = 'Käyttäjänimi on liian lyhyt tai pitkä.';
}
if (!preg_match('/^[A-Za-z0-9_]+$/', $username))
{
    $errors[] = 'Käyttäjänimi sisältää kiellettyjä merkkejä.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    $errors[] = 'Virheellinen sähköpostiosoite.';
}
if (strlen($password) < 8)
{
    $errors[] = 'Salasana tulee olla vähintään 8 merkkiä pitkä.';
}
if ($password !== $password_confirm)
{
    $errors[] = 'Salasanat eivät täsmää.';
}
if ($errors)
{
    foreach ($errors as $e)
    {
        echo '<p><a href="register.php">Palaa takaisin</a></p>';
        exit;
    }
}

//Tarkista, onko käyttäjänimi tai sähköposti jo käytössä
try 
{
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);
    $existing = $stmt->fetch();

    if($existing)
    {
        die('Käyttäjänimi tai sähköposti on jo käytössä. <a href="register.php">Takaisin</a>');
    }

    //Tallenna uusi käyttäjä
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $insert = $pdo->prepare('INSERT INTO user (username, email, :password_hash)');
    $insert->execute([
        'username' => $username,
        'email' => $email,
        'password_hash' => $password_hash
    ]);

    echo '<p>Rekisteröinti onnistui. <a href="login.php">Kirjaudu sisään</a></p>'; 
}
catch (PDOException $e)
{
    //Kehitysvaiheessa virheen tulostus voi auttaa tuotannossa lokita
    die('Tietokantavirhe: ' . $e->getMessage());
}