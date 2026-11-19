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
$basisPersonen = max(1, (int) $recept['standaard_personen']);
require __DIR__ . '/../includes/header.php';
?>
<article class="recept-detail">
    <a href="overzicht.php">&larr; Terug naar overzicht</a>
    <img class="recept-detail-foto" src="<?= h(recept_afbeelding($recept)) ?>" alt="<?= h($recept['titel']) ?>">
    <h1>
        <?= h($recept['titel']) ?>
        <?php if ($recept['dieet']): ?>
            <span class="dieet-badge dieet-badge--<?= h($recept['dieet']) ?>"><?= $recept['dieet'] === 'vegan' ? 'Vegan' : 'Halal' ?></span>
        <?php endif; ?>
    </h1>
    <p class="recept-meta">
        <?= h($recept['categorie_naam']) ?> &middot; door <?= h($recept['auteur']) ?>
        <?php if ($recept['bereidingstijd_min']): ?> &middot; <?= (int) $recept['bereidingstijd_min'] ?> min<?php endif; ?>
    </p>

    <div class="personen-kiezer" data-basis-personen="<?= $basisPersonen ?>">
        <span>Aantal personen</span>
        <div class="personen-opties">
            <?php foreach ([1, 2, 3, 4] as $aantal): ?>
                <button type="button" class="personen-optie<?= $aantal === $basisPersonen ? ' actief' : '' ?>" data-aantal="<?= $aantal ?>"><?= $aantal ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <h2>Ingrediënten</h2>
    <ul>
        <?php foreach ($recept['ingredienten'] as $i): ?>
            <li data-basis="<?= h((string) $i['hoeveelheid']) ?>"><span class="hoeveelheid"><?= h(format_hoeveelheid($i['hoeveelheid'])) ?></span> <?= h($i['eenheid']) ?> <?= h($i['naam']) ?></li>
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
<script>
const personenKiezer = document.querySelector('.personen-kiezer');
if (personenKiezer) {
    const basisPersonen = parseFloat(personenKiezer.dataset.basisPersonen) || 1;
    const regels = document.querySelectorAll('.recept-detail li[data-basis]');
    const knoppen = personenKiezer.querySelectorAll('.personen-optie');

    function formatHoeveelheid(getal) {
        getal = Math.round(getal * 100) / 100;
        if (Number.isInteger(getal)) {
            return String(getal);
        }
        return getal.toFixed(2).replace(/0+$/, '').replace(/\.$/, '');
    }

    knoppen.forEach(function (knop) {
        knop.addEventListener('click', function () {
            knoppen.forEach(function (k) {
                k.classList.remove('actief');
            });
            knop.classList.add('actief');

            const aantal = parseInt(knop.dataset.aantal, 10);
            regels.forEach(function (regel) {
                const basisHoeveelheid = parseFloat(regel.dataset.basis);
                regel.querySelector('.hoeveelheid').textContent = formatHoeveelheid(basisHoeveelheid / basisPersonen * aantal);
            });
        });
    });
}
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
