<?php
// models/Alert.php
require_once __DIR__ . '/../config/database.php';

class Alert {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Générer toutes les alertes (stock faible + péremption)
     * Sans duplication - suppression des anciennes avant génération
     */
    public function generateAllAlerts() {
        try {
            // 1. Supprimer toutes les alertes actives existantes
            $stmt = $this->db->prepare("UPDATE alerts SET status = 'resolved' WHERE status = 'active'");
            $stmt->execute();
            
            // 2. Générer les alertes de stock faible
            $this->generateLowStockAlerts();
            
            // 3. Générer les alertes de péremption
            $this->generateExpiryAlerts();
            
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la génération des alertes: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Générer les alertes de stock faible
     */
    private function generateLowStockAlerts() {
        $stmt = $this->db->query("
            SELECT DISTINCT p.id, p.name, p.alert_threshold, COALESCE(s.quantity, 0) as quantity
            FROM products p
            LEFT JOIN stock s ON p.id = s.product_id
            WHERE COALESCE(s.quantity, 0) <= p.alert_threshold
              AND COALESCE(s.quantity, 0) > 0
              AND p.is_deleted = 0
        ");
        $lowStockProducts = $stmt->fetchAll();
        
        foreach ($lowStockProducts as $product) {
            $message = "⚠️ مخزون منخفض : {$product['name']} - المتبقي {$product['quantity']} (الحد: {$product['alert_threshold']})";
            
            $insertStmt = $this->db->prepare("
                INSERT INTO alerts (product_id, type, message, status, created_at) 
                VALUES (?, 'stock_low', ?, 'active', NOW())
            ");
            $insertStmt->execute([$product['id'], $message]);
        }
    }
    
    /**
     * Générer les alertes de péremption
     */
    private function generateExpiryAlerts() {
        $stmt = $this->db->query("
            SELECT DISTINCT p.id, p.name, s.expiry_date,
                   DATEDIFF(s.expiry_date, CURDATE()) as days_left
            FROM products p
            JOIN stock s ON p.id = s.product_id
            WHERE s.expiry_date IS NOT NULL
              AND s.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 180 DAY)
              AND s.quantity > 0
              AND p.is_deleted = 0
            ORDER BY days_left ASC
        ");
        $expiryProducts = $stmt->fetchAll();
        
        foreach ($expiryProducts as $product) {
            $daysLeft = $product['days_left'];
            
            if ($daysLeft <= 0) {
                $type = 'expired';
                $message = "🔴⚰️ منتهي الصلاحية : {$product['name']} - انتهى صلاحيته منذ " . abs($daysLeft) . " يوماً! يجب التخلص منه فوراً.";
            } elseif ($daysLeft <= 30) {
                $type = 'expiry_critical';
                $message = "🔴 تنبيه عاجل : {$product['name']} ينتهي صلاحيته بعد {$daysLeft} يوماً!";
            } else {
                $type = 'expiry_warning';
                $message = "🟠 تنبيه : {$product['name']} ينتهي صلاحيته بعد {$daysLeft} يوماً";
            }
            
            $insertStmt = $this->db->prepare("
                INSERT INTO alerts (product_id, type, message, status, created_at) 
                VALUES (?, ?, ?, 'active', NOW())
            ");
            $insertStmt->execute([$product['id'], $type, $message]);
        }
    }
    
    /**
     * Récupérer toutes les alertes actives
     */
    public function getActiveAlerts() {
        $stmt = $this->db->query("
            SELECT DISTINCT a.*, p.name as product_name
            FROM alerts a
            JOIN products p ON a.product_id = p.id
            WHERE a.status = 'active'
              AND p.is_deleted = 0
            ORDER BY 
                CASE a.type 
                    WHEN 'expired' THEN 1
                    WHEN 'expiry_critical' THEN 2
                    WHEN 'stock_low' THEN 3
                    WHEN 'expiry_warning' THEN 4
                    ELSE 5
                END,
                a.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer une alerte par ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, p.name as product_name
            FROM alerts a
            JOIN products p ON a.product_id = p.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Marquer une alerte comme résolue
     */
    public function resolveAlert($alertId) {
        $stmt = $this->db->prepare("
            UPDATE alerts SET status = 'resolved' WHERE id = ?
        ");
        return $stmt->execute([$alertId]);
    }
    
    /**
     * Marquer toutes les alertes d'un produit comme résolues
     */
    public function resolveAlertsByProduct($productId) {
        $stmt = $this->db->prepare("
            UPDATE alerts SET status = 'resolved' WHERE product_id = ? AND status = 'active'
        ");
        return $stmt->execute([$productId]);
    }
    
    /**
     * Compter les alertes actives
     */
    public function countActive() {
        $stmt = $this->db->query("
            SELECT COUNT(DISTINCT id) as total 
            FROM alerts 
            WHERE status = 'active'
        ");
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Compter les alertes par type
     */
    public function countByType() {
        $stmt = $this->db->query("
            SELECT type, COUNT(*) as total
            FROM alerts
            WHERE status = 'active'
            GROUP BY type
        ");
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[$row['type']] = $row['total'];
        }
        return $result;
    }
    
    /**
     * Supprimer toutes les alertes résolues (plus anciennes que X jours)
     */
    public function cleanupOldResolved($days = 30) {
        $stmt = $this->db->prepare("
            DELETE FROM alerts 
            WHERE status = 'resolved' 
              AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }
    
    /**
     * Vérifier si un produit a une alerte active
     */
    public function hasActiveAlert($productId, $type = null) {
        if ($type) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM alerts 
                WHERE product_id = ? AND type = ? AND status = 'active'
            ");
            $stmt->execute([$productId, $type]);
        } else {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM alerts 
                WHERE product_id = ? AND status = 'active'
            ");
            $stmt->execute([$productId]);
        }
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Rafraîchir les alertes pour un produit spécifique
     */
    public function refreshProductAlerts($productId) {
        // Résoudre les anciennes alertes
        $this->resolveAlertsByProduct($productId);
        
        // Récupérer les informations du produit
        $stmt = $this->db->prepare("
            SELECT p.*, s.quantity, s.expiry_date
            FROM products p
            LEFT JOIN stock s ON p.id = s.product_id
            WHERE p.id = ? AND p.is_deleted = 0
        ");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if (!$product) return;
        
        // Vérifier stock faible
        $quantity = $product['quantity'] ?? 0;
        $threshold = $product['alert_threshold'] ?? 10;
        
        if ($quantity <= $threshold && $quantity > 0) {
            $message = "⚠️ مخزون منخفض : {$product['name']} - المتبقي {$quantity} (الحد: {$threshold})";
            $insertStmt = $this->db->prepare("
                INSERT INTO alerts (product_id, type, message, status, created_at) 
                VALUES (?, 'stock_low', ?, 'active', NOW())
            ");
            $insertStmt->execute([$productId, $message]);
        }
        
        // Vérifier péremption
        if ($product['expiry_date']) {
            $daysLeft = (strtotime($product['expiry_date']) - time()) / (60 * 60 * 24);
            
            if ($daysLeft <= 0) {
                $message = "🔴⚰️ منتهي الصلاحية : {$product['name']} - انتهى صلاحيته منذ " . abs($daysLeft) . " يوماً!";
                $insertStmt = $this->db->prepare("
                    INSERT INTO alerts (product_id, type, message, status, created_at) 
                    VALUES (?, 'expired', ?, 'active', NOW())
                ");
                $insertStmt->execute([$productId, $message]);
            } elseif ($daysLeft <= 30) {
                $message = "🔴 تنبيه عاجل : {$product['name']} ينتهي صلاحيته بعد {$daysLeft} يوماً!";
                $insertStmt = $this->db->prepare("
                    INSERT INTO alerts (product_id, type, message, status, created_at) 
                    VALUES (?, 'expiry_critical', ?, 'active', NOW())
                ");
                $insertStmt->execute([$productId, $message]);
            } elseif ($daysLeft <= 180) {
                $message = "🟠 تنبيه : {$product['name']} ينتهي صلاحيته بعد {$daysLeft} يوماً";
                $insertStmt = $this->db->prepare("
                    INSERT INTO alerts (product_id, type, message, status, created_at) 
                    VALUES (?, 'expiry_warning', ?, 'active', NOW())
                ");
                $insertStmt->execute([$productId, $message]);
            }
        }
    }
}
?>