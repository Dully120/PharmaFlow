<?php
// controllers/AdminController.php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../includes/functions.php';

class AdminController {
    
    private $productModel;
    private $categoryModel;
    private $supplierModel;
    private $userModel;
    private $alertModel;
    private $reportModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->supplierModel = new Supplier();
        $this->userModel = new User();
        $this->alertModel = new Alert();
        $this->reportModel = new Report();
    }
    
    // ========== DASHBOARD ==========
    public function dashboard() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $this->alertModel->generateAllAlerts();
        
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        $suppliers = $this->supplierModel->getAll();
        $users = $this->userModel->getAll();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    // ========== GESTION DES CATÉGORIES ==========
    public function categories() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $categories = $this->categoryModel->getAll();
        $categoryModel = $this->categoryModel;
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/categories.php';
    }
    
    public function categoryCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            
            if (empty($name)) {
                setFlash('error', '❌ اسم الفئة مطلوب');
                redirect('index.php?controller=admin&action=categories');
            }
            
            $result = $this->categoryModel->create($_POST);
            if ($result) {
                setFlash('success', '✅ تم إضافة الفئة "' . h($name) . '" بنجاح');
            } else {
                setFlash('error', '❌ حدث خطأ أثناء إضافة الفئة');
            }
            redirect('index.php?controller=admin&action=categories');
        }
        
        require_once __DIR__ . '/../views/admin/category_form.php';
    }
    
    public function categoryEdit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف الفئة غير صحيح');
            redirect('index.php?controller=admin&action=categories');
        }
        
        $category = $this->categoryModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            
            if (empty($name)) {
                setFlash('error', '❌ اسم الفئة مطلوب');
                redirect('index.php?controller=admin&action=categories');
            }
            
            $result = $this->categoryModel->update($id, $_POST);
            if ($result) {
                setFlash('success', '✅ تم تعديل الفئة "' . h($name) . '" بنجاح');
            } else {
                setFlash('error', '❌ حدث خطأ أثناء تعديل الفئة');
            }
            redirect('index.php?controller=admin&action=categories');
        }
        
        require_once __DIR__ . '/../views/admin/category_form.php';
    }
    
    public function categoryDelete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف الفئة غير صحيح');
            redirect('index.php?controller=admin&action=categories');
        }
        
        $category = $this->categoryModel->getById($id);
        $count = $this->categoryModel->countProducts($id);
        
        if ($count > 0) {
            setFlash('error', '❌ لا يمكن حذف فئة "' . h($category['name']) . '" لأنها تحتوي على ' . $count . ' منتج(ات)');
        } else {
            $this->categoryModel->delete($id);
            setFlash('success', '✅ تم حذف الفئة "' . h($category['name']) . '" بنجاح');
        }
        redirect('index.php?controller=admin&action=categories');
    }
    
    // ========== GESTION DES FOURNISSEURS ==========
    public function suppliers() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $suppliers = $this->supplierModel->getAll();
        $supplierModel = $this->supplierModel;
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/suppliers.php';
    }
    
    public function supplierCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            
            if (empty($name)) {
                setFlash('error', '❌ اسم المورد مطلوب');
                redirect('index.php?controller=admin&action=suppliers');
            }
            
            $result = $this->supplierModel->create($_POST);
            if ($result) {
                setFlash('success', '✅ تم إضافة المورد "' . h($name) . '" بنجاح');
            } else {
                setFlash('error', '❌ حدث خطأ أثناء إضافة المورد');
            }
            redirect('index.php?controller=admin&action=suppliers');
        }
        
        require_once __DIR__ . '/../views/admin/supplier_form.php';
    }
    
    public function supplierEdit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف المورد غير صحيح');
            redirect('index.php?controller=admin&action=suppliers');
        }
        
        $supplier = $this->supplierModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            
            if (empty($name)) {
                setFlash('error', '❌ اسم المورد مطلوب');
                redirect('index.php?controller=admin&action=suppliers');
            }
            
            $result = $this->supplierModel->update($id, $_POST);
            if ($result) {
                setFlash('success', '✅ تم تعديل المورد "' . h($name) . '" بنجاح');
            } else {
                setFlash('error', '❌ حدث خطأ أثناء تعديل المورد');
            }
            redirect('index.php?controller=admin&action=suppliers');
        }
        
        require_once __DIR__ . '/../views/admin/supplier_form.php';
    }
    
    public function supplierDelete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف المورد غير صحيح');
            redirect('index.php?controller=admin&action=suppliers');
        }
        
        $supplier = $this->supplierModel->getById($id);
        $count = $this->supplierModel->countProducts($id);
        
        if ($count > 0) {
            setFlash('error', '❌ لا يمكن حذف المورد "' . h($supplier['name']) . '" لأنه مرتبط بـ ' . $count . ' منتج(ات)');
        } else {
            $this->supplierModel->delete($id);
            setFlash('success', '✅ تم حذف المورد "' . h($supplier['name']) . '" بنجاح');
        }
        redirect('index.php?controller=admin&action=suppliers');
    }
    
    // ========== GESTION DES PRODUITS ==========
    public function products() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        $suppliers = $this->supplierModel->getAll();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/products.php';
    }
    
    public function productCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $sellingPrice = floatval($_POST['selling_price'] ?? 0);
            $purchasePrice = floatval($_POST['purchase_price'] ?? 0);
            
            if (empty($name)) {
                setFlash('error', '❌ اسم المنتج مطلوب');
                redirect('index.php?controller=admin&action=products');
            }
            
            if ($sellingPrice <= 0) {
                setFlash('error', '❌ سعر البيع يجب أن يكون أكبر من صفر');
                redirect('index.php?controller=admin&action=products');
            }
            
            if ($sellingPrice < $purchasePrice) {
                setFlash('error', '❌ سعر البيع أقل من سعر الشراء');
                redirect('index.php?controller=admin&action=products');
            }
            
            $result = $this->productModel->create($_POST);
            if ($result) {
                setFlash('success', '✅ تم إضافة المنتج "' . h($name) . '" بنجاح');
                $this->alertModel->refreshProductAlerts($result);
            } else {
                setFlash('error', '❌ حدث خطأ أثناء إضافة المنتج');
            }
            redirect('index.php?controller=admin&action=products');
        }
        
        $categories = $this->categoryModel->getAll();
        $suppliers = $this->supplierModel->getAll();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/product_form.php';
    }
    
    public function productEdit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف المنتج غير صحيح');
            redirect('index.php?controller=admin&action=products');
        }
        
        $product = $this->productModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $sellingPrice = floatval($_POST['selling_price'] ?? 0);
            $purchasePrice = floatval($_POST['purchase_price'] ?? 0);
            
            if (empty($name)) {
                setFlash('error', '❌ اسم المنتج مطلوب');
                redirect('index.php?controller=admin&action=products');
            }
            
            if ($sellingPrice <= 0) {
                setFlash('error', '❌ سعر البيع يجب أن يكون أكبر من صفر');
                redirect('index.php?controller=admin&action=products');
            }
            
            $result = $this->productModel->update($id, $_POST);
            if ($result) {
                setFlash('success', '✅ تم تعديل المنتج "' . h($name) . '" بنجاح');
                $this->alertModel->refreshProductAlerts($id);
            } else {
                setFlash('error', '❌ حدث خطأ أثناء تعديل المنتج');
            }
            redirect('index.php?controller=admin&action=products');
        }
        
        $categories = $this->categoryModel->getAll();
        $suppliers = $this->supplierModel->getAll();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/product_form.php';
    }
    
    public function productDelete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0 && isset($_POST['id'])) {
            $id = intval($_POST['id']);
        }
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف المنتج غير صحيح');
            redirect('index.php?controller=admin&action=products');
        }
        
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            setFlash('error', '❌ المنتج غير موجود');
            redirect('index.php?controller=admin&action=products');
        }
        
        $result = $this->productModel->delete($id);
        
        if ($result) {
            setFlash('success', '✅ تم حذف المنتج "' . h($product['name']) . '" بنجاح');
        } else {
            setFlash('error', '❌ حدث خطأ أثناء حذف المنتج');
        }
        
        redirect('index.php?controller=admin&action=products');
    }
    
    public function getProduct() {
        header('Content-Type: application/json');
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID invalide']);
            return;
        }
        
        $product = $this->productModel->getById($id);
        if ($product) {
            echo json_encode(['success' => true, 'product' => $product]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Produit non trouvé']);
        }
    }
    
    // ========== GESTION DES UTILISATEURS ==========
    public function users() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $users = $this->userModel->getAll();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/users.php';
    }
    
    public function userCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($email) || empty($password)) {
                setFlash('error', '❌ جميع الحقول المطلوبة غير مكتملة');
                redirect('index.php?controller=admin&action=users');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setFlash('error', '❌ البريد الإلكتروني غير صالح');
                redirect('index.php?controller=admin&action=users');
            }
            
            if ($this->userModel->emailExists($email)) {
                setFlash('error', '❌ هذا البريد الإلكتروني مسجل بالفعل');
                redirect('index.php?controller=admin&action=users');
            }
            
            $result = $this->userModel->create($_POST);
            
            if ($result) {
                setFlash('success', '✅ تم إضافة المستخدم "' . h($name) . '" بنجاح');
            } else {
                setFlash('error', '❌ حدث خطأ أثناء إضافة المستخدم');
            }
            redirect('index.php?controller=admin&action=users');
        }
    }
    
    public function userUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role_id = intval($_POST['role_id'] ?? 2);
            $phone = $_POST['phone'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            
            if ($id <= 0 || empty($name) || empty($email)) {
                setFlash('error', '❌ البيانات غير مكتملة');
                redirect('index.php?controller=admin&action=users');
            }
            
            $result = $this->userModel->update($id, [
                'name' => $name,
                'email' => $email,
                'role_id' => $role_id,
                'phone' => $phone
            ]);
            
            if (!empty($new_password)) {
                $this->userModel->updatePassword($id, $new_password);
            }
            
            if ($result) {
                setFlash('success', '✅ تم تعديل المستخدم "' . h($name) . '" بنجاح');
            } else {
                setFlash('error', '❌ حدث خطأ أثناء تعديل المستخدم');
            }
            redirect('index.php?controller=admin&action=users');
        }
    }
    
    public function userDelete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف المستخدم غير صحيح');
            redirect('index.php?controller=admin&action=users');
        }
        
        if ($id == $_SESSION['user_id']) {
            setFlash('error', '❌ لا يمكنك حذف حسابك الخاص');
            redirect('index.php?controller=admin&action=users');
        }
        
        $user = $this->userModel->getById($id);
        $result = $this->userModel->toggleActive($id, 0);
        
        if ($result) {
            setFlash('success', '✅ تم تعطيل المستخدم "' . h($user['name']) . '" بنجاح');
        } else {
            setFlash('error', '❌ حدث خطأ أثناء تعطيل المستخدم');
        }
        redirect('index.php?controller=admin&action=users');
    }
    
    public function userToggle() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف المستخدم غير صحيح');
            redirect('index.php?controller=admin&action=users');
        }
        
        if ($id == $_SESSION['user_id']) {
            setFlash('error', '❌ لا يمكنك تعطيل حسابك الخاص');
            redirect('index.php?controller=admin&action=users');
        }
        
        $user = $this->userModel->getById($id);
        $newStatus = $user['is_active'] ? 0 : 1;
        $result = $this->userModel->toggleActive($id, $newStatus);
        
        if ($result) {
            $message = $newStatus ? 'تم تفعيل' : 'تم تعطيل';
            setFlash('success', '✅ ' . $message . ' المستخدم "' . h($user['name']) . '" بنجاح');
        } else {
            setFlash('error', '❌ حدث خطأ');
        }
        redirect('index.php?controller=admin&action=users');
    }
    
    // ========== STOCK ==========
    public function stock() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->productModel->getAll();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/stock.php';
    }
    
    // ========== ALERTES ==========
    public function alerts() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $this->alertModel->generateAllAlerts();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/alerts.php';
    }
    
    public function resolveAlert() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ معرف التنبيه غير صحيح');
            redirect('index.php?controller=admin&action=alerts');
        }
        
        $this->alertModel->resolveAlert($id);
        setFlash('success', '✅ تم حل التنبيه بنجاح');
        redirect('index.php?controller=admin&action=alerts');
    }
    
    // ========== RAPPORTS ==========
    public function reports() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/reports.php';
    }
    
    // 1. تقرير المبيعات
    public function exportSalesPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $data = $this->reportModel->getSalesByPeriod($startDate, $endDate);
        
        $this->showSalesReport($data);
    }
    
    public function exportSalesExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $data = $this->reportModel->getSalesByPeriod($startDate, $endDate);
        
        $filename = "rapport_ventes_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير المبيعات']);
        fputcsv($output, ["الفترة: $startDate إلى $endDate"]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'التاريخ', 'رقم الفاتورة', 'الصيدلي', 'المبلغ (د.ل)']);
        
        $i = 1;
        foreach ($data['data'] as $sale) {
            fputcsv($output, [
                $i++,
                $sale['sale_date'],
                $sale['id'],
                $sale['user_name'],
                $sale['total']
            ]);
        }
        
        fputcsv($output, []);
        fputcsv($output, ['الإجمالي', '', '', '', $data['total']]);
        
        fclose($output);
    }
    
    // 2. تقرير الأرباح
    public function exportProfitsPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $data = $this->reportModel->getProfitsByPeriod($startDate, $endDate);
        
        $this->showProfitsReport($data);
    }
    
    public function exportProfitsExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $data = $this->reportModel->getProfitsByPeriod($startDate, $endDate);
        
        $filename = "rapport_profits_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير الأرباح والخسائر']);
        fputcsv($output, ["الفترة: $startDate إلى $endDate"]);
        fputcsv($output, []);
        fputcsv($output, ['البيان', 'القيمة (د.ل)']);
        fputcsv($output, ['إجمالي المبيعات', $data['total_sales']]);
        fputcsv($output, ['تكلفة البضاعة المباعة', $data['total_cost']]);
        fputcsv($output, ['صافي الربح', $data['profit']]);
        fputcsv($output, ['هامش الربح (%)', number_format($data['margin'], 2)]);
        
        fclose($output);
    }
    
    // 3. تقرير المشتريات
    public function exportPurchasesPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $data = $this->reportModel->getPurchasesByPeriod($startDate, $endDate);
        
        $this->showPurchasesReport($data);
    }
    
    public function exportPurchasesExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $data = $this->reportModel->getPurchasesByPeriod($startDate, $endDate);
        
        $filename = "rapport_achats_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير المشتريات']);
        fputcsv($output, ["الفترة: $startDate إلى $endDate"]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'رقم الفاتورة', 'المورد', 'التاريخ', 'المبلغ (د.ل)', 'الحالة']);
        
        $i = 1;
        foreach ($data['data'] as $purchase) {
            fputcsv($output, [
                $i++,
                $purchase['invoice_number'],
                $purchase['supplier_name'],
                $purchase['invoice_date'],
                $purchase['total_amount'],
                $purchase['status'] == 'approved' ? 'معتمدة' : 'قيد الانتظار'
            ]);
        }
        
        fputcsv($output, []);
        fputcsv($output, ['الإجمالي', '', '', '', $data['total'], '']);
        
        fclose($output);
    }
    
    // 4. تقرير المنتجات
    public function exportProductsPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->reportModel->getAllProducts();
        $this->showProductsReport($products);
    }
    
    public function exportProductsExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->reportModel->getAllProducts();
        
        $filename = "rapport_produits_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير المنتجات']);
        fputcsv($output, ['تاريخ التوليد: ' . date('Y-m-d H:i')]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'الاسم', 'الرمز الشريطي', 'الفئة', 'المورد', 'سعر الشراء', 'سعر البيع', 'الكمية', 'تاريخ الصلاحية']);
        
        $i = 1;
        foreach ($products as $product) {
            fputcsv($output, [
                $i++,
                $product['name'],
                $product['barcode'],
                $product['category_name'],
                $product['supplier_name'],
                $product['purchase_price'],
                $product['selling_price'],
                $product['stock_quantity'],
                $product['expiry_date']
            ]);
        }
        
        fclose($output);
    }
    
    // 5. تقرير المخزون المنخفض
    public function exportLowStockPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->reportModel->getLowStockProducts();
        $this->showLowStockReport($products);
    }
    
    public function exportLowStockExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->reportModel->getLowStockProducts();
        
        $filename = "rapport_stock_faible_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير المنتجات منخفضة المخزون']);
        fputcsv($output, ['تاريخ التوليد: ' . date('Y-m-d H:i')]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'اسم المنتج', 'الكمية الحالية', 'حد التنبيه']);
        
        $i = 1;
        foreach ($products as $product) {
            fputcsv($output, [
                $i++,
                $product['name'],
                $product['quantity'],
                $product['alert_threshold']
            ]);
        }
        
        fclose($output);
    }
    
    // 6. تقرير الصلاحية
    public function exportExpiryPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->reportModel->getExpiryProducts();
        $this->showExpiryReport($products);
    }
    
    public function exportExpiryExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $products = $this->reportModel->getExpiryProducts();
        
        $filename = "rapport_peremption_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير الأدوية المنتهية أو القريبة من الانتهاء']);
        fputcsv($output, ['تاريخ التوليد: ' . date('Y-m-d H:i')]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'اسم المنتج', 'تاريخ الصلاحية', 'الحالة', 'الكمية']);
        
        $i = 1;
        foreach ($products as $product) {
            $status = $product['days_left'] <= 0 ? 'منتهي' : ($product['days_left'] <= 30 ? 'ينتهي قريباً' : 'ينتهي بعد ' . $product['days_left'] . ' يوماً');
            fputcsv($output, [
                $i++,
                $product['name'],
                $product['expiry_date'],
                $status,
                $product['quantity']
            ]);
        }
        
        fclose($output);
    }
    
    // 7. تقرير الموردين
    public function exportSuppliersPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $suppliers = $this->reportModel->getSuppliers();
        $this->showSuppliersReport($suppliers);
    }
    
    public function exportSuppliersExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $suppliers = $this->reportModel->getSuppliers();
        
        $filename = "rapport_fournisseurs_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير الموردين']);
        fputcsv($output, ['تاريخ التوليد: ' . date('Y-m-d H:i')]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'اسم المورد', 'الهاتف', 'البريد الإلكتروني', 'شخص الاتصال', 'عدد المنتجات']);
        
        $i = 1;
        foreach ($suppliers as $supplier) {
            fputcsv($output, [
                $i++,
                $supplier['name'],
                $supplier['phone'],
                $supplier['email'],
                $supplier['contact_person'],
                $supplier['products_count']
            ]);
        }
        
        fclose($output);
    }
    
    // 8. تقرير المستخدمين
    public function exportUsersPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $users = $this->reportModel->getUsers();
        $this->showUsersReport($users);
    }
    
    public function exportUsersExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $users = $this->reportModel->getUsers();
        
        $filename = "rapport_utilisateurs_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير المستخدمين']);
        fputcsv($output, ['تاريخ التوليد: ' . date('Y-m-d H:i')]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'الاسم', 'البريد الإلكتروني', 'الدور', 'رقم الهاتف', 'الحالة']);
        
        $i = 1;
        foreach ($users as $user) {
            $role = ($user['role_name'] == 'admin') ? 'مدير' : (($user['role_name'] == 'pharmacist') ? 'صيدلي' : 'محاسب');
            fputcsv($output, [
                $i++,
                $user['name'],
                $user['email'],
                $role,
                $user['phone'],
                $user['is_active'] ? 'نشط' : 'غير نشط'
            ]);
        }
        
        fclose($output);
    }
    
    // 9. تقرير التنبيهات
    public function exportAlertsPDF() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $alerts = $this->reportModel->getAlerts();
        $this->showAlertsReport($alerts);
    }
    
    public function exportAlertsExcel() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $alerts = $this->reportModel->getAlerts();
        
        $filename = "rapport_alertes_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['تقرير التنبيهات']);
        fputcsv($output, ['تاريخ التوليد: ' . date('Y-m-d H:i')]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'المنتج', 'نوع التنبيه', 'الرسالة', 'التاريخ']);
        
        $i = 1;
        foreach ($alerts as $alert) {
            $type = ($alert['type'] == 'stock_low') ? 'مخزون منخفض' : (($alert['type'] == 'expiry_critical') ? 'انتهاء وشيك' : 'تنبيه');
            fputcsv($output, [
                $i++,
                $alert['product_name'],
                $type,
                $alert['message'],
                $alert['created_at']
            ]);
        }
        
        fclose($output);
    }
    
    // ========== RAPPORTS HTML (AFFICHAGE) ==========
    
    private function showSalesReport($data) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>تقرير المبيعات - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()" style="padding:10px 20px;background:#2C3E66;color:white;border:none;border-radius:5px;">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()" style="padding:10px 20px;background:#6c757d;color:white;border:none;border-radius:5px;">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">تقرير المبيعات</h3>
            <p style="text-align:center">من <?= $data['start_date'] ?> إلى <?= $data['end_date'] ?></p>
            <table>
                <thead><tr><th>#</th><th>التاريخ</th><th>رقم الفاتورة</th><th>الصيدلي</th><th>المبلغ</th></tr></thead>
                <tbody><?php $i=1; foreach($data['data'] as $sale): ?>
                <tr><td><?= $i++ ?></td><td><?= $sale['sale_date'] ?></td><td>#<?= $sale['id'] ?></td><td><?= htmlspecialchars($sale['user_name']) ?></td><td><?= number_format($sale['total'], 2) ?> د.ل\n
                <?php endforeach; ?></tbody>
                <tfoot><tr style="background:#f0f0f0"><td colspan="4"><strong>الإجمالي</strong></td><td><strong><?= number_format($data['total'], 2) ?> د.ل</strong></td></table></tfoot>
            </table>
        </body>
        </html>
        <?php
    }
    
    private function showProfitsReport($data) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>تقرير الأرباح - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            .profit-box { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
            .profit-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ddd; }
            .profit-total { font-weight: bold; font-size: 18px; color: #28a745; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">تقرير الأرباح والخسائر</h3>
            <p style="text-align:center">من <?= $data['start_date'] ?> إلى <?= $data['end_date'] ?></p>
            <div class="profit-box">
                <div class="profit-item"><strong>إجمالي المبيعات :</strong><span><?= number_format($data['total_sales'], 2) ?> د.ل</span></div>
                <div class="profit-item"><strong>تكلفة البضاعة المباعة :</strong><span><?= number_format($data['total_cost'], 2) ?> د.ل</span></div>
                <div class="profit-item"><strong>صافي الربح :</strong><span class="profit-total"><?= number_format($data['profit'], 2) ?> د.ل</span></div>
                <div class="profit-item"><strong>هامش الربح :</strong><span><?= number_format($data['margin'], 2) ?> %</span></div>
            </div>
        </body>
        </html>
        <?php
    }
    
    private function showPurchasesReport($data) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>تقرير المشتريات - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">تقرير المشتريات</h3>
            <p style="text-align:center">من <?= $data['start_date'] ?> إلى <?= $data['end_date'] ?></p>
            <table>
                <thead><tr><th>#</th><th>رقم الفاتورة</th><th>المورد</th><th>التاريخ</th><th>المبلغ</th><th>الحالة</th></tr></thead>
                <tbody><?php $i=1; foreach($data['data'] as $p): ?>
                <tr><td style="text-align:center"><?= $i++ ?></td><td style="text-align:center"><?= htmlspecialchars($p['invoice_number']) ?></td><td style="text-align:center"><?= htmlspecialchars($p['supplier_name']) ?></td><td style="text-align:center"><?= $p['invoice_date'] ?></td><td style="text-align:center"><?= number_format($p['total_amount'], 2) ?> د.ل</td><td style="text-align:center"><?= $p['status'] == 'approved' ? 'معتمدة' : 'قيد الانتظار' ?></td></tr>
                <?php endforeach; ?></tbody>
                <tfoot><tr style="background:#f0f0f0"><td colspan="4"><strong>الإجمالي</strong></td><td><strong><?= number_format($data['total'], 2) ?> د.ل</strong></td><td></td></tr></tfoot>
            </table>
        </body>
        </html>
        <?php
    }
    
    private function showProductsReport($products) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>تقرير المنتجات - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
            th, td { border: 1px solid #ddd; padding: 6px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">تقرير المنتجات</h3>
            <p style="text-align:center">تاريخ التوليد: <?= date('Y-m-d H:i') ?></p>
            <table>
                <thead><tr><th>#</th><th>الاسم</th><th>الرمز</th><th>الفئة</th><th>المورد</th><th>سعر الشراء</th><th>سعر البيع</th><th>الكمية</th><th>الصلاحية</th></tr></thead>
                <tbody><?php $i=1; foreach($products as $p): ?>
                <tr><td style="text-align:center"><?= $i++ ?></td><td style="text-align:center"><?= htmlspecialchars($p['name']) ?></td><td style="text-align:center"><?= htmlspecialchars($p['barcode']) ?></td><td style="text-align:center"><?= htmlspecialchars($p['category_name']) ?></td><td style="text-align:center"><?= htmlspecialchars($p['supplier_name']) ?></td><td style="text-align:center"><?= $p['purchase_price'] ?> د.ل</td><td style="text-align:center"><?= $p['selling_price'] ?> د.ل</td><td style="text-align:center"><?= $p['stock_quantity'] ?></td><td style="text-align:center"><?= $p['expiry_date'] ?></td></tr>
                <?php endforeach; ?></tbody>
            </table>
        </body>
        </html>
        <?php
    }
    
    private function showLowStockReport($products) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>المخزون المنخفض - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">المنتجات منخفضة المخزون</h3>
            <table>
                <thead><tr><th>#</th><th>المنتج</th><th>الكمية الحالية</th><th>حد التنبيه</th></tr></thead>
                <tbody><?php $i=1; foreach($products as $p): ?>
                <tr><td style="text-align:center"><?= $i++ ?></td><td style="text-align:center"><?= htmlspecialchars($p['name']) ?></td><td style="text-align:center;color:red;font-weight:bold"><?= $p['quantity'] ?></td><td style="text-align:center"><?= $p['alert_threshold'] ?></td></tr>
                <?php endforeach; ?></tbody>
            </table>
        </body>
        </html>
        <?php
    }
    
    private function showExpiryReport($products) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>الأدوية المنتهية - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            .danger { color: red; font-weight: bold; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">الأدوية المنتهية أو القريبة من الانتهاء</h3>
            <table>
                <thead><tr><th>#</th><th>المنتج</th><th>تاريخ الصلاحية</th><th>الحالة</th><th>الكمية</th></tr></thead>
                <tbody><?php $i=1; foreach($products as $p): 
                    $class = ($p['days_left'] <= 0) ? 'danger' : '';
                    $status = ($p['days_left'] <= 0) ? 'منتهي' : (($p['days_left'] <= 30) ? 'ينتهي قريباً' : 'ينتهي بعد ' . $p['days_left'] . ' يوماً');
                ?>
                <tr><td style="text-align:center"><?= $i++ ?></td><td style="text-align:center"><?= htmlspecialchars($p['name']) ?></td><td style="text-align:center"><?= $p['expiry_date'] ?></td><td style="text-align:center" class="<?= $class ?>"><?= $status ?></td><td style="text-align:center"><?= $p['quantity'] ?></td></tr>
                <?php endforeach; ?></tbody>
            </table>
        </body>
        </html>
        <?php
    }
    
    private function showSuppliersReport($suppliers) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>تقرير الموردين - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">تقرير الموردين</h3>
            <table>
                <thead><tr><th>#</th><th>اسم المورد</th><th>الهاتف</th><th>البريد</th><th>شخص الاتصال</th><th>عدد المنتجات</th></tr></thead>
                <tbody><?php $i=1; foreach($suppliers as $s): ?>
                <tr><td style="text-align:center"><?= $i++ ?></td><td style="text-align:center"><?= htmlspecialchars($s['name']) ?></td><td style="text-align:center"><?= htmlspecialchars($s['phone']) ?></td><td style="text-align:center"><?= htmlspecialchars($s['email']) ?></td><td style="text-align:center"><?= htmlspecialchars($s['contact_person']) ?></td><td style="text-align:center"><?= $s['products_count'] ?></td></tr>
                <?php endforeach; ?></tbody>
            </table>
        </body>
        </html>
        <?php
    }
    
    private function showUsersReport($users) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>تقرير المستخدمين - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">تقرير المستخدمين</h3>
            <table>
                <thead><tr><th>#</th><th>الاسم</th><th>البريد</th><th>الدور</th><th>الهاتف</th><th>الحالة</th></tr></thead>
                <tbody><?php $i=1; foreach($users as $u): 
                    $role = ($u['role_name'] == 'admin') ? 'مدير' : (($u['role_name'] == 'pharmacist') ? 'صيدلي' : 'محاسب');
                ?>
                <tr><td style="text-align:center"><?= $i++ ?></td><td style="text-align:center"><?= htmlspecialchars($u['name']) ?></td><td style="text-align:center"><?= htmlspecialchars($u['email']) ?></td><td style="text-align:center"><?= $role ?></td><td style="text-align:center"><?= htmlspecialchars($u['phone']) ?></td><td style="text-align:center"><?= $u['is_active'] ? 'نشط' : 'غير نشط' ?></td></tr>
                <?php endforeach; ?></tbody>
            </table>
        </body>
        </html>
        <?php
    }
    
    private function showAlertsReport($alerts) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head><meta charset="UTF-8"><title>تقرير التنبيهات - فارما فلو</title>
        <style>
            body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
            h1 { color: #2C3E66; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #2C3E66; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head>
        <body>
            <div class="no-print" style="text-align:center;margin-bottom:20px;">
                <button onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()">✖️ إغلاق</button>
            </div>
            <h1>فارما فلو</h1>
            <h3 style="text-align:center">تقرير التنبيهات</h3>
            <table>
                <thead><tr><th>#</th><th>المنتج</th><th>نوع التنبيه</th><th>الرسالة</th><th>التاريخ</th></tr></thead>
                <tbody><?php $i=1; foreach($alerts as $a): 
                    $type = ($a['type'] == 'stock_low') ? 'مخزون منخفض' : (($a['type'] == 'expiry_critical') ? 'انتهاء وشيك' : 'تنبيه');
                ?>
                <tr><td style="text-align:center"><?= $i++ ?></td><td style="text-align:center"><?= htmlspecialchars($a['product_name']) ?></td><td style="text-align:center"><?= $type ?></td><td style="text-align:center"><?= htmlspecialchars($a['message']) ?></td><td style="text-align:center"><?= $a['created_at'] ?></tr>
                <?php endforeach; ?></tbody>
            </table>
        </body>
        </html>
        <?php
    }

        // ========== PARAMÈTRES ==========
    
    // Page des paramètres
    public function settings() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/admin/settings.php';
    }
    
    // Changer le mot de passe
    public function changePassword() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                setFlash('error', '❌ يرجى ملء جميع الحقول');
                redirect('index.php?controller=admin&action=settings');
            }
            
            if ($newPassword !== $confirmPassword) {
                setFlash('error', '❌ كلمة المرور الجديدة وتأكيدها غير متطابقين');
                redirect('index.php?controller=admin&action=settings');
            }
            
            if (strlen($newPassword) < 6) {
                setFlash('error', '❌ كلمة المرور الجديدة يجب أن تكون على الأقل 6 أحرف');
                redirect('index.php?controller=admin&action=settings');
            }
            
            $user = $this->userModel->getById($_SESSION['user_id']);
            
            if (!password_verify($currentPassword, $user['password'])) {
                setFlash('error', '❌ كلمة المرور الحالية غير صحيحة');
                redirect('index.php?controller=admin&action=settings');
            }
            
            $result = $this->userModel->updatePassword($_SESSION['user_id'], $newPassword);
            
            if ($result) {
                setFlash('success', '✅ تم تغيير كلمة المرور بنجاح');
            } else {
                setFlash('error', '❌ حدث خطأ أثناء تغيير كلمة المرور');
            }
            redirect('index.php?controller=admin&action=settings');
        }
    }
    
    // Sauvegarder la base de données
    public function backupDatabase() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $backupDir = __DIR__ . '/../backups/';
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        }
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupDir . $filename;
        
        // Commande mysqldump (XAMPP)
        $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'pharmaflow';
        
        $command = "\"$mysqldump\" --host=$dbhost --user=$dbuser --password=$dbpass $dbname > \"$filepath\"";
        
        exec($command, $output, $returnVar);
        
        if ($returnVar === 0 && file_exists($filepath)) {
            // Télécharger le fichier
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile($filepath);
            
            // Supprimer le fichier après téléchargement
            unlink($filepath);
        } else {
            setFlash('error', '❌ حدث خطأ أثناء إنشاء النسخة الاحتياطية');
            redirect('index.php?controller=admin&action=settings');
        }
    }
    
    // Nettoyer les alertes résolues
    public function cleanAlerts() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $result = $this->alertModel->cleanupOldResolved(0);
        
        if ($result) {
            setFlash('success', '✅ تم حذف جميع التنبيهات التي تم حلها');
        } else {
            setFlash('error', '❌ حدث خطأ أثناء تنظيف التنبيهات');
        }
        redirect('index.php?controller=admin&action=settings');
    }
    
    // Nettoyer les logs anciens
    public function cleanLogs() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            redirect('index.php?controller=auth&action=showLogin');
        }
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $result = $stmt->execute();
        
        if ($result) {
            setFlash('success', '✅ تم حذف سجل العمليات القديم (أكثر من 30 يوماً)');
        } else {
            setFlash('error', '❌ حدث خطأ أثناء تنظيف السجلات');
        }
        redirect('index.php?controller=admin&action=settings');
    }
}
?>