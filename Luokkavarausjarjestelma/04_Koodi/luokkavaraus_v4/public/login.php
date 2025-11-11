<?php
//login.php
/*Tee lomake, jossa kentät tunnukselle ja salasanalle.
Lisää CSRF-token.*/

require_once '../functions.php';
$token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirjaudu sisään</title>
</head>
<body>
    <h1>Kirjaudu</h1>
    <form action="login_process.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token); ?>">
        <label>
            Käyttäjänimi tai sähköposti:
            <input type="text" name="identifier" required>
        </label><br><br>

        <label>
            Salasana:
            <input type="text" name="identifier" required>
        </label><br><br>

        <button type="submit">Kirjaudu</button>
    </form>

    <p>Ei tiliä? <a href="register.php">Rekisteröidy</a></p>
</body>
</html>