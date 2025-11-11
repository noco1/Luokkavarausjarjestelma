<?php
//register.php
/*Tee HTML-lomake, jossa on:
Käyttäjänimi, sähköposti, salasana, salasanan vahvistus.
CSRF-token piilotettuna.
HTML5-validointi (required, minlength, pattern).*/

require_once '../functions.php';
$token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekisteröidy</title>
</head>
<body>
    <h1>Rekisteröidy</h1>
    <form action="register_process.php" method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token); ?>">
        <label>
            Käyttäjänimi:
            <input type="text" name="username" required minlength="3" maxlength="50" pattern="[A-Za-z0-9_]+"
            title="A-Z, a-z, 0-9 ja alaviiva sallittu">
        </label><br><br>

        <label>
            Sähköposti:
            <input type="email" name="email" required maxlength="255">
        </label><br><br>

        <label>
            Vahvista salasana:
            <input type="password" name="password_confirm" required minlength="8" maxlength="255">
        </label><br><br>

        <button type="submit">Rekisteröidy</button>
    </form>

    <p>Onko sinulla tili? <a href="login.php">Kirjaudu</a><p>
        
</body>
</html>