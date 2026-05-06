<?php
// models/PurchaseInvoice.php
require_once __DIR__ . '/../config/database.php';

class PurchaseInvoice {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Créer une facture en attente
    public function create($data, $items) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("
                INSERT INTO purchase_invoices (supplier_id, invoice_number, invoice_date, total_amount, status, recorded_by)
                VALUES (?, ?, ?, ?, 'pending', ?)
            ");
            $stmt->execute([
                $data['supplier_id'],
                $data['invoice_number'],
                $data['invoice_date'],
                $data['total_amount'],
                $_SESSION['user_id']
            ]);
            
            $invoiceId = $this->db->lastInsertId();
            
            foreach ($items as $item) {
                $stmtItem = $this->db->prepare("
                    INSERT INTO purchase_items (invoice_id, product_id, quantity, purchase_price, line_total)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmtItem->execute([
                    $invoiceId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['purchase_price'],
                    $item['quantity'] * $item['purchase_price']
                ]);
            }
            
            $this->db->commit();
            return $invoiceId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur création facture: " . $e->getMessage());
            return false;
        }
    }
    
    // Approuver une facture (met à jour stock et prix)
    public function approve($invoiceId) {
        try {
            $this->db->beginTransaction();
            
            // Mettre à jour le statut
            $stmt = $this->db->prepare("
                UPDATE purchase_invoices 
                SET status = 'approved', approved_by = ?, approved_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$_SESSION['user_id'], $invoiceId]);
            
            // Récupérer les items
            $stmtItems = $this->db->prepare("
                SELECT * FROM purchase_items WHERE invoice_id = ?
            ");
            $stmtItems->execute([$invoiceId]);
            $items = $stmtItems->fetchAll();
            
            foreach ($items as $item) {
                // Mettre à jour le stock
                $stmtStock = $this->db->prepare("
                    UPDATE stock 
                    SET quantity = quantity + ?, last_updated = NOW()
                    WHERE product_id = ?
                ");
                $stmtStock->execute([$item['quantity'], $item['product_id']]);
                
                // Mettre à jour le prix d'achat du produit
                $stmtProduct = $this->db->prepare("
                    UPDATE products 
                    SET purchase_price = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmtProduct->execute([$item['purchase_price'], $item['product_id']]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur approbation facture: " . $e->getMessage());
            return false;
        }
    }
    
    // Rejeter une facture
    public function reject($invoiceId, $reason) {
        $stmt = $this->db->prepare("
            UPDATE purchase_invoices 
            SET status = 'rejected', rejection_reason = ?
            WHERE id = ?
        ");
        return $stmt->execute([$reason, $invoiceId]);
    }
    
    // Récupérer les factures en attente
    public function getPending() {
        $stmt = $this->db->query("
            SELECT pi.*, s.name as supplier_name, u.name as recorder_name
            FROM purchase_invoices pi
            JOIN suppliers s ON pi.supplier_id = s.id
            JOIN users u ON pi.recorded_by = u.id
            WHERE pi.status = 'pending'
            ORDER BY pi.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer les factures approuvées
    public function getApproved() {
        $stmt = $this->db->query("
            SELECT pi.*, s.name as supplier_name, u.name as recorder_name, a.name as approver_name
            FROM purchase_invoices pi
            JOIN suppliers s ON pi.supplier_id = s.id
            JOIN users u ON pi.recorded_by = u.id
            LEFT JOIN users a ON pi.approved_by = a.id
            WHERE pi.status = 'approved'
            ORDER BY pi.approved_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer les détails d'une facture
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT pi.*, s.name as supplier_name
            FROM purchase_invoices pi
            JOIN suppliers s ON pi.supplier_id = s.id
            WHERE pi.id = ?
        ");
        $stmt->execute([$id]);
        $invoice = $stmt->fetch();
        
        if ($invoice) {
            $stmtItems = $this->db->prepare("
                SELECT pi.*, p.name as product_name, p.barcode
                FROM purchase_items pi
                JOIN products p ON pi.product_id = p.id
                WHERE pi.invoice_id = ?
            ");
            $stmtItems->execute([$id]);
            $invoice['items'] = $stmtItems->fetchAll();
        }
        
        return $invoice;
    }
}
?>