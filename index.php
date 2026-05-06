<?php
// index.php - Front Controller
require_once 'config/config.php';
require_once 'includes/functions.php';

// Autoloader simple
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/includes/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Router simple
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'showLogin';

// Construire le nom du contrôleur
$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile = CONTROLLERS_PATH . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerInstance = new $controllerClass();
    
    if (method_exists($controllerInstance, $action)) {
        // Vérifier si l'utilisateur doit être connecté (sauf pour auth)
        if ($controller !== 'auth') {
            if (!isLoggedIn()) {
                redirect('index.php?controller=auth&action=showLogin');
            }
        }
        $controllerInstance->$action();
    } else {
        die("Action '$action' non trouvée dans le contrôleur '$controllerClass'");
    }
} else {
    die("Contrôleur '$controllerClass' non trouvé");
}
?>