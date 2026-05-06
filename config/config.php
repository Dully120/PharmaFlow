<?php
// config/config.php
session_start();

// Constantes de l'application
define('APP_NAME', 'PharmaFlow');
define('APP_URL', 'http://localhost/pharmaflow');
define('APP_VERSION', '1.0.0');

// Fuseau horaire
date_default_timezone_set('Africa/Tripoli');

// Chemins
define('ROOT_PATH', dirname(__DIR__));
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers/');
define('MODELS_PATH', ROOT_PATH . '/models/');
define('VIEWS_PATH', ROOT_PATH . '/views/');

// Configuration des logs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>