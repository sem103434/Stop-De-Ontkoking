<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$recept = haal_recept($pdo, $id);
if (!$recept) {
    http_response_code(404);
    die('Recept niet gevonden.');
}

$magBeheren = is_admin() || (is_ingelogd() && $_SESSION['gebruiker_id'] == $recept['gebruiker_id']);

if ($magBeheren && isset($_POST['verwijderen'])) {
    $stmt = $pdo->prepare('DELETE FROM recepten WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: overzicht.php');
    exit;
}

$paginaTitel = $recept['titel'];
require __DIR__ . '/../includes/header.php';
?>
<article class="recept-detail">
    <a href="overzicht.php">&larr; Terug naar overzicht</a>
    <img class="recept-detail-foto" src="<?= h(recept_afbeelding($recept)) ?>" alt="<?= h($recept['titel']) ?>">
    <h1><?= h($recept['titel']) ?></h1>
    <p class="recept-meta">
        <?= h($recept['categorie_naam']) ?> &middot; door <?= h($recept['auteur']) ?>
        <?php if ($recept['bereidingstijd_min']): ?> &middot; <?= (int) $recept['bereidingstijd_min'] ?> min<?php endif; ?>
    </p>

    <h2>Ingrediënten</h2>
    <ul>
        <?php foreach ($recept['ingredienten'] as $i): ?>
            <li><?= h(format_hoeveelheid($i['hoeveelheid'])) ?> <?= h($i['eenheid']) ?> <?= h($i['naam']) ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Bereidingswijze</h2>
    <ol class="bereidingsstappen">
        <?php foreach (preg_split('/\R+/', trim($recept['bereidingswijze'])) as $stap): ?>
            <?php if (trim($stap) !== ''): ?><li><?= h(trim(preg_replace('/^\s*(?:[-•*]|\d+[.)])\s*/u', '', $stap))) ?></li><?php endif; ?>
        <?php endforeach; ?>
    </ol>

    <?php if ($magBeheren): ?>
        <div class="beheer-acties">
            <a href="invoer.php?id=<?= $id ?>" class="knop">Bewerken</a>
            <form method="post" onsubmit="return confirm('Weet je zeker dat je dit recept wilt verwijderen?');" style="display: inline">
                <button type="submit" name="verwijderen" class="knop knop-gevaar">Verwijderen</button>
            </form>
        </div>
    <?php endif; ?>
</article>
<?php require __DIR__ . '/../includes/footer.php'; ?>
