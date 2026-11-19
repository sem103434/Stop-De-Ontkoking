<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

$fout = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    if (log_gebruiker_in($pdo, $email, $wachtwoord)) {
        header('Location: index.php');
        exit;
    }
    $fout = 'E-mailadres of wachtwoord is onjuist.';
}

$paginaTitel = 'Inloggen';
require __DIR__ . '/../includes/header.php';
?>
<section class="account-shell">
    <div class="account-photo">
        <h2>Er staat iets lekkers op je te wachten.</h2>
        <p>Log in en ontdek weer nieuwe ideeën voor aan tafel.</p>
    </div>
    <div class="account-panel">
        <span class="eyebrow">Welkom terug</span>
        <h1>Inloggen</h1>
        <p class="account-intro">Log in op je account</p>
        <?php if ($fout): ?><p class="foutmelding" role="alert"><?= h($fout) ?></p><?php endif; ?>
        <form method="post" class="account-formulier">
            <label>
                E-mailadres
                <input type="email" name="email" autocomplete="email" placeholder="jij@voorbeeld.nl" required>
            </label>
            <label>
                Wachtwoord
                <input type="password" name="wachtwoord" autocomplete="current-password" placeholder="Je wachtwoord" required>
            </label>
            <button type="submit" class="button button-dark">Inloggen <span aria-hidden="true">→</span></button>
        </form>
        <p class="account-switch">Nog geen account? <a href="registreren.php">registreer hier</a></p>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
