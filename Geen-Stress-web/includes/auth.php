<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_ingelogd(): bool
{
    return isset($_SESSION['gebruiker_id']);
}

function is_admin(): bool
{
    return is_ingelogd() && ($_SESSION['rol'] ?? '') === 'admin';
}

function vereist_login(): void
{
    if (!is_ingelogd()) {
        header('Location: login.php');
        exit;
    }
}

function vereist_admin(): void
{
    if (!is_admin()) {
        http_response_code(403);
        die('Geen toegang. Deze pagina is alleen voor beheerders.');
    }
}

function registreer_gebruiker(PDO $pdo, string $naam, string $email, string $wachtwoord): array
{
    $stmt = $pdo->prepare('SELECT id FROM gebruikers WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return [false, 'Dit e-mailadres is al in gebruik.'];
    }

    $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO gebruikers (naam, email, wachtwoord_hash) VALUES (?, ?, ?)');
    $stmt->execute([$naam, $email, $hash]);
    return [true, null];
}

function log_gebruiker_in(PDO $pdo, string $email, string $wachtwoord): bool
{
    $stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE email = ?');
    $stmt->execute([$email]);
    $gebruiker = $stmt->fetch();

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord_hash'])) {
        $_SESSION['gebruiker_id'] = $gebruiker['id'];
        $_SESSION['naam'] = $gebruiker['naam'];
        $_SESSION['rol'] = $gebruiker['rol'];
        return true;
    }
    return false;
}
