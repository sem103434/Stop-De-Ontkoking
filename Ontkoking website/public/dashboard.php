<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';
vereist_login();

$gebruikerId = (int) $_SESSION['gebruiker_id'];
$fout = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    controleer_csrf();
    $actie = $_POST['actie'] ?? '';

    if ($actie === 'verwijder_recept') {
        $stmt = $pdo->prepare('DELETE FROM recepten WHERE id = ? AND gebruiker_id = ?');
        $stmt->execute([(int) ($_POST['recept_id'] ?? 0), $gebruikerId]);
        zet_melding('Het recept is verwijderd.');
        header('Location: dashboard.php');
        exit;
    }

    if ($actie === 'naam') {
        $naam = trim($_POST['naam'] ?? '');
        if ($naam === '' || mb_strlen($naam) > 100) {
            $fout = 'Vul een naam in van maximaal 100 tekens.';
        } else {
            $pdo->prepare('UPDATE gebruikers SET naam = ? WHERE id = ?')->execute([$naam, $gebruikerId]);
            $_SESSION['naam'] = $naam;
            zet_melding('Je naam is bijgewerkt.');
            header('Location: dashboard.php');
            exit;
        }
    }

    if ($actie === 'wachtwoord') {
        $stmt = $pdo->prepare('SELECT wachtwoord_hash FROM gebruikers WHERE id = ?');
        $stmt->execute([$gebruikerId]);
        $hash = $stmt->fetchColumn();
        $nieuw = $_POST['nieuw_wachtwoord'] ?? '';
        if (!$hash || !password_verify($_POST['huidig_wachtwoord'] ?? '', $hash)) {
            $fout = 'Je huidige wachtwoord klopt niet.';
        } elseif (strlen($nieuw) < 8) {
            $fout = 'Je nieuwe wachtwoord moet minimaal 8 tekens bevatten.';
        } else {
            $pdo->prepare('UPDATE gebruikers SET wachtwoord_hash = ? WHERE id = ?')
                ->execute([password_hash($nieuw, PASSWORD_DEFAULT), $gebruikerId]);
            zet_melding('Je wachtwoord is gewijzigd.');
            header('Location: dashboard.php');
            exit;
        }
    }
}

$paginaTitel = 'Mijn dashboard';
$recepten = haal_recepten_van_gebruiker($pdo, $gebruikerId);
require __DIR__ . '/../includes/header.php';
?>
<div class="page-heading">
    <span class="eyebrow">Jouw keuken</span>
    <h1>Hoi <?= h($_SESSION['naam']) ?>!</h1>
    <p>Hier beheer je je eigen recepten en je account.</p>
</div>
<?php if ($fout): ?><p class="foutmelding" role="alert"><?= h($fout) ?></p><?php endif; ?>

<div class="section-heading">
    <div><h2>Mijn recepten (<?= count($recepten) ?>)</h2></div>
    <a class="knop knop-primair" href="invoer.php">+ Nieuw recept</a>
</div>

<?php if (!$recepten): ?>
    <div class="lege-staat">
        <h3>Je hebt nog geen recepten</h3>
        <p>Deel het gerecht waar jij altijd om gevraagd wordt. Een beheerder bekijkt het snel, daarna staat het online.</p>
        <a class="knop knop-primair" href="invoer.php">Deel je eerste recept</a>
    </div>
<?php else: ?>
    <div class="tabel-scroll">
    <table class="beheer-tabel">
        <tr><th>Recept</th><th>Categorie</th><th>Status</th><th>Weergaven</th><th></th></tr>
        <?php foreach ($recepten as $r): ?>
            <tr>
                <td><a href="detail.php?id=<?= (int) $r['id'] ?>"><?= h($r['titel']) ?></a></td>
                <td><?= h($r['categorie_naam']) ?></td>
                <td><span class="status-badge status-<?= h($r['status']) ?>"><?= h(STATUS_LABELS[$r['status']]) ?></span></td>
                <td><?= (int) $r['weergaven'] ?></td>
                <td>
                    <form method="post" class="actie-rij">
                        <?= csrf_veld() ?>
                        <input type="hidden" name="actie" value="verwijder_recept">
                        <input type="hidden" name="recept_id" value="<?= (int) $r['id'] ?>">
                        <a class="knop" href="invoer.php?id=<?= (int) $r['id'] ?>">Bewerken</a>
                        <button type="submit" class="knop knop-gevaar" onclick="return confirm('Weet je zeker dat je dit recept wilt verwijderen?');">Verwijderen</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
<?php endif; ?>

<h2>Profielinstellingen</h2>
<div class="profiel-grid">
    <form method="post" class="recept-formulier recept-formulier-card">
        <?= csrf_veld() ?>
        <input type="hidden" name="actie" value="naam">
        <label>Naam <input type="text" name="naam" maxlength="100" required value="<?= h($_SESSION['naam']) ?>"></label>
        <button type="submit" class="knop knop-primair">Naam opslaan</button>
    </form>
    <form method="post" class="recept-formulier recept-formulier-card">
        <?= csrf_veld() ?>
        <input type="hidden" name="actie" value="wachtwoord">
        <label>Huidig wachtwoord <input type="password" name="huidig_wachtwoord" autocomplete="current-password" required></label>
        <label>Nieuw wachtwoord <input type="password" name="nieuw_wachtwoord" autocomplete="new-password" minlength="8" required></label>
        <button type="submit" class="knop knop-primair">Wachtwoord wijzigen</button>
    </form>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
