<?php
// models/Sale.php
require_once __DIR__ . '/../config/database.php';

class Sale {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Enregistrer une nouvelle vente
    public function create($userId, $cart, $paidAmount) {
        try {
            $this->db->beginTransaction();
            
            // Calculer les totaux
            $subtotal = 0;
            $taxAmount = 0;
            
            foreach ($cart as $item) {
                $lineTotal = $item['price'] * $item['quantity'];
                $subtotal += $lineTotal;
            }
            
            $total = $subtotal + $taxAmount;
            $changeAmount = $paidAmount - $total;
            
            // Insérer la vente
            $stmt = $this->db->prepare("
                INSERT INTO sales (user_id, subtotal, tax_amount, total, paid_amount, change_amount)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $subtotal, $taxAmount, $total, $paidAmount, $changeAmount]);
            $saleId = $this->db->lastInsertId();
            
            // Insérer les lignes de vente et mettre à jour le stock
            foreach ($cart as $item) {
                $lineTotal = $item['price'] * $item['quantity'];
                
                $stmt = $this->db->prepare("
                    INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, line_total)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$saleId, $item['id'], $item['quantity'], $item['price'], $lineTotal]);
                
                // Mettre à jour le stock
                $stmtStock = $this->db->prepare("
                    UPDATE stock SET quantity = quantity - ?, last_updated = NOW()
                    WHERE product_id = ?
                ");
                $stmtStock->execute([$item['quantity'], $item['id']]);
            }
            
            $this->db->commit();
            
            // Générer les alertes après mise à jour du stock
            $alertModel = new Alert();
            $alertModel->generateAllAlerts();
            
            return [
                'success' => true,
                'sale_id' => $saleId,
                'total' => $total,
                'change' => $changeAmount
            ];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur lors de l'enregistrement de la vente: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    // Récupérer une vente par ID (pour le ticket)
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name
            FROM sales s
            JOIN users u ON s.user_id = u.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();
        
        if ($sale) {
            $stmtItems = $this->db->prepare("
                SELECT si.*, p.name as product_name
                FROM sale_items si
                JOIN products p ON si.product_id = p.id
                WHERE si.sale_id = ?
            ");
            $stmtItems->execute([$id]);
            $sale['items'] = $stmtItems->fetchAll();
        }
        
        return $sale;
    }
    
    // Récupérer l'historique des ventes
    public function getAll($limit = 100) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name
            FROM sales s
            JOIN users u ON s.user_id = u.id
            ORDER BY s.sale_date DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    // Récupérer les ventes du jour
    public function getTodaySales() {
        $stmt = $this->db->query("
            SELECT COUNT(*) as count, SUM(total) as total
            FROM sales
            WHERE DATE(sale_date) = CURDATE()
        ");
        return $stmt->fetch();
    }
}
?>