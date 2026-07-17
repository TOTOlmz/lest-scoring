<?php
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// On cale l'heure sur Paris
date_default_timezone_set('Europe/Paris');
$dateFormatter = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE,
    'Europe/Paris'
);

// Appel de l'autoloader Symfony
define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . "/vendor/autoload.php";
require_once ROOT_PATH . "/config.php";
// Récupération des variables d'environnement depuis le fichier .env
$envFile = file(ROOT_PATH . "/.env", FILE_SKIP_EMPTY_LINES);
foreach ($envFile as $line) {
    if (str_contains($line, "#")) {
        continue;
    } else {
        $keyValue = explode("=", $line);
        if (count($keyValue) === 2) {
            $_ENV[trim($keyValue[0])] = trim($keyValue[1]);
        }
    }
}
// Vérification des variables après le chargement
// echo '<pre>';
// print_r($_ENV);
// echo '</pre>';

// Lignes temporaires


// Connexion à la BDD
if ($_ENV["DB_HOST"] && $_ENV["DB_USER"] && $_ENV["DB_PASS"]) {
    try {
        $pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// Appel et instanciation des différents controllers
use App\controllers\HomeController;
use App\controllers\AuthController;
use App\controllers\UserSpaceController;
use App\controllers\AdminSpaceController;

// Récupération de l'URI de la requête (sans les query parameters)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = '/';

if (stripos($path, $base) === 0) {
    $uri = substr($path, strlen($base));
} else {
    $uri = $path;
}

$uri = '/'.ltrim($uri, '/'); // garantit un slash initial
$_ENV["app_uri"] = $uri;


// Section gérant le routing
switch ($uri) {
    case '/':
        new HomeController()->rendered();
        break;
    case "/connexion":
        new AuthController()->connection();
        break;
    case "/inscription":
        new AuthController()->registration();
        break;
    case "/deconnexion":
        new AuthController()->logout();
        break;
    case "/mon-espace":
        new UserSpaceController()->rendered();
        break;
    case "/espace-admin":
        new AdminSpaceController()->rendered();
        break;
}
?>
