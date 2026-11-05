<?php
$localConfig = __DIR__ . '/config.local.php';
if (file_exists($localConfig)) {
    require $localConfig;
} else {
	define('DB_HOST', 'localhost:3306');
	define('DB_NAME', 'db.recepten');
	define('DB_USER', 'ftp103434');
	define('DB_PASS', 'Pvsl23122509!');
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Databaseverbinding mislukt. Controleer config/config.local.php.');
}
