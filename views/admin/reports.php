<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
    <title>التقارير - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f9; font-family: 'Tahoma', 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        .navbar { background-color: #2C3E66; padding: 10px 15px; }
        .navbar-brand, .navbar .text-white { color: white !important; }
        .navbar-brand { font-weight: bold; font-size: 1.2rem; text-decoration: none; }
        .logout-btn { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 8px; font-size: 13px; text-decoration: none; }
        .user-name { color: rgba(255,255,255,0.9); font-size: 12px; background-color: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 20px; }
        .logout-btn { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 8px; font-size: 13px; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .logout-btn:hover { background-color: #c82333; color: white; }
        .user-name { color: rgba(255,255,255,0.9); font-size: 12px; background-color: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 20px; }
        .user-name i { margin-left: 6px; }
        
        .menu-toggle-btn { background-color: rgba(255,255,255,0.12); color: white; border: none; font-size: 24px; cursor: pointer; width: 42px; height: 42px; border-radius: 50%; display: none; align-items: center; justify-content: center; transition: background-color 0.3s, transform 0.3s; }
        .menu-toggle-btn:hover { background-color: rgba(255,255,255,0.2); transform: scale(1.05); }

        .sidebar { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); position: sticky; top: 20px; }
        .sidebar h5 { color: #2C3E66; border-bottom: 2px solid #e8f0fe; padding-bottom: 10px; margin-bottom: 15px; font-size: 16px; }
        .sidebar a { color: #2C3E66; text-decoration: none; display: block; padding: 10px 12px; border-radius: 10px; margin-bottom: 5px; transition: all 0.3s; font-size: 14px; }
        .sidebar a:hover { background-color: #e8f0fe; transform: translateX(-5px); }
        .sidebar a i { margin-left: 12px; width: 22px; text-align: center; }
        .sidebar a.active { background: linear-gradient(135deg, #2C3E66 0%, #1a2a4a 100%); color: white; }
        .sidebar .logout-sidebar { margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; color: #dc3545; }
        .badge-count {
            background-color: #dc3545;
            color: white;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
            float: left;
        }
        .content-card { background: white; border-radius: 15px; padding: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .filter-box { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .btn-pdf { background-color: #dc3545; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 12px; margin: 2px; }
        .btn-excel { background-color: #28a745; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 12px; margin: 2px; }
        .btn-pdf:hover, .btn-excel:hover { opacity: 0.85; color: white; }
        .report-icon { font-size: 40px; margin-bottom: 10px; }
        
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 997; }
        .sidebar-col-mobile { position: fixed; top: 0; right: -280px; width: 260px; height: 100vh; z-index: 998; transition: right 0.3s ease; padding: 0; }
        .sidebar-col-mobile .sidebar { border-radius: 0; height: 100%; overflow-y: auto; position: relative; top: 0; }
        .sidebar-col-mobile.open { right: 0; }
        .sidebar-overlay.open { display: block; }
        
        @media (max-width: 992px) {
            .sidebar-col-desktop { display: none; }
            .navbar-brand { font-size: 1rem; }
            .menu-toggle-btn { display: flex; }
            .logout-btn { display: none; }
        }
        @media (min-width: 993px) {
            .sidebar-col-mobile { display: none; }
            .menu-toggle-btn { display: none !important; }
            .user-name { display: inline-block !important; }
        }
        @media (max-width: 768px) {
            .content-card { padding: 10px; }
            h4 { font-size: 16px; }
            .btn-pdf, .btn-excel { padding: 4px 8px; font-size: 10px; }
            .filter-box { padding: 10px; }
            .report-icon { font-size: 30px; }
            .card-body { padding: 10px; }
            .card-body h5 { font-size: 14px; }
        }
    </style>
</head>
<body>

<!-- Overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar mobile -->
<div class="sidebar-col-mobile" id="mobileSidebar">
    <div class="sidebar">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-bars"></i> القائمة</h5>
            <button type="button" class="btn-close" id="closeSidebarBtn"></button>
        </div>
        <hr>
        <a href="index.php?controller=admin&action=dashboard"><i class="fas fa-tachometer-alt"></i> الرئيسية</a>
        <a href="index.php?controller=admin&action=products"><i class="fas fa-pills"></i> المنتجات</a>
        <a href="index.php?controller=admin&action=categories"><i class="fas fa-tags"></i> الفئات</a>
        <a href="index.php?controller=admin&action=suppliers"><i class="fas fa-truck"></i> الموردين</a>
        <a href="index.php?controller=admin&action=stock"><i class="fas fa-boxes"></i> المخزون</a>
        <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> المستخدمين</a>
        <a href="index.php?controller=admin&action=reports" class="active"><i class="fas fa-chart-line"></i> التقارير</a>
        <a href="index.php?controller=admin&action=settings"><i class="fas fa-cog"></i> الإعدادات</a>
        <a href="index.php?controller=admin&action=alerts">
            <i class="fas fa-bell"></i> التنبيهات
            <?php if (count($alerts) > 0): ?>
                        <span class="badge-count"><?= count($alerts) ?></span>
                    <?php endif; ?>
        </a>
        <hr>
        <a href="index.php?controller=auth&action=logout" class="logout-sidebar"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <button type="button" class="menu-toggle-btn" id="sidebarToggleBtn">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="#"><i class="fas fa-hospital-user"></i> فارما فلو</a>
        <div class="d-flex align-items-center">
            <span class="user-name me-3"><i class="fas fa-user-circle"></i> <?= h($_SESSION['user_name']) ?></span>
            <a href="index.php?controller=auth&action=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="row">
        
        <!-- Sidebar desktop -->
        <div class="col-md-3 sidebar-col-desktop">
            <div class="sidebar">
                <h5><i class="fas fa-bars"></i> القائمة الرئيسية</h5>
                <hr>
                <a href="index.php?controller=admin&action=dashboard"><i class="fas fa-tachometer-alt"></i> الرئيسية</a>
                <a href="index.php?controller=admin&action=products"><i class="fas fa-pills"></i> المنتجات</a>
                <a href="index.php?controller=admin&action=categories"><i class="fas fa-tags"></i> الفئات</a>
                <a href="index.php?controller=admin&action=suppliers"><i class="fas fa-truck"></i> الموردين</a>
                <a href="index.php?controller=admin&action=stock"><i class="fas fa-boxes"></i> المخزون</a>
                <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> المستخدمين</a>
                <a href="index.php?controller=admin&action=reports" class="active"><i class="fas fa-chart-line"></i> التقارير</a>
                <a href="index.php?controller=admin&action=settings"><i class="fas fa-cog"></i> الإعدادات</a>
                <a href="index.php?controller=admin&action=alerts">
                    <i class="fas fa-bell"></i> التنبيهات
                        <?php if (count($alerts) > 0): ?>
                            <span class="badge-count"><?= count($alerts) ?></span>
                        <?php endif; ?>
                    </a>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="col-md-9">
            <div class="content-card">
                <h4><i class="fas fa-chart-line"></i> التقارير</h4>
                <p class="text-muted small">اختر التقرير المطلوب ثم اضغط على PDF أو Excel للتصدير</p>
                
                <?= displayFlashMessages() ?>
                
                <!-- Cartes des rapports - Version complète -->
                <div class="row g-3">
                    <!-- 1. تقرير المبيعات -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-primary"><i class="fas fa-chart-line"></i></div>
                                <h5>تقرير المبيعات</h5>
                                <p class="small text-muted">إحصائيات المبيعات</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('sales', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('sales', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 2. تقرير الأرباح -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-success"><i class="fas fa-chart-pie"></i></div>
                                <h5>تقرير الأرباح</h5>
                                <p class="small text-muted">الأرباح والخسائر</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('profits', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('profits', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 3. تقرير المشتريات -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-warning"><i class="fas fa-file-invoice"></i></div>
                                <h5>تقرير المشتريات</h5>
                                <p class="small text-muted">فواتير الشراء</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('purchases', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('purchases', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 4. تقرير المنتجات -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-info"><i class="fas fa-pills"></i></div>
                                <h5>تقرير المنتجات</h5>
                                <p class="small text-muted">جميع المنتجات</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('products', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('products', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 5. تقرير المخزون المنخفض -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                                <h5>المخزون المنخفض</h5>
                                <p class="small text-muted">منتجات تحت الحد</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('lowstock', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('lowstock', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 6. تقرير الصلاحية -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-warning"><i class="fas fa-calendar-times"></i></div>
                                <h5>تقارير الصلاحية</h5>
                                <p class="small text-muted">أدوية منتهية أو قريبة</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('expiry', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('expiry', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 7. تقرير الموردين -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-secondary"><i class="fas fa-truck"></i></div>
                                <h5>تقرير الموردين</h5>
                                <p class="small text-muted">جميع الموردين</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('suppliers', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('suppliers', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 8. تقرير المستخدمين -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-dark"><i class="fas fa-users"></i></div>
                                <h5>تقرير المستخدمين</h5>
                                <p class="small text-muted">جميع المستخدمين</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('users', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('users', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 9. تقرير التنبيهات -->
                    <div class="col-md-4 col-sm-6">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-danger"><i class="fas fa-bell"></i></div>
                                <h5>تقرير التنبيهات</h5>
                                <p class="small text-muted">جميع التنبيهات النشطة</p>
                                <div>
                                    <button class="btn-pdf" onclick="exportReport('alerts', 'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button class="btn-excel" onclick="exportReport('alerts', 'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> 
                    <strong>طريقة الاستخدام :</strong>
                    <ul class="mb-0 mt-2">
                        <li>📄 <strong>PDF :</strong> يفتح التقرير في نافذة جديدة → <strong>Ctrl+P</strong> ثم اختيار "حفظ بتنسيق PDF"</li>
                        <li>📊 <strong>Excel :</strong> يتم تحميل التقرير بصيغة <strong>CSV</strong> (يفتح مباشرة في Excel)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar functions
(function() {
    const mobileSidebar = document.getElementById('mobileSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const overlay = document.getElementById('sidebarOverlay');
    const closeBtn = document.getElementById('closeSidebarBtn');
    
    function openSidebar() { mobileSidebar.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeSidebar() { mobileSidebar.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow = ''; }
    
    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
    
    window.addEventListener('resize', function() { if (window.innerWidth > 992) closeSidebar(); });
    
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', function() { if (window.innerWidth <= 992) setTimeout(closeSidebar, 200); });
    });
})();

// Exporter les rapports
function exportReport(type, format) {
    if (format === 'pdf') {
        let url = '';
        switch(type) {
            case 'sales': url = 'index.php?controller=admin&action=exportSalesPDF'; break;
            case 'profits': url = 'index.php?controller=admin&action=exportProfitsPDF'; break;
            case 'purchases': url = 'index.php?controller=admin&action=exportPurchasesPDF'; break;
            case 'products': url = 'index.php?controller=admin&action=exportProductsPDF'; break;
            case 'lowstock': url = 'index.php?controller=admin&action=exportLowStockPDF'; break;
            case 'expiry': url = 'index.php?controller=admin&action=exportExpiryPDF'; break;
            case 'suppliers': url = 'index.php?controller=admin&action=exportSuppliersPDF'; break;
            case 'users': url = 'index.php?controller=admin&action=exportUsersPDF'; break;
            case 'alerts': url = 'index.php?controller=admin&action=exportAlertsPDF'; break;
            default: return;
        }
        window.open(url, '_blank');
    } else {
        let url = '';
        switch(type) {
            case 'sales': url = 'index.php?controller=admin&action=exportSalesExcel'; break;
            case 'profits': url = 'index.php?controller=admin&action=exportProfitsExcel'; break;
            case 'purchases': url = 'index.php?controller=admin&action=exportPurchasesExcel'; break;
            case 'products': url = 'index.php?controller=admin&action=exportProductsExcel'; break;
            case 'lowstock': url = 'index.php?controller=admin&action=exportLowStockExcel'; break;
            case 'expiry': url = 'index.php?controller=admin&action=exportExpiryExcel'; break;
            case 'suppliers': url = 'index.php?controller=admin&action=exportSuppliersExcel'; break;
            case 'users': url = 'index.php?controller=admin&action=exportUsersExcel'; break;
            case 'alerts': url = 'index.php?controller=admin&action=exportAlertsExcel'; break;
            default: return;
        }
        window.location.href = url;
    }
}
</script>
</body>
</html>