<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';
vereist_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$bewerken = $id !== null;
$recept = $bewerken ? haal_recept($pdo, $id) : null;

if ($bewerken && (!$recept || (!is_admin() && $recept['gebruiker_id'] != $_SESSION['gebruiker_id']))) {
    http_response_code(403);
    die('Je mag dit recept niet bewerken.');
}

$fout = null;
$uploadPad = $recept['afbeelding_url'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titel = trim($_POST['titel'] ?? '');
    $categorieId = (int) ($_POST['categorie_id'] ?? 0);
    $bereidingswijze = trim($_POST['bereidingswijze'] ?? '');
    $bereidingstijd = $_POST['bereidingstijd_min'] !== '' ? (int) $_POST['bereidingstijd_min'] : null;
    $personen = ($_POST['personen'] ?? '') !== '' ? max(1, (int) $_POST['personen']) : 1;
    $ingredientNamen = $_POST['ingredient_naam'] ?? [];
    $ingredientHoeveelheden = $_POST['ingredient_hoeveelheid'] ?? [];
    $ingredientEenheden = $_POST['ingredient_eenheid'] ?? [];

    if (isset($_FILES['afbeelding']) && $_FILES['afbeelding']['error'] !== UPLOAD_ERR_NO_FILE) {
        $bestand = $_FILES['afbeelding'];
        $mimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = $bestand['tmp_name'] && is_uploaded_file($bestand['tmp_name']) ? mime_content_type($bestand['tmp_name']) : '';
        if ($bestand['error'] !== UPLOAD_ERR_OK || $bestand['size'] > 5 * 1024 * 1024 || !isset($mimeTypes[$mime]) || !getimagesize($bestand['tmp_name'])) {
            $fout = 'Kies een JPG-, PNG- of WebP-afbeelding van maximaal 5 MB.';
        } else {
            $uploadMap = __DIR__ . '/uploads';
            if (!is_dir($uploadMap) && !mkdir($uploadMap, 0755, true) && !is_dir($uploadMap)) {
                $fout = 'De uploadmap kon niet worden aangemaakt.';
            } else {
                $bestandsnaam = bin2hex(random_bytes(16)) . '.' . $mimeTypes[$mime];
                if (move_uploaded_file($bestand['tmp_name'], $uploadMap . '/' . $bestandsnaam)) {
                    $uploadPad = 'uploads/' . $bestandsnaam;
                } else {
                    $fout = 'Uploaden is niet gelukt. Probeer het opnieuw.';
                }
            }
        }
    }

    if (!$fout && ($titel === '' || $categorieId === 0 || $bereidingswijze === '')) {
        $fout = 'Titel, categorie en bereidingswijze zijn verplicht.';
    } elseif (!$fout) {
        if ($bewerken) {
            $stmt = $pdo->prepare(
                'UPDATE recepten
                SET titel = ?, categorie_id = ?, bereidingswijze = ?, bereidingstijd_min = ?, standaard_personen = ?, afbeelding_url = ?
                WHERE id = ?'
            );
            $stmt->execute([$titel, $categorieId, $bereidingswijze, $bereidingstijd, $personen, $uploadPad, $id]);
            $pdo->prepare('DELETE FROM recept_ingredienten WHERE recept_id = ?')->execute([$id]);
            $receptId = $id;
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO recepten (gebruiker_id, categorie_id, titel, bereidingswijze, bereidingstijd_min, standaard_personen, afbeelding_url)
                VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$_SESSION['gebruiker_id'], $categorieId, $titel, $bereidingswijze, $bereidingstijd, $personen, $uploadPad]);
            $receptId = (int) $pdo->lastInsertId();
        }

        foreach ($ingredientNamen as $index => $naam) {
            $naam = trim($naam);
            if ($naam === '') {
                continue;
            }
            $ingredientId = vind_of_maak_ingredient($pdo, $naam, $ingredientEenheden[$index] ?? null);
            $hoeveelheid = (float) ($ingredientHoeveelheden[$index] ?? 0);
            $stmt = $pdo->prepare(
                'INSERT INTO recept_ingredienten (recept_id, ingredient_id, hoeveelheid)
                VALUES (?, ?, ?)'
            );
            $stmt->execute([$receptId, $ingredientId, $hoeveelheid]);
        }

        header('Location: detail.php?id=' . $receptId);
        exit;
    }
}

