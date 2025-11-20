<?php
require_once '../functions.php';
$token = generate_csrf_token();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kirjaudu sisään</title>
</head>
<body>
<h1>Kirjaudu</h1>
<form action='login_process.php' method='post'>
    <input type='hidden' name="csrf_token" value="<?php echo htmlspecialchars($token); ?>">
    <label>
        Käyttäjänimi tai Sähköposti:
        <input type="text" name="identifier" required>
    </label><br><br>

    <label>
        Salasana:
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Kirjaudu</button>
</form>
<p>Ei vielä tiliä? <a href='register.php'>Rekisteröidy</a></p>
</body>
</html>