<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
    <title>المنتجات - فارما فلو</title>
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
        .search-input-wrapper { position: relative; width: 220px; }
        .search-input { border-radius: 20px; padding: 8px 40px 8px 15px; border: 1px solid #ddd; width: 100%; font-size: 14px; }
        .search-input:focus { outline: none; border-color: #2C3E66; }
        .search-input-icon { position: absolute; top: 50%; right: 10px; transform: translateY(-50%); color: #6c757d; font-size: 14px; pointer-events: none; }
        .search-control-row { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .search-control-row .search-input-wrapper { flex: 1 1 220px; min-width: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .btn-sm { padding: 5px 8px; font-size: 12px; }
        
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 997; }
        .sidebar-col-mobile { position: fixed; top: 0; right: -280px; width: 260px; height: 100vh; z-index: 998; transition: right 0.3s ease; padding: 0; }
        .sidebar-col-mobile .sidebar { border-radius: 0; height: 100%; overflow-y: auto; position: relative; top: 0; }
        .sidebar-col-mobile.open { right: 0; }
        .sidebar-overlay.open { display: block; }
        
        .modal-header { background-color: #2C3E66; color: white; border-radius: 10px 10px 0 0; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-footer .btn-cancel { background-color: #6c757d; color: white; border: none; padding: 6px 20px; border-radius: 8px; }
        .modal-footer .btn-save { background-color: #28a745; color: white; border: none; padding: 6px 20px; border-radius: 8px; }
        
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
            .search-control-row { flex-wrap: nowrap; }
            .search-input-wrapper { flex: 1 1 auto; min-width: 0; }
            .search-input { width: 100%; padding-right: 45px; font-size: 11px; }
            h4 { font-size: 16px; }
            .table { font-size: 11px; }
            .table th, .table td { padding: 5px 3px; }
            .btn-sm { padding: 3px 5px; font-size: 10px; }
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
        <a href="index.php?controller=admin&action=products" class="active"><i class="fas fa-pills"></i> المنتجات</a>
        <a href="index.php?controller=admin&action=categories"><i class="fas fa-tags"></i> الفئات</a>
        <a href="index.php?controller=admin&action=suppliers"><i class="fas fa-truck"></i> الموردين</a>
        <a href="index.php?controller=admin&action=stock"><i class="fas fa-boxes"></i> المخزون</a>
        <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> المستخدمين</a>
        <a href="index.php?controller=admin&action=reports"><i class="fas fa-chart-line"></i> التقارير</a>
        <a href="index.php?controller=admin&action=settings"><i class="fas fa-cog"></i> الإعدادات</a>
        <a href="index.php?controller=admin&action=alerts">
    <i class="fas fa-bell"></i> التنبيهات
    <?php if (isset($alerts) && count($alerts) > 0): ?>
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
                <a href="index.php?controller=admin&action=products" class="active"><i class="fas fa-pills"></i> المنتجات</a>
                <a href="index.php?controller=admin&action=categories"><i class="fas fa-tags"></i> الفئات</a>
                <a href="index.php?controller=admin&action=suppliers"><i class="fas fa-truck"></i> الموردين</a>
                <a href="index.php?controller=admin&action=stock"><i class="fas fa-boxes"></i> المخزون</a>
                <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> المستخدمين</a>
                <a href="index.php?controller=admin&action=reports"><i class="fas fa-chart-line"></i> التقارير</a>
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
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h4 class="mb-0"><i class="fas fa-pills"></i> المنتجات</h4>
                    <div class="search-control-row">
                        <div class="search-input-wrapper">
                            <input type="text" id="searchInput" class="search-input form-control" placeholder="بحث...">
                            <span class="search-input-icon"><i class="fas fa-search"></i></span>
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus"></i> جديد</button>
                    </div>
                </div>
                
                <?= displayFlashMessages() ?>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="productsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الرمز الشريطي</th>
                                <th>اسم المنتج</th>
                                <th class="d-none d-md-table-cell">الفئة</th>
                                <th class="d-none d-md-table-cell">سعر الشراء</th>
                                <th>سعر البيع</th>
                                <th>الكمية</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= h($p['barcode']) ?></td>
                                <td><?= h($p['name']) ?></td>
                                <td class="d-none d-md-table-cell"><?= h($p['category_name']) ?></td>
                                <td class="d-none d-md-table-cell"><?= number_format($p['purchase_price'], 2) ?> د.ل</td>
                                <td class="<?= ($p['stock_quantity'] ?? 0) <= ($p['alert_threshold'] ?? 10) ? 'text-danger fw-bold' : '' ?>">
                                    <?= number_format($p['selling_price'], 2) ?> د.ل
                                </td>
                                <td class="<?= ($p['stock_quantity'] ?? 0) <= ($p['alert_threshold'] ?? 10) ? 'text-danger fw-bold' : '' ?>">
                                    <?= $p['stock_quantity'] ?? 0 ?>
                                </td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-warning" onclick="openEditProductModal(<?= $p['id'] ?>)"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="index.php?controller=admin&action=productDelete" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Produit -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> إضافة منتج جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=admin&action=productCreate" id="addProductForm">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الرمز الشريطي</label>
                            <input type="text" name="barcode" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم العلمي</label>
                            <input type="text" name="scientific_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الفئة</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- اختر فئة --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= h($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المورد</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">-- اختر مورد --</option>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= h($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الجرعة</label>
                            <input type="text" name="dosage" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الشكل الصيدلاني</label>
                            <input type="text" name="form" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="purchase_price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سعر البيع <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="selling_price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نسبة الضريبة (%)</label>
                            <input type="number" step="0.01" name="tax_rate" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حد التنبيه</label>
                            <input type="number" name="alert_threshold" class="form-control" value="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">وحدة البيع</label>
                            <input type="text" name="unit" class="form-control" value="boîte">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الكمية الأولية</label>
                            <input type="number" name="initial_quantity" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الدفعة</label>
                            <input type="text" name="batch_number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الصلاحية</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">موقع التخزين</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-save">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modification Produit -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> تعديل منتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=admin&action=productEdit" id="editProductForm">
                <input type="hidden" name="id" id="editProductId">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الرمز الشريطي</label>
                            <input type="text" name="barcode" id="editBarcode" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم العلمي</label>
                            <input type="text" name="scientific_name" id="editScientificName" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الفئة</label>
                            <select name="category_id" id="editCategory" class="form-control">
                                <option value="">-- اختر فئة --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= h($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المورد</label>
                            <select name="supplier_id" id="editSupplier" class="form-control">
                                <option value="">-- اختر مورد --</option>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= h($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الجرعة</label>
                            <input type="text" name="dosage" id="editDosage" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الشكل الصيدلاني</label>
                            <input type="text" name="form" id="editForm" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="purchase_price" id="editPurchasePrice" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سعر البيع <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="selling_price" id="editSellingPrice" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نسبة الضريبة (%)</label>
                            <input type="number" step="0.01" name="tax_rate" id="editTaxRate" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حد التنبيه</label>
                            <input type="number" name="alert_threshold" id="editAlertThreshold" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">وحدة البيع</label>
                            <input type="text" name="unit" id="editUnit" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn-save">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar
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

// Search
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    let val = this.value.toLowerCase();
    document.querySelectorAll('#productsTable tbody tr').forEach(row => {
        let name = row.cells[2]?.textContent.toLowerCase();
        row.style.display = name.includes(val) ? '' : 'none';
    });
});

// Open edit modal - récupère les données via AJAX
function openEditProductModal(id) {
    fetch('index.php?controller=admin&action=getProduct&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editProductId').value = data.product.id;
                document.getElementById('editBarcode').value = data.product.barcode || '';
                document.getElementById('editName').value = data.product.name;
                document.getElementById('editScientificName').value = data.product.scientific_name || '';
                document.getElementById('editCategory').value = data.product.category_id || '';
                document.getElementById('editSupplier').value = data.product.supplier_id || '';
                document.getElementById('editDosage').value = data.product.dosage || '';
                document.getElementById('editForm').value = data.product.form || '';
                document.getElementById('editPurchasePrice').value = data.product.purchase_price;
                document.getElementById('editSellingPrice').value = data.product.selling_price;
                document.getElementById('editTaxRate').value = data.product.tax_rate || 0;
                document.getElementById('editAlertThreshold').value = data.product.alert_threshold || 10;
                document.getElementById('editUnit').value = data.product.unit || 'boîte';
                new bootstrap.Modal(document.getElementById('editProductModal')).show();
            } else {
                alert('Erreur lors du chargement du produit');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur de chargement');
        });
}
</script>
</body>
</html>