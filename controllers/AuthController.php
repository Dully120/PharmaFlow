<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/functions.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    // Afficher la page de connexion
    public function showLogin() {
        if (isLoggedIn()) {
            redirect('index.php?controller=dashboard&action=index');
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    // Traiter la connexion
    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Vérification 1 : Champs vides
        if (empty($email) || empty($password)) {
            setFlash('error', '❌ يرجى إدخال البريد الإلكتروني وكلمة المرور');
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        // Vérification 2 : Format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', '❌ البريد الإلكتروني غير صالح. يرجى إدخال بريد إلكتروني صحيح (مثال: name@domain.com)');
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        // Tentative d'authentification
        $user = $this->userModel->authenticate($email, $password);
        
        if ($user) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['user_role_id'] = $user['role_id'];
            
            setFlash('success', '✅ مرحباً ' . $user['name'] . '، تم تسجيل الدخول بنجاح');
            
            // Redirection selon le rôle
            switch ($user['role_name']) {
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
                    redirect('index.php?controller=dashboard&action=index');
            }
        } else {
            // Vérification 3 : L'email existe-t-il ?
            $emailExists = $this->userModel->emailExists($email);
            
            if (!$emailExists) {
                setFlash('error', '❌ هذا البريد الإلكتروني غير مسجل في النظام. يرجى التحقق من البريد الإلكتروني.');
            } else {
                setFlash('error', '❌ كلمة المرور غير صحيحة. يرجى المحاولة مرة أخرى.');
            }
            redirect('index.php?controller=auth&action=showLogin');
        }
    }
    
    // Déconnexion
    public function logout() {
        session_destroy();
        setFlash('success', '✅ تم تسجيل الخروج بنجاح');
        redirect('index.php?controller=auth&action=showLogin');
    }
}
?>