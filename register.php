<?php 
require_once '../functions.php';
$token = generate_csrf_token();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Rekisteröidy</title></head>
<body>
<h1>Rekisteröidy</h1>
<from action='register_process.php' method='post' novalidate>
<input type='hidden' name='csrf_token' value='<?=htmlspecialchars($token)?>'>
<label>
    Käyttäjänimi:
    <input type="text" name="username" required
    minlength="3" maxlength="50" pattern="[A-Za-z0-9_]+"
    title="A-Z, a-z, 0-9 ja alaviiva sallittu">
</label><br>

<label>
    Sähköposti:
    <input type="email" name="email" required
    maxlengt="255">
</label><br>

<label>
    Vahvista Salasana:
    <input type="password" name="password_confirm" required
    minlength="8">
</label><br>

<button type="submit">Rekisteröidy</button>
</from>

<p>Onko sinulla tili? <a href='login.php'>Kirjaudu</a></p>
</body>
</html>