<?php
// models/Report.php
require_once __DIR__ . '/../config/database.php';

class Report {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer les ventes par période
    public function getSalesByPeriod($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT s.id, s.sale_date, u.name as user_name, 
                   s.subtotal, s.tax_amount, s.total,
                   s.paid_amount, s.change_amount
            FROM sales s
            JOIN users u ON s.user_id = u.id
            WHERE DATE(s.sale_date) BETWEEN ? AND ?
            ORDER BY s.sale_date DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        $sales = $stmt->fetchAll();
        
        // Ajouter le total général
        $totalSales = 0;
        foreach ($sales as $sale) {
            $totalSales += $sale['total'];
        }
        
        return [
            'data' => $sales,
            'total' => $totalSales,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
    
    // Récupérer les meilleures ventes par produit
    public function getTopProducts($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.barcode,
                   SUM(si.quantity) as total_quantity,
                   SUM(si.line_total) as total_amount
            FROM sale_items si
            JOIN products p ON si.product_id = p.id
            GROUP BY si.product_id
            ORDER BY total_quantity DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    // Récupérer les bénéfices par période
    public function getProfitsByPeriod($startDate, $endDate) {
        // Ventes totales
        $stmt = $this->db->prepare("
            SELECT SUM(total) as total_sales
            FROM sales
            WHERE DATE(sale_date) BETWEEN ? AND ?
        ");
        $stmt->execute([$startDate, $endDate]);
        $totalSales = $stmt->fetch()['total_sales'] ?? 0;
        
        // Coût des marchandises vendues (CMV)
        $stmt = $this->db->prepare("
            SELECT SUM(si.quantity * p.purchase_price) as total_cost
            FROM sale_items si
            JOIN products p ON si.product_id = p.id
            JOIN sales s ON si.sale_id = s.id
            WHERE DATE(s.sale_date) BETWEEN ? AND ?
        ");
        $stmt->execute([$startDate, $endDate]);
        $totalCost = $stmt->fetch()['total_cost'] ?? 0;
        
        $profit = $totalSales - $totalCost;
        $margin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;
        
        return [
            'total_sales' => $totalSales,
            'total_cost' => $totalCost,
            'profit' => $profit,
            'margin' => $margin,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
    
    // Récupérer les achats par période
    public function getPurchasesByPeriod($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT pi.id, pi.invoice_number, s.name as supplier_name,
                   pi.invoice_date, pi.total_amount, pi.status
            FROM purchase_invoices pi
            JOIN suppliers s ON pi.supplier_id = s.id
            WHERE pi.status = 'approved'
              AND DATE(pi.invoice_date) BETWEEN ? AND ?
            ORDER BY pi.invoice_date DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        $purchases = $stmt->fetchAll();
        
        $totalPurchases = 0;
        foreach ($purchases as $purchase) {
            $totalPurchases += $purchase['total_amount'];
        }
        
        return [
            'data' => $purchases,
            'total' => $totalPurchases,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }

        // Récupérer tous les fournisseurs
    public function getSuppliers() {
        $stmt = $this->db->query("
            SELECT s.*, COUNT(p.id) as products_count
            FROM suppliers s
            LEFT JOIN products p ON s.id = p.supplier_id
            GROUP BY s.id
            ORDER BY s.name
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer toutes les alertes
    public function getAlerts() {
        $stmt = $this->db->query("
            SELECT a.*, p.name as product_name
            FROM alerts a
            JOIN products p ON a.product_id = p.id
            WHERE a.status = 'active'
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
    
    // Récupérer tous les utilisateurs
    public function getUsers() {
        $stmt = $this->db->query("
            SELECT u.*, r.name as role_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            ORDER BY u.name
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer tous les produits avec stock
    public function getAllProducts() {
        $stmt = $this->db->query("
            SELECT p.*, c.name as category_name, s.name as supplier_name,
                   COALESCE(st.quantity, 0) as stock_quantity,
                   st.expiry_date
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            LEFT JOIN stock st ON p.id = st.product_id
            WHERE p.is_deleted = 0
            ORDER BY p.name
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer les produits avec stock faible
    public function getLowStockProducts() {
        $stmt = $this->db->query("
            SELECT p.id, p.name, p.alert_threshold, COALESCE(st.quantity, 0) as quantity
            FROM products p
            LEFT JOIN stock st ON p.id = st.product_id
            WHERE COALESCE(st.quantity, 0) <= p.alert_threshold
              AND COALESCE(st.quantity, 0) > 0
            ORDER BY quantity ASC
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer les produits expirés ou proches
    public function getExpiryProducts() {
        $stmt = $this->db->query("
            SELECT p.id, p.name, st.expiry_date,
                   DATEDIFF(st.expiry_date, CURDATE()) as days_left,
                   st.quantity
            FROM products p
            JOIN stock st ON p.id = st.product_id
            WHERE st.expiry_date IS NOT NULL
              AND st.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)
              AND st.quantity > 0
            ORDER BY days_left ASC
        ");
        return $stmt->fetchAll();
    }
}
?>