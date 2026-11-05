# Geen Stress

Een community-receptenwebsite in PHP 8+ en MySQL/MariaDB, met zoeken, categorieën, receptinvoer en registratie/inloggen. De vormgeving is responsief en werkt op mobiel en desktop.

## Naar Plesk uploaden

1. Upload de inhoud van deze projectmap naar de server. Houd `config/`, `includes/`, `public/` en `schema.sql` bij elkaar.
2. Stel de document root in op de map `public/`.
3. Maak in Plesk een MySQL/MariaDB-database en gebruiker aan. Importeer `schema.sql` via phpMyAdmin.
4. Kopieer `config/config.local.example.php` naar `config/config.local.php` en vul de gegevens van Plesk in.
5. Gebruik PHP 8+ met PDO en PDO MySQL.

Bij een bestaande installatie kun je ook alleen de bijgewerkte bestanden uploaden: `includes/header.php`, `includes/footer.php`, `public/index.php`, `public/overzicht.php`, `public/login.php`, `public/registreren.php` en `public/css/style.css`. Maak vooraf een kopie van de huidige bestanden. De database hoeft voor de nieuwe vormgeving niet opnieuw geïmporteerd te worden.

## Afbeeldingen en lettertypen

De gerechtfoto's staan lokaal in `public/images/`. De website kiest voor voorbeeldrecepten een foto op basis van de titel; voor andere recepten wordt een foto op basis van de categorie gekozen. De webfonts worden vanaf Google Fonts geladen en hebben internet nodig.

## Beheeraccount

De startdata bevatten een testbeheerder. Gebruik de eerder afgesproken testgegevens alleen voor een eerste controle en wijzig het wachtwoord meteen na inloggen. Gebruik op een publieke website een uniek wachtwoord.
