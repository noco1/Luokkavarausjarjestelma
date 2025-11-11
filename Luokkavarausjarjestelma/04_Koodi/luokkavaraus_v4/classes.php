<?php
//classes.php
require_once __DIR__ . '/config/db.php';

//Hae kaikki luokat
$sql = "SELECT id, name, capacity, location, description, created_at FROM classes ORDER BY name ASC";
$stmt = $pdo->query($sql);
$classes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luokat</title>
    <style>
        table
        {
            border-collapse: collapse; width: 100%;
        }
        th, td
        {
            border: 1px solid #ddd; padding: 8px;
        }
        th
        {
            background: #f4f4f4; text-align: left;
        }
    </style>
</head>
<body>
    <h1>Luokat</h1>

    <?php if (empty($classes)): ?>
        <p>Ei luokkia tietokannassa.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nimi</th>
                    <th>kapasiteetti</th>
                    <th>Sijainti</th>
                    <th>Kuvaus<th>
                    <th>lisätty<th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($c['capacity'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($c['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($c['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')); ?></td>
                        <td><?php echo htmlspecialchars($c['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>