$paginaTitel = $bewerken ? 'Recept bewerken' : 'Recept toevoegen';
$categorieen = haal_categorieen($pdo);
require __DIR__ . '/../includes/header.php';
?>
<div class="page-heading"><span class="eyebrow">Van jou, voor aan tafel</span><h1><?= h($paginaTitel) ?></h1><p>Vul de basics in, zet elke stap op een nieuwe regel en voeg eventueel je eigen foto toe.</p></div>
<?php if ($fout): ?>
    <p class="foutmelding"><?= h($fout) ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="recept-formulier recept-formulier-card">
    <label>
        Titel
        <input type="text" name="titel" required value="<?= h($recept['titel'] ?? '') ?>">
    </label>

    <label>
        Categorie
        <select name="categorie_id" required>
            <option value="">Kies een categorie</option>
            <?php foreach ($categorieen as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= ($recept['categorie_id'] ?? null) == $c['id'] ? 'selected' : '' ?>>
                    <?= h($c['naam']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        Bereidingstijd (minuten)
        <input type="number" name="bereidingstijd_min" min="0" value="<?= h((string) ($recept['bereidingstijd_min'] ?? '')) ?>">
    </label>

    <label>
        Aantal personen (basis)
        <input type="number" name="personen" min="1" max="12" value="<?= h((string) ($recept['standaard_personen'] ?? 1)) ?>">
    </label>

    <label class="upload-veld">
        Eigen receptfoto <span class="veld-hint">JPG, PNG of WebP · max. 5 MB</span>
        <input type="file" name="afbeelding" accept="image/jpeg,image/png,image/webp">
        <?php if ($uploadPad): ?><span class="veld-hint">Er staat al een foto bij dit recept. Kies een nieuwe om die te vervangen.</span><?php endif; ?>
    </label>

    <fieldset>
        <legend>Ingrediënten</legend>
        <div id="ingredienten-lijst">
            <?php
            $bestaandeIngredienten = $recept['ingredienten'] ?? [
                ['naam' => '', 'hoeveelheid' => '', 'eenheid' => ''],
            ];

            foreach ($bestaandeIngredienten as $ing):
                ?>
                <div class="ingredient-rij">
                    <input type="text" name="ingredient_naam[]" placeholder="Ingrediënt" value="<?= h($ing['naam'] ?? '') ?>">
                    <input
                        type="number"
                        step="0.01"
                        name="ingredient_hoeveelheid[]"
                        placeholder="Hoeveelheid"
                        value="<?= ($ing['hoeveelheid'] ?? '') !== '' ? h(format_hoeveelheid($ing['hoeveelheid'])) : '' ?>"
                    >
                    <input type="text" name="ingredient_eenheid[]" placeholder="Eenheid (g, ml, stuks)" value="<?= h($ing['eenheid'] ?? '') ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="ingredient-toevoegen">+ Ingrediënt toevoegen</button>
    </fieldset>

    <label>
        Bereidingswijze
        <textarea name="bereidingswijze" rows="8" required placeholder="Snijd de groenten klein.&#10;Verhit olie in een pan.&#10;Serveer en geniet."><?= h($recept['bereidingswijze'] ?? '') ?></textarea>
        <span class="veld-hint">Tip: zet elke stap op een eigen regel. Op de receptpagina wordt dit een nette lijst.</span>
    </label>

    <button type="submit" class="knop knop-primair"><?= $bewerken ? 'Wijzigingen opslaan' : 'Recept opslaan' ?></button>
</form>

<script>
document.getElementById('ingredient-toevoegen').addEventListener('click', function () {
    const lijst = document.getElementById('ingredienten-lijst');
    const rij = document.createElement('div');
    rij.className = 'ingredient-rij';
    rij.innerHTML = '<input type="text" name="ingredient_naam[]" placeholder="Ingrediënt">' +
        '<input type="number" step="0.01" name="ingredient_hoeveelheid[]" placeholder="Hoeveelheid">' +
        '<input type="text" name="ingredient_eenheid[]" placeholder="Eenheid (g, ml, stuks)">';
    lijst.appendChild(rij);
});
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
