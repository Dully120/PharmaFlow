<?php
// models/User.php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Authentification
    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.email = ? AND u.is_active = 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $updateStmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            return $user;
        }
        
        return false;
    }
    
    // Vérifier si un email existe déjà
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    // Récupérer tous les utilisateurs
    public function getAll() {
        $stmt = $this->db->query("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer un utilisateur par ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Créer un utilisateur
    public function create($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role_id, phone) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $hashedPassword,
            $data['role_id'],
            $data['phone'] ?? null
        ]);
    }
    
    // Mettre à jour un utilisateur
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE users SET name = ?, email = ?, role_id = ?, phone = ? WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['role_id'],
            $data['phone'] ?? null,
            $id
        ]);
    }
    
    // Mettre à jour le mot de passe
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }
    
    // Désactiver un utilisateur
    public function toggleActive($id, $isActive) {
        $stmt = $this->db->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        return $stmt->execute([$isActive, $id]);
    }
}
?>