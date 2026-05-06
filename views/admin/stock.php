<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
    <title>المخزون - فارما فلو</title>
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
        
        .content-card { background: white; border-radius: 15px; padding: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .search-input-wrapper { position: relative; width: 220px; }
        .search-input { border-radius: 20px; padding: 8px 40px 8px 15px; border: 1px solid #ddd; width: 100%; font-size: 14px; }
        .search-input:focus { outline: none; border-color: #2C3E66; }
        .search-input-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
            pointer-events: none;
        }
        .table th, .table td { text-align: center; vertical-align: middle; }
        @media (max-width: 768px) {
            .search-input-wrapper { width: 100%; }
            .search-input { padding-right: 45px; }
            .search-input-icon { right: 10px; }
        }
        .badge-stock-good { background-color: #198754; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-stock-low { background-color: #ffc107; color: #000; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-stock-out { background-color: #dc3545; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-expiry-soon { background-color: #fd7e14; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-expiry-critical { background-color: #dc3545; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-count {
            background-color: #dc3545;
            color: white;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
            float: left;
        }
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
            .search-input { width: 130px; font-size: 11px; }
            h4 { font-size: 16px; }
            .table { font-size: 11px; }
            .table th, .table td { padding: 5px 3px; }
            .badge-stock-good, .badge-stock-low, .badge-stock-out, .badge-expiry-soon, .badge-expiry-critical { font-size: 9px; padding: 2px 6px; }
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
        <a href="index.php?controller=admin&action=stock" class="active"><i class="fas fa-boxes"></i> المخزون</a>
        <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> المستخدمين</a>
        <a href="index.php?controller=admin&action=reports"><i class="fas fa-chart-line"></i> التقارير</a>
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
                <a href="index.php?controller=admin&action=stock" class="active"><i class="fas fa-boxes"></i> المخزون</a>
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
                    <h4 class="mb-0"><i class="fas fa-boxes"></i> المخزون</h4>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="search-input-wrapper">
                            <input type="text" id="searchInput" class="search-input form-control" placeholder="بحث...">
                            <span class="search-input-icon"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
                
                <?= displayFlashMessages() ?>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle" id="stockTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>تاريخ الصلاحية</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($products as $p): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= h($p['name']) ?></td>
                                <td class="<?= ($p['stock_quantity'] ?? 0) <= ($p['alert_threshold'] ?? 10) ? 'text-danger fw-bold' : '' ?>">
                                    <?= $p['stock_quantity'] ?? 0 ?>
                                </td>
                                <td><?= $p['expiry_date'] ?? 'غير محدد' ?></td>
                                <td>
                                    <?php 
                                        $stock = $p['stock_quantity'] ?? 0;
                                        $threshold = $p['alert_threshold'] ?? 10;
                                        
                                        if ($stock <= 0) {
                                            echo '<span class="badge-stock-out">غير متوفر</span>';
                                        } elseif ($stock <= $threshold) {
                                            echo '<span class="badge-stock-low">مخزون منخفض</span>';
                                        } else {
                                            echo '<span class="badge-stock-good">جيد</span>';
                                        }
                                        
                                        // Vérifier la péremption
                                        if ($p['expiry_date']) {
                                            $daysLeft = (strtotime($p['expiry_date']) - time()) / (60 * 60 * 24);
                                            if ($daysLeft <= 30 && $daysLeft > 0) {
                                                echo ' <span class="badge-expiry-critical">ينتهي قريباً! (' . floor($daysLeft) . ' يوماً)</span>';
                                            } elseif ($daysLeft <= 180 && $daysLeft > 0) {
                                                echo ' <span class="badge-expiry-soon">ينتهي بعد ' . floor($daysLeft) . ' يوماً</span>';
                                            }
                                        }
                                    ?>
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
    document.querySelectorAll('#stockTable tbody tr').forEach(row => {
        let name = row.cells[1]?.textContent.toLowerCase();
        if (name.includes(val)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
</body>
</html>