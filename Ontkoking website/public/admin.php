<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';
vereist_login();
vereist_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verwijder_recept'])) {
        $pdo->prepare('DELETE FROM recepten WHERE id = ?')->execute([(int) $_POST['verwijder_recept']]);
    }
    if (isset($_POST['verwijder_gebruiker'])) {
        $pdo->prepare('DELETE FROM gebruikers WHERE id = ? AND id != ?')
            ->execute([(int) $_POST['verwijder_gebruiker'], $_SESSION['gebruiker_id']]);
    }
    header('Location: admin.php');
    exit;
}

$paginaTitel = 'Beheer';
$recepten = $pdo->query(
    'SELECT r.id, r.titel, g.naam AS auteur
    FROM recepten r
    JOIN gebruikers g ON g.id = r.gebruiker_id
    ORDER BY r.id DESC'
)->fetchAll();
$gebruikers = $pdo->query('SELECT id, naam, email, rol FROM gebruikers ORDER BY naam')->fetchAll();

require __DIR__ . '/../includes/header.php';
?>
<h1>Beheer</h1>

<h2>Recepten (<?= count($recepten) ?>)</h2>
<table class="beheer-tabel">
    <tr>
        <th>Titel</th>
        <th>Auteur</th>
        <th></th>
    </tr>
    <?php foreach ($recepten as $r): ?>
        <tr>
            <td><a href="detail.php?id=<?= (int) $r['id'] ?>"><?= h($r['titel']) ?></a></td>
            <td><?= h($r['auteur']) ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Recept verwijderen?');">
                    <button type="submit" name="verwijder_recept" value="<?= (int) $r['id'] ?>" class="knop knop-gevaar">Verwijderen</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Gebruikers (<?= count($gebruikers) ?>)</h2>
<table class="beheer-tabel">
    <tr>
        <th>Naam</th>
        <th>E-mail</th>
        <th>Rol</th>
        <th></th>
    </tr>
    <?php foreach ($gebruikers as $g): ?>
        <tr>
            <td><?= h($g['naam']) ?></td>
            <td><?= h($g['email']) ?></td>
            <td><?= h($g['rol']) ?></td>
            <td>
                <?php if ($g['id'] != $_SESSION['gebruiker_id']): ?>
                    <form method="post" onsubmit="return confirm('Gebruiker verwijderen?');">
                        <button type="submit" name="verwijder_gebruiker" value="<?= (int) $g['id'] ?>" class="knop knop-gevaar">Verwijderen</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../includes/footer.php'; ?>
