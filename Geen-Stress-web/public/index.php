<?php
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/functions.php';

$paginaTitel = 'Koken wordt leuk';
$categorieen = haal_categorieen($pdo);
$uitgelicht = array_slice(zoek_recepten($pdo, null, null), 0, 6);
require __DIR__ . '/../includes/header.php';
?>
<section class="hero">
    <div class="hero-content">
        <span class="eyebrow">Geen Stress</span>
        <h1>Wat eten we vandaag?</h1>
        <p>Maak je niet druk over wat je eet vandaag, Geen Stress! Maak een keuze uit lekkere en gemakkelijke recepten.</p>
        <form action="overzicht.php" method="get" class="zoekbalk">
            <input type="search" name="q" placeholder="Waar heb je zin in?" aria-label="Zoek een recept">
            <button type="submit">Vind recepten <span aria-hidden="true">→</span></button>
        </form>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Waar heb je zin in?</span>
            <h2>Neem een kijkje</h2>
            <p></p>
        </div>
        <a class="text-link" href="overzicht.php">Alle recepten →</a>
    </div>
    <div class="categorie-grid">
        <?php foreach ($categorieen as $c): ?>
            <a class="categorie-kaart" href="overzicht.php?categorie=<?= (int) $c['id'] ?>">
                <?= h($c['naam']) ?>
            </a>
        <?php endforeach; ?>
        <?php if (!$categorieen): ?>
            <a class="categorie-kaart" href="overzicht.php">Alles wat lekker is</a>
            <a class="categorie-kaart" href="overzicht.php">Snel &amp; simpel</a>
            <a class="categorie-kaart" href="overzicht.php">Veggie</a>
        <?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Van onze keuken naar die van jou</span>
            <h2>Recepten om blij van te worden</h2>
            <p>Nieuwe inspiratie, gedeeld door de Geen Stress-community.</p>
        </div>
    </div>
    <div class="recept-grid">
        <?php foreach ($uitgelicht as $r): ?>
            <a class="recept-kaart" href="detail.php?id=<?= (int) $r['id'] ?>">
                <div class="recept-kaart-afbeelding">
                    <img src="<?= h(recept_afbeelding($r)) ?>" alt="<?= h($r['titel']) ?>" loading="lazy">
                    <?php if ($r['dieet']): ?>
                        <span class="dieet-badge dieet-badge--<?= h($r['dieet']) ?>"><?= $r['dieet'] === 'vegan' ? 'Vegan' : 'Halal' ?></span>
                    <?php endif; ?>
                </div>
                <div class="recept-kaart-content">
                    <h3><?= h($r['titel']) ?></h3>
                    <p><?= h($r['categorie_naam']) ?> · door <?= h($r['auteur']) ?></p>
                    <div class="card-meta">
                        <span>Bekijk recept -></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
        <?php if (!$uitgelicht): ?>
            <p>
                Nog geen recepten.
                <a href="<?= is_ingelogd() ? 'invoer.php' : 'registreren.php' ?>">Deel als eerste jouw favoriet!</a>
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="feature-band">
    <div class="feature-copy">
        <span class="eyebrow">Samen smaakt alles beter</span>
        <h2>Jouw recept verdient een plekje aan tafel.</h2>
        <p>Heb jij een gerecht waar iedereen om vraagt? Deel het met de community en inspireer iemand voor het avondeten. Pas op: huisgenoten kunnen om een hapje vragen.</p>
        <a class="button button-dark" href="<?= is_ingelogd() ? 'invoer.php' : 'registreren.php' ?>">
            <?= is_ingelogd() ? 'Recept delen' : 'Word lid en deel mee' ?>
            <span aria-hidden="true">→</span>
        </a>
    </div>
    <div class="feature-image" role="img" aria-label="Verse ingrediënten op tafel"></div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
