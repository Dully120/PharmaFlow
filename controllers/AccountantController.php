<?php
// controllers/AccountantController.php
require_once __DIR__ . '/../models/PurchaseInvoice.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../includes/functions.php';

class AccountantController {
    
    private $invoiceModel;
    private $productModel;
    private $supplierModel;
    private $alertModel;
    private $reportModel;
    
    public function __construct() {
        $this->invoiceModel = new PurchaseInvoice();
        $this->productModel = new Product();
        $this->supplierModel = new Supplier();
        $this->alertModel = new Alert();
        $this->reportModel = new Report();
    }
    
    private function checkAuth() {
        if (!isLoggedIn()) {
            redirect('index.php?controller=auth&action=showLogin');
        }
        if ($_SESSION['user_role'] !== 'accountant' && $_SESSION['user_role'] !== 'admin') {
            setFlash('error', '❌ غير مصرح لك بالوصول إلى هذه الصفحة');
            redirect('index.php?controller=dashboard&action=index');
        }
    }
    
    // ========== GESTION DES FACTURES ==========
    
    // Page principale - Factures
    public function invoices() {
        $this->checkAuth();
        
        $pending = $this->invoiceModel->getPending();
        $approved = $this->invoiceModel->getApproved();
        
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/accountant/invoices.php';
    }
    
    // Formulaire ajout facture
    public function createInvoice() {
        $this->checkAuth();
        
        $suppliers = $this->supplierModel->getAll();
        $products = $this->productModel->getAll();
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/accountant/invoice_form.php';
    }
    
    // Sauvegarder facture
    public function storeInvoice() {
        $this->checkAuth();
        
        $data = [
            'supplier_id' => $_POST['supplier_id'],
            'invoice_number' => $_POST['invoice_number'],
            'invoice_date' => $_POST['invoice_date'],
            'total_amount' => $_POST['total_amount']
        ];
        
        $items = [];
        for ($i = 0; $i < count($_POST['product_id']); $i++) {
            $items[] = [
                'product_id' => $_POST['product_id'][$i],
                'quantity' => $_POST['quantity'][$i],
                'purchase_price' => $_POST['purchase_price'][$i]
            ];
        }
        
        $result = $this->invoiceModel->create($data, $items);
        
        if ($result) {
            setFlash('success', '✅ Facture enregistrée en attente de validation');
        } else {
            setFlash('error', '❌ Erreur lors de l\'enregistrement');
        }
        
        redirect('index.php?controller=accountant&action=invoices');
    }
    
    // Voir les détails d'une facture
    public function viewInvoice() {
        $this->checkAuth();
        
        $id = $_GET['id'] ?? 0;
        $invoice = $this->invoiceModel->getById($id);
        
        if (!$invoice) {
            setFlash('error', '❌ Facture non trouvée');
            redirect('index.php?controller=accountant&action=invoices');
        }
        
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/accountant/invoice_view.php';
    }
    
    // Approuver une facture
    public function approve() {
        $this->checkAuth();
        
        $id = $_GET['id'] ?? 0;
        
        if ($id <= 0) {
            setFlash('error', '❌ Facture invalide');
            redirect('index.php?controller=accountant&action=invoices');
        }
        
        $result = $this->invoiceModel->approve($id);
        
        if ($result) {
            setFlash('success', '✅ Facture approuvée. Stock et prix mis à jour.');
        } else {
            setFlash('error', '❌ Erreur lors de l\'approbation');
        }
        
        redirect('index.php?controller=accountant&action=invoices');
    }
    
    // Rejeter une facture
    public function reject() {
        $this->checkAuth();
        
        $id = $_POST['id'] ?? 0;
        $reason = $_POST['reason'] ?? '';
        
        if ($id <= 0) {
            setFlash('error', '❌ Facture invalide');
            redirect('index.php?controller=accountant&action=invoices');
        }
        
        if (empty($reason)) {
            setFlash('error', '❌ Veuillez saisir un motif de rejet');
            redirect('index.php?controller=accountant&action=invoices');
        }
        
        $result = $this->invoiceModel->reject($id, $reason);
        
        if ($result) {
            setFlash('success', '✅ Facture rejetée');
        } else {
            setFlash('error', '❌ Erreur lors du rejet');
        }
        
        redirect('index.php?controller=accountant&action=invoices');
    }
    
    // ========== RAPPORTS ==========
    
    // Afficher la page des rapports
    public function reports() {
        $this->checkAuth();
        
        $alerts = $this->alertModel->getActiveAlerts();
        
        require_once __DIR__ . '/../views/accountant/reports.php';
    }
    
    // Export PDF (via HTML + impression navigateur)
    public function exportPDF() {
        $this->checkAuth();
        
        $type = $_GET['type'] ?? 'sales';
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        if ($type == 'sales') {
            $data = $this->reportModel->getSalesByPeriod($startDate, $endDate);
            $this->showSalesReport($data);
        } elseif ($type == 'profits') {
            $data = $this->reportModel->getProfitsByPeriod($startDate, $endDate);
            $this->showProfitsReport($data);
        } elseif ($type == 'purchases') {
            $data = $this->reportModel->getPurchasesByPeriod($startDate, $endDate);
            $this->showPurchasesReport($data);
        }
    }
    
