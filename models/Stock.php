<?php
// models/Stock.php
require_once __DIR__ . '/../config/database.php';

class Stock {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer le stock d'un produit
    public function getByProductId($productId) {
        $stmt = $this->db->prepare("SELECT * FROM stock WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }
    
    // Mettre à jour la quantité
    public function updateQuantity($productId, $newQuantity) {
        $stmt = $this->db->prepare("
            UPDATE stock SET quantity = ?, last_updated = NOW() 
            WHERE product_id = ?
        ");
        return $stmt->execute([$newQuantity, $productId]);
    }
    
    // Augmenter la quantité (réception de commande)
    public function increaseQuantity($productId, $quantity, $batchNumber = null, $expiryDate = null) {
        $stmt = $this->db->prepare("
            UPDATE stock 
            SET quantity = quantity + ?, 
                batch_number = COALESCE(?, batch_number),
                expiry_date = COALESCE(?, expiry_date),
                last_updated = NOW()
            WHERE product_id = ?
        ");
        return $stmt->execute([$quantity, $batchNumber, $expiryDate, $productId]);
    }
    
    // Diminuer la quantité (vente)
    public function decreaseQuantity($productId, $quantity) {
        $stmt = $this->db->prepare("
            UPDATE stock 
            SET quantity = quantity - ?, last_updated = NOW()
            WHERE product_id = ? AND quantity >= ?
        ");
        return $stmt->execute([$quantity, $productId, $quantity]);
    }
    
    // Récupérer tous les stocks avec alertes
    public function getAllWithAlerts() {
        $stmt = $this->db->query("
            SELECT p.id, p.name, p.alert_threshold,
                   s.quantity, s.expiry_date,
                   DATEDIFF(s.expiry_date, CURDATE()) as days_until_expiry
            FROM products p
            JOIN stock s ON p.id = s.product_id
            ORDER BY p.name
        ");
        return $stmt->fetchAll();
    }
}
?>