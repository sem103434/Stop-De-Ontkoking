<?php
function haal_categorieen(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM categorieen ORDER BY naam')->fetchAll();
}

function zoek_recepten(PDO $pdo, ?string $zoekterm, ?int $categorieId): array
{
    $sql = 'SELECT r.*, c.naam AS categorie_naam, g.naam AS auteur
            FROM recepten r
            JOIN categorieen c ON c.id = r.categorie_id
            JOIN gebruikers g ON g.id = r.gebruiker_id
            WHERE 1=1';
    $params = [];

    if ($categorieId) {
        $sql .= ' AND r.categorie_id = ?';
        $params[] = $categorieId;
    }

    if ($zoekterm) {
        $sql .= ' AND (r.titel LIKE ? OR EXISTS (
            SELECT 1 FROM recept_ingredienten ri
            JOIN ingredienten i ON i.id = ri.ingredient_id
            WHERE ri.recept_id = r.id AND i.naam LIKE ?
        ))';
        $params[] = '%' . $zoekterm . '%';
        $params[] = '%' . $zoekterm . '%';
    }

    $sql .= ' ORDER BY r.aangemaakt_op DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function haal_recept(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT r.*, c.naam AS categorie_naam, g.naam AS auteur
                            FROM recepten r
                            JOIN categorieen c ON c.id = r.categorie_id
                            JOIN gebruikers g ON g.id = r.gebruiker_id
                            WHERE r.id = ?');
    $stmt->execute([$id]);
    $recept = $stmt->fetch();
    if (!$recept) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT i.naam, i.eenheid, ri.hoeveelheid
                            FROM recept_ingredienten ri
                            JOIN ingredienten i ON i.id = ri.ingredient_id
                            WHERE ri.recept_id = ?');
    $stmt->execute([$id]);
    $recept['ingredienten'] = $stmt->fetchAll();
    return $recept;
}

function vind_of_maak_ingredient(PDO $pdo, string $naam, ?string $eenheid): int
{
    $stmt = $pdo->prepare('SELECT id FROM ingredienten WHERE naam = ?');
    $stmt->execute([$naam]);
    $bestaand = $stmt->fetch();
    if ($bestaand) {
        return (int) $bestaand['id'];
    }

    $stmt = $pdo->prepare('INSERT INTO ingredienten (naam, eenheid) VALUES (?, ?)');
    $stmt->execute([$naam, $eenheid]);
    return (int) $pdo->lastInsertId();
}

function h(?string $tekst): string
{
    return htmlspecialchars($tekst ?? '', ENT_QUOTES, 'UTF-8');
}

function format_hoeveelheid($hoeveelheid): string
{
    $getal = (float) $hoeveelheid;
    if ($getal === (float) (int) $getal) {
        return (string) (int) $getal;
    }

    return rtrim(rtrim(number_format($getal, 2, '.', ''), '0'), '.');
}

function recept_afbeelding(array $recept): string
{
    if (!empty($recept['afbeelding_url'])) {
        $pad = ltrim((string) $recept['afbeelding_url'], '/');
        if (str_starts_with($pad, 'uploads/')) {
            return $pad;
        }
        return 'images/' . basename($pad);
    }
    $fotoPerGerecht = [
        'Crispy chicken wraps' => 'chicken-wrap.jpg',
        'Pasta pesto met kip en tomaat' => 'chicken-pesto-pasta.jpg',
        'Loaded fries met kip en knoflooksaus' => 'loaded-fries.jpg',
        'Quesadilla met kaas en paprika' => 'quesadilla.jpg',
        'Romige tomatenpasta met mozzarella' => 'tomato-mozzarella-pasta.jpg',
        'Zelfgemaakte cheeseburgers' => 'cheeseburger.jpg',
        'Burrito bowl met gehakt en rijst' => 'burrito-bowl.jpg',
        'Krokante kipburger' => 'chicken-burger.jpg',
        'Taco’s met gehakt en frisse salsa' => 'tacos.jpg',
        'Mac and cheese uit de oven' => 'mac-and-cheese.jpg',
        'Noodles met zoete chilikip' => 'sweet-chili-chicken.jpg',
        'Pizza met salami en paprika' => 'pizza.jpg',
        'Kapsalon uit de oven' => 'kapsalon.jpg',
        'Pita met kipshoarma' => 'pita-shoarma.jpg',
        'Gebakken rijst met ei en groenten' => 'fried-rice.jpg',
        'Romige kipcarbonara' => 'chicken-carbonara.jpg',
        'Butter chicken met rijst' => 'butter-chicken.jpg',
        'Gnocchi met tomaat en kaas' => 'gnocchi.jpg',
        'Burrito’s met bonen en kaas' => 'bean-burrito.jpg',
        'Pittige ramen met ei' => 'ramen.jpg',
        'Tuna melt tosti' => 'tuna-melt.jpg',
        'Caesar wrap met kip' => 'caesar-wrap.jpg',
        'Nachos met gehakt en kaas' => 'nachos.jpg',
        'Spaghetti bolognese' => 'spaghetti-bolognese.jpg',
        'Ovenfeta pasta' => 'oven-feta-pasta.jpg',
        'Sweet chili kip met rijst' => 'sweet-chili-chicken.jpg',
        'Lasagne met gehakt' => 'lasagna.jpg',
        'Grilled cheese met tomatensoep' => 'grilled-cheese-soup.jpg',
        'Tomatensoep met gegrilde kaas' => 'tomato-soup.jpg',
        'Tosti ham-kaas' => 'ham-cheese-toastie.jpg',
        'Zelfgemaakte kipnuggets' => 'chicken-nuggets.jpg',
    ];

    $fotoPerCategorie = [
        'Ontbijt' => 'breakfast.jpg',
        'Lunch' => 'lunch.jpg',
        'Voorgerecht' => 'starter.jpg',
        'Nagerecht' => 'dessert.jpg',
    ];

    $titel = $recept['titel'] ?? '';
    $categorie = $recept['categorie_naam'] ?? '';
    $bestandsnaam = $fotoPerGerecht[$titel]
        ?? $fotoPerCategorie[$categorie]
        ?? 'meal.jpg';

    return 'images/' . $bestandsnaam;
}
