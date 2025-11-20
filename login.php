<?php
require_once 'classes.php';
session_start();
$db = new DB('127.0.0.1', 'varausdb', 'dbuser', 'dbpass');
$pdo = $db->pdo();
$userModel = new UserModel($pdo);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$errors = [];


$user = $userModel->findByEmail($email);
if (!$user || !password_verify($password, $user['password_hash'])) {
$errors[] = 'Virheellinen sähköposti tai salasana.';
} else {
$_SESSION['user_id'] = $user['id'];
header('Location: index.php');
exit;
}
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Kirjaudu</title></head>
<body>
<h1>Kirjaudu</h1>
<?php if (!empty($errors)): ?>
<ul>
<?php foreach ($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
</ul>
<?php endif; ?>
<form method="post">
<label>Sähköposti<br><input name="email" value="<?=htmlspecialchars($email ?? '')?>"></label><br>
<label>Salasana<br><input type="password" name="password"></label><br>
<button type="submit">Kirjaudu</button>
</form>
</body>
</html>