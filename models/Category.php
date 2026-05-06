<?php
// models/Category.php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer toutes les catégories
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll();
    }
    
    // Récupérer une catégorie par ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Créer une catégorie
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['description']]);
    }
    
    // Mettre à jour une catégorie
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }
    
    // Supprimer une catégorie
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Compter les produits dans une catégorie
    public function countProducts($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
?>