<?php
require_once__DIR__ . '/config/db.php';

$sql = "SELECT id, name, capacity, location, description,
created_at FROM classes ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$classes = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fi ">
<head>
    <meta charset="utf-8">
    <title>Luokat</title>
    <style>
        table { border-collapse: collapse; width: 100%;}
        th, td { border: 1px solid #ddd,; padding: 8px; }
        th { background-color: #f4f4f4; text-align: left; }
    </style>
</head>
<body>
    <h1>Luokat</h1>
    <table>
        <thead>
            <tr>
                <th>Nimi</th>
                <th>Kapasiteetti</th>
                <th>Sijainti</th>
                <th>Kuvaus</th>
                <th>Luotu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $c): ?>
            <tr>
                <td><?php echo htmlspecialchars($c['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($c['capacity'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($c['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($c['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($c['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>    