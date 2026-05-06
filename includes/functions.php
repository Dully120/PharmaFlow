<?php
// includes/functions.php

// Redirection
function redirect($url) {
    header("Location: " . APP_URL . "/" . $url);
    exit();
}

// Définir un message flash
function setFlash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

// Récupérer un message flash (simple)
function getFlash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

// Afficher tous les messages flash (version complète avec icônes)
function displayFlashMessages() {
    $html = '';
    
    if (isset($_SESSION['flash']['error'])) {
        $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> ' . h($_SESSION['flash']['error']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        unset($_SESSION['flash']['error']);
    }
    
    if (isset($_SESSION['flash']['success'])) {
        $html .= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> ' . h($_SESSION['flash']['success']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        unset($_SESSION['flash']['success']);
    }
    
    if (isset($_SESSION['flash']['warning'])) {
        $html .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> ' . h($_SESSION['flash']['warning']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        unset($_SESSION['flash']['warning']);
    }
    
    if (isset($_SESSION['flash']['info'])) {
        $html .= '<div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i> ' . h($_SESSION['flash']['info']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        unset($_SESSION['flash']['info']);
    }
    
    return $html;
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifier le rôle de l'utilisateur
function hasRole($roleName) {
    if (!isset($_SESSION['user_role'])) return false;
    return $_SESSION['user_role'] === $roleName;
}

// Sécuriser la sortie HTML
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>