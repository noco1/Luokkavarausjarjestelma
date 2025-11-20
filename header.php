<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Luokkavaraus'; ?></title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <header><h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Luokat'; ?></h1></header>
    <main>