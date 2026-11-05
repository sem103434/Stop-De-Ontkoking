<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fffaf2">
    <title><?= isset($paginaTitel) ? h($paginaTitel) . ' | ' : '' ?>Geen Stress</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <a href="index.php" class="logo" aria-label="Geen Stress home">
        <img class="brand-logo" src="images/geen-stress-logo.png" alt="Geen Stress — lekker koken, zonder gedoe">
    </a>
    <nav class="main-nav" aria-label="Hoofdnavigatie">
        <a href="overzicht.php">Recepten</a>
        <a href="overzicht.php">Ontdek</a>
        <?php if (is_ingelogd()): ?>
            <a href="invoer.php">Recept delen</a>
            <?php if (is_admin()): ?>
                <a href="admin.php">Beheer</a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
    <div class="nav-actions">
        <?php if (is_ingelogd()): ?>
            <span class="welcome-user">Hoi, <?= h($_SESSION['naam']) ?></span>
            <a class="nav-login" href="logout.php">Uitloggen</a>
        <?php else: ?>
            <a class="nav-login" href="login.php">Inloggen</a>
            <a class="button button-dark button-small" href="registreren.php">Account maken <span aria-hidden="true">↗</span></a>
        <?php endif; ?>
    </div>
</header>
<main class="container">
