<?php
// models/Product.php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer tous les produits avec leurs relations
    public function getAll() {
        $stmt = $this->db->query("
            SELECT p.*, 
                   c.name as category_name, 
                   s.name as supplier_name,
                   st.quantity as stock_quantity,
                   st.expiry_date
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            LEFT JOIN stock st ON p.id = st.product_id
            ORDER BY p.name
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer un produit par ID
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   c.name as category_name, 
                   s.name as supplier_name,
                   st.quantity as stock_quantity,
                   st.expiry_date,
                   st.batch_number,
                   st.location
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            LEFT JOIN stock st ON p.id = st.product_id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Rechercher des produits (pour le POS)
    public function search($keyword) {
        $stmt = $this->db->prepare("
            SELECT p.*, st.quantity as stock_quantity
            FROM products p
            LEFT JOIN stock st ON p.id = st.product_id
            WHERE p.name LIKE ? 
               OR p.barcode LIKE ? 
               OR p.scientific_name LIKE ?
            LIMIT 20
        ");
        $keyword = "%$keyword%";
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    // Créer un produit et son stock associé
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Insérer le produit
            $stmt = $this->db->prepare("
                INSERT INTO products (barcode, name, scientific_name, category_id, supplier_id, 
                                     dosage, form, purchase_price, selling_price, tax_rate, alert_threshold, unit)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $result = $stmt->execute([
                $data['barcode'],
                $data['name'],
                $data['scientific_name'],
                $data['category_id'],
                $data['supplier_id'],
                $data['dosage'],
                $data['form'],
                $data['purchase_price'],
                $data['selling_price'],
                $data['tax_rate'] ?? 0,
                $data['alert_threshold'] ?? 10,
                $data['unit'] ?? 'boîte'
            ]);
            
            $productId = $this->db->lastInsertId();
            
            // Insérer le stock initial
            $stmtStock = $this->db->prepare("
                INSERT INTO stock (product_id, batch_number, expiry_date, quantity, location)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmtStock->execute([
                $productId,
                $data['batch_number'] ?? null,
                $data['expiry_date'] ?? null,
                $data['initial_quantity'] ?? 0,
                $data['location'] ?? null
            ]);
            
            $this->db->commit();
            return $productId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur création produit: " . $e->getMessage());
            return false;
        }
    }
    
    // Mettre à jour un produit
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE products 
            SET barcode = ?, name = ?, scientific_name = ?, category_id = ?, 
                supplier_id = ?, dosage = ?, form = ?, purchase_price = ?, 
                selling_price = ?, tax_rate = ?, alert_threshold = ?, unit = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['barcode'],
            $data['name'],
            $data['scientific_name'],
            $data['category_id'],
            $data['supplier_id'],
            $data['dosage'],
            $data['form'],
            $data['purchase_price'],
            $data['selling_price'],
            $data['tax_rate'] ?? 0,
            $data['alert_threshold'] ?? 10,
            $data['unit'] ?? 'boîte',
            $id
        ]);
    }
    
    // Supprimer un produit
        // Supprimer un produit et toutes ses dépendances
    public function delete($id) {
        try {
            $this->db->beginTransaction();
            
            // 1. Supprimer les lignes de vente liées à ce produit
            $stmt = $this->db->prepare("DELETE FROM sale_items WHERE product_id = ?");
            $stmt->execute([$id]);
            
            // 2. Supprimer les lignes d'achat liées à ce produit
            $stmt = $this->db->prepare("DELETE FROM purchase_items WHERE product_id = ?");
            $stmt->execute([$id]);
            
            // 3. Supprimer le stock lié à ce produit
            $stmt = $this->db->prepare("DELETE FROM stock WHERE product_id = ?");
            $stmt->execute([$id]);
            
            // 4. Supprimer les alertes liées à ce produit
            $stmt = $this->db->prepare("DELETE FROM alerts WHERE product_id = ?");
            $stmt->execute([$id]);
            
            // 5. Supprimer le produit
            $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression du produit: " . $e->getMessage());
            return false;
        }
    }

    // Recherche pour le POS (retourne uniquement les infos nécessaires)
public function searchForPOS($keyword) {
    $stmt = $this->db->prepare("
        SELECT p.id, p.name, p.barcode, p.selling_price as price, 
               COALESCE(s.quantity, 0) as stock
        FROM products p
        LEFT JOIN stock s ON p.id = s.product_id
        WHERE (p.name LIKE ? OR p.barcode LIKE ? OR p.scientific_name LIKE ?)
          AND p.is_deleted = 0
        LIMIT 20
    ");
    $keyword = "%$keyword%";
    $stmt->execute([$keyword, $keyword, $keyword]);
    return $stmt->fetchAll();
}

}
?>