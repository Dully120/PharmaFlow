<?php
// models/Supplier.php
require_once __DIR__ . '/../config/database.php';

class Supplier {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer tous les fournisseurs
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM suppliers ORDER BY name");
        return $stmt->fetchAll();
    }
    
    // Récupérer un fournisseur par ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Créer un fournisseur
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO suppliers (name, address, phone, email, contact_person) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['address'],
            $data['phone'],
            $data['email'],
            $data['contact_person']
        ]);
    }
    
    // Mettre à jour un fournisseur
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE suppliers 
            SET name = ?, address = ?, phone = ?, email = ?, contact_person = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['address'],
            $data['phone'],
            $data['email'],
            $data['contact_person'],
            $id
        ]);
    }
    
    // Supprimer un fournisseur
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM suppliers WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Compter les produits d'un fournisseur
    public function countProducts($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM products WHERE supplier_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
?>