<?php
// controllers/PharmacistController.php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../includes/functions.php';

class PharmacistController {
    
    private $productModel;
    private $saleModel;
    private $alertModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->saleModel = new Sale();
        $this->alertModel = new Alert();
    }
    
    private function checkAuth() {
        if (!isLoggedIn()) {
            redirect('index.php?controller=auth&action=showLogin');
        }
        if ($_SESSION['user_role'] !== 'pharmacist' && $_SESSION['user_role'] !== 'admin') {
            setFlash('error', '❌ غير مصرح لك بالوصول إلى هذه الصفحة');
            redirect('index.php?controller=dashboard&action=index');
        }
    }
    
    // Interface de vente (POS)
    public function pos() {
        $this->checkAuth();
        
        $this->alertModel->generateAllAlerts();
        $alerts = $this->alertModel->getActiveAlerts();
        $alertCount = count($alerts);
        
        require_once __DIR__ . '/../views/pharmacist/pos.php';
    }
    
    // API : Rechercher un produit (AJAX)
    public function searchProduct() {
        $this->checkAuth();
        
        $keyword = $_GET['q'] ?? '';
        
        if (strlen($keyword) < 2) {
            echo json_encode([]);
            return;
        }
        
        $products = $this->productModel->searchForPOS($keyword);
        
        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'barcode' => $product['barcode'],
                'price' => floatval($product['price']),
                'stock' => intval($product['stock'])
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($results);
    }
    
    // API : Enregistrer une vente
    public function saveSale() {
        $this->checkAuth();
        
        $cart = json_decode($_POST['cart'] ?? '', true);
        $paidAmount = floatval($_POST['paid_amount'] ?? 0);
        $userId = $_SESSION['user_id'];
        
        if (empty($cart)) {
            echo json_encode(['success' => false, 'error' => 'السلة فارغة']);
            return;
        }
        
        if ($paidAmount <= 0) {
            echo json_encode(['success' => false, 'error' => 'المبلغ المدفوع غير صحيح']);
            return;
        }
        
        $result = $this->saleModel->create($userId, $cart, $paidAmount);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    // Afficher le ticket
    public function ticket() {
        $this->checkAuth();
        
        $saleId = $_GET['id'] ?? 0;
        $sale = $this->saleModel->getById($saleId);
        
        if (!$sale) {
            setFlash('error', '❌ فاتورة البيع غير موجودة');
            redirect('index.php?controller=pharmacist&action=pos');
        }
        
        require_once __DIR__ . '/../views/pharmacist/ticket.php';
    }
}
?>