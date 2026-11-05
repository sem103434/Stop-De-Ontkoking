<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

$paginaTitel = 'Ontdek recepten';
$zoekterm = trim($_GET['q'] ?? '');
$categorieId = isset($_GET['categorie']) ? (int) $_GET['categorie'] : null;
$categorieen = haal_categorieen($pdo);
$recepten = zoek_recepten($pdo, $zoekterm ?: null, $categorieId ?: null);

require __DIR__ . '/../includes/header.php';
?>
<div class="section-heading">
    <div>
        <span class="eyebrow">Volop inspiratie</span>
        <h1>Ontdek recepten</h1>
        <p>Vind iets nieuws om vanavond op tafel te zetten.</p>
    </div>
</div>

<form action="overzicht.php" method="get" class="filterbalk">
    <input type="text" name="q" placeholder="Zoek op ingrediënt of gerecht..." value="<?= h($zoekterm) ?>">
    <select name="categorie">
        <option value="">Alle categorieën</option>
        <?php foreach ($categorieen as $c): ?>
            <option value="<?= (int) $c['id'] ?>" <?= $categorieId === (int) $c['id'] ? 'selected' : '' ?>>
                <?= h($c['naam']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filteren</button>
</form>

<div class="recept-grid">
    <?php foreach ($recepten as $r): ?>
        <a class="recept-kaart" href="detail.php?id=<?= (int) $r['id'] ?>">
            <div class="recept-kaart-afbeelding">
                <img src="<?= h(recept_afbeelding($r)) ?>" alt="<?= h($r['titel']) ?>" loading="lazy">
            </div>
            <div class="recept-kaart-content">
                <h3><?= h($r['titel']) ?></h3>
                <p><?= h($r['categorie_naam']) ?> &middot; door <?= h($r['auteur']) ?></p>
                <div class="card-meta">
                    <span>Recept van de community</span>
                    <span>Bekijk →</span>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
    <?php if (!$recepten): ?>
        <p>Geen recepten gevonden voor deze zoekopdracht.</p>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
