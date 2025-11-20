<?php
require_once 'classes.php';
session_start();

$db = new DB('127.0.0.1', 'varausdb', 'dbuser', 'dbpass');
$pdo = $db->pdo();
$userModel = new UserModel($pdo);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


$errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Virheellinen sähköposti.';
if (strlen($password) < 6) $errors[] = 'Salasanan tulee olla vähintään 6 merkkiä.';
if ($name === '') $errors[] = 'Nimi vaaditaan.';


if (empty($errors)) {
if ($userModel->findByEmail($email)) {
$errors[] = 'Sähköposti on jo käytössä.';
} else {
$hash = password_hash($password, PASSWORD_DEFAULT);
$userId = $userModel->create($name, $email, $hash);
$_SESSION['user_id'] = $userId;
header('Location: index.php');
exit;
}
}
}

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Rekisteröidy</title></head>
<body>
<h1>Rekisteröidy</h1>
<?php if (!empty($errors)): ?>
<ul>
<?php foreach ($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
</ul>
<?php endif; ?>
<form method="post">
<label>Nimi<br><input name="name" value="<?=htmlspecialchars($name ?? '')?>"></label><br>
<label>Sähköposti<br><input name="email" value="<?=htmlspecialchars($email ?? '')?>"></label><br>
<label>Salasana<br><input type="password" name="password"></label><br>
<button type="submit">Rekisteröidy</button>
</form>
</body>
</html>