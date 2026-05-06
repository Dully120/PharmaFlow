<?php
// controllers/DashboardController.php
require_once __DIR__ . '/../includes/functions.php';

class DashboardController {
    
    // Tableau de bord générique
    public function index() {
        if (!isLoggedIn()) {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        // Rediriger vers le tableau de bord selon le rôle
        switch ($_SESSION['user_role']) {
            case 'admin':
                redirect('index.php?controller=admin&action=dashboard');
                break;
            case 'pharmacist':
                redirect('index.php?controller=pharmacist&action=pos');
                break;
            case 'accountant':
                redirect('index.php?controller=accountant&action=invoices');
                break;
            default:
                echo "Tableau de bord - Bienvenue " . h($_SESSION['user_name']);
        }
    }
}
?>