    // Export Excel (CSV)
    public function exportExcel() {
        $this->checkAuth();
        
        $type = $_GET['type'] ?? 'sales';
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        if ($type == 'sales') {
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
            
        } elseif ($type == 'profits') {
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
            
        } elseif ($type == 'purchases') {
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
    }
    
    // ========== RAPPORTS (AFFICHAGE HTML) ==========
    
    // Afficher rapport ventes (HTML prêt à imprimer)
    private function showSalesReport($data) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>تقرير المبيعات - فارما فلو</title>
            <style>
                body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
                h1 { color: #2C3E66; text-align: center; }
                .header { text-align: center; margin-bottom: 20px; }
                .period { text-align: center; margin-bottom: 30px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #2C3E66; color: white; }
                .total { margin-top: 20px; text-align: left; font-weight: bold; font-size: 14px; }
                .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
                @media print { .no-print { display: none; } button { display: none; } }
            </style>
        </head>
        <body>
            <div class="no-print" style="text-align: center; margin-bottom: 20px;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #2C3E66; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    🖨️ طباعة / حفظ PDF
                </button>
                <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
                    ✖️ إغلاق
                </button>
            </div>
            
            <div class="header">
                <h1>فارما فلو</h1>
                <p>نظام إدارة الصيدليات</p>
            </div>
            <div class="period">
                <strong>تقرير المبيعات</strong><br>
                من <?= $data['start_date'] ?> إلى <?= $data['end_date'] ?>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>رقم الفاتورة</th>
                        <th>الصيدلي</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data['data'] as $sale): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($sale['sale_date'])) ?></td>
                        <td>#<?= $sale['id'] ?></td>
                        <td><?= htmlspecialchars($sale['user_name']) ?></td>
                        <td><?= number_format($sale['total'], 2) ?> د.ل</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="total">
                الإجمالي : <?= number_format($data['total'], 2) ?> د.ل
            </div>
            
            <div class="footer">
                تم التوليد بواسطة فارما فلو - <?= date('Y-m-d H:i') ?>
            </div>
        </body>
        </html>
        <?php
    }
    
    // Afficher rapport profits (HTML prêt à imprimer)
    private function showProfitsReport($data) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>تقرير الأرباح - فارما فلو</title>
            <style>
                body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
                h1 { color: #2C3E66; text-align: center; }
                .period { text-align: center; margin-bottom: 30px; }
                .profit-box { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
                .profit-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ddd; }
                .profit-total { font-weight: bold; font-size: 18px; color: #28a745; }
                .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
                @media print { .no-print { display: none; } button { display: none; } }
            </style>
        </head>
        <body>
            <div class="no-print" style="text-align: center; margin-bottom: 20px;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #2C3E66; color: white; border: none; border-radius: 5px; cursor: pointer;">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">✖️ إغلاق</button>
            </div>
            
            <div class="header">
                <h1>فارما فلو</h1>
                <p>نظام إدارة الصيدليات</p>
            </div>
            <div class="period">
                <strong>تقرير الأرباح والخسائر</strong><br>
                من <?= $data['start_date'] ?> إلى <?= $data['end_date'] ?>
            </div>
            
            <div class="profit-box">
                <div class="profit-item">
                    <strong>إجمالي المبيعات :</strong>
                    <span><?= number_format($data['total_sales'], 2) ?> د.ل</span>
                </div>
                <div class="profit-item">
                    <strong>تكلفة البضاعة المباعة :</strong>
                    <span><?= number_format($data['total_cost'], 2) ?> د.ل</span>
                </div>
                <div class="profit-item">
                    <strong>صافي الربح :</strong>
                    <span class="profit-total"><?= number_format($data['profit'], 2) ?> د.ل</span>
                </div>
                <div class="profit-item">
                    <strong>هامش الربح :</strong>
                    <span><?= number_format($data['margin'], 2) ?> %</span>
                </div>
            </div>
            
            <div class="footer">
                تم التوليد بواسطة فارما فلو - <?= date('Y-m-d H:i') ?>
            </div>
        </body>
        </html>
        <?php
    }
    
    // Afficher rapport achats (HTML prêt à imprimer)
    private function showPurchasesReport($data) {
        ?>
        <!DOCTYPE html>
        <html dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>تقرير المشتريات - فارما فلو</title>
            <style>
                body { font-family: Tahoma, Arial, sans-serif; margin: 20px; }
                h1 { color: #2C3E66; text-align: center; }
                .period { text-align: center; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #2C3E66; color: white; }
                .total { margin-top: 20px; text-align: left; font-weight: bold; }
                .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
                @media print { .no-print { display: none; } button { display: none; } }
            </style>
        </head>
        <body>
            <div class="no-print" style="text-align: center; margin-bottom: 20px;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #2C3E66; color: white; border: none; border-radius: 5px; cursor: pointer;">🖨️ طباعة / حفظ PDF</button>
                <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">✖️ إغلاق</button>
            </div>
            
            <div class="header">
                <h1>فارما فلو</h1>
                <p>نظام إدارة الصيدليات</p>
            </div>
            <div class="period">
                <strong>تقرير المشتريات</strong><br>
                من <?= $data['start_date'] ?> إلى <?= $data['end_date'] ?>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الفاتورة</th>
                        <th>المورد</th>
                        <th>التاريخ</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data['data'] as $purchase): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($purchase['invoice_number']) ?></td>
                        <td><?= htmlspecialchars($purchase['supplier_name']) ?></td>
                        <td><?= $purchase['invoice_date'] ?></td>
                        <td><?= number_format($purchase['total_amount'], 2) ?> د.ل</td>
                        <td><?= $purchase['status'] == 'approved' ? 'معتمدة' : 'قيد الانتظار' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="total">
                الإجمالي : <?= number_format($data['total'], 2) ?> د.ل
            </div>
            
            <div class="footer">
                تم التوليد بواسطة فارما فلو - <?= date('Y-m-d H:i') ?>
            </div>
        </body>
        </html>
        <?php
    }
}
?>