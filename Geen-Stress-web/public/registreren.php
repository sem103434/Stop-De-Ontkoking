<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

$fout = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = trim($_POST['naam'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if ($naam === '' || $email === '' || strlen($wachtwoord) < 8) {
        $fout = 'Vul alle velden in. Het wachtwoord moet minimaal 8 tekens bevatten.';
    } else {
        [$gelukt, $foutmelding] = registreer_gebruiker($pdo, $naam, $email, $wachtwoord);
        if ($gelukt) {
            log_gebruiker_in($pdo, $email, $wachtwoord);
            header('Location: index.php');
            exit;
        }
        $fout = $foutmelding;
    }
}

$paginaTitel = 'Registreren';
require __DIR__ . '/../includes/header.php';
?>
<section class="account-shell">
    <div class="account-photo">
        <span class="eyebrow">Koken brengt samen</span>
        <h2>Jouw nieuwe favoriete plek voor eten.</h2>
        <p>Bewaar inspiratie, ontdek gerechten en deel de recepten waar jij trots op bent.</p>
    </div>
    <div class="account-panel">
        <span class="eyebrow">Schuif gezellig aan</span>
        <h1>Maak je account</h1>
        <p class="account-intro">Het is gratis. En er valt een hoop lekkers te ontdekken.</p>
        <?php if ($fout): ?><p class="foutmelding" role="alert"><?= h($fout) ?></p><?php endif; ?>
        <form method="post" class="account-formulier">
            <label>
                Jouw naam
                <input type="text" name="naam" autocomplete="name" placeholder="Bijvoorbeeld Sam" required>
            </label>
            <label>
                E-mailadres
                <input type="email" name="email" autocomplete="email" placeholder="jij@voorbeeld.nl" required>
            </label>
            <label>
                Wachtwoord
                <input type="password" name="wachtwoord" autocomplete="new-password" placeholder="Minimaal 8 tekens" required minlength="8">
            </label>
            <button type="submit" class="button button-dark">Account aanmaken <span aria-hidden="true">→</span></button>
        </form>
        <p class="account-switch">Heb je al een account? <a href="login.php">Log hier in</a></p>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
