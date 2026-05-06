<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
    <title>التنبيهات - فارما فلو</title>
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
        
        .menu-toggle-btn { background-color: rgba(255,255,255,0.12); color: white; border: none; font-size: 22px; cursor: pointer; width: 38px; height: 38px; border-radius: 50%; display: none; align-items: center; justify-content: center; transition: all 0.3s; }
        .menu-toggle-btn:hover { background-color: rgba(255,255,255,0.2); }
        .logout-btn { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 8px; font-size: 13px; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .logout-btn:hover { background-color: #c82333; color: white; }
        .user-name { color: rgba(255,255,255,0.9); font-size: 12px; background-color: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 20px; }
        .user-name i { margin-left: 6px; }
        

        
        .sidebar { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); position: sticky; top: 20px; }
        .sidebar h5 { color: #2C3E66; border-bottom: 2px solid #e8f0fe; padding-bottom: 10px; margin-bottom: 15px; font-size: 16px; }
        .sidebar a { color: #2C3E66; text-decoration: none; display: block; padding: 10px 12px; border-radius: 10px; margin-bottom: 5px; transition: all 0.3s; font-size: 14px; }
        .sidebar a:hover { background-color: #e8f0fe; transform: translateX(-5px); }
        .sidebar a i { margin-left: 12px; width: 22px; text-align: center; }
        .sidebar a.active { background: linear-gradient(135deg, #2C3E66 0%, #1a2a4a 100%); color: white; }
        .sidebar .logout-sidebar { margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; color: #dc3545; }
        
        .content-card { background: white; border-radius: 15px; padding: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .alert-item { border-radius: 12px; padding: 18px; margin-bottom: 15px; border-right: 4px solid; }
        .alert-item .alert-item-text { max-width: 100%; }
        .alert-item .alert-item-text p { margin: 8px 0 0; line-height: 1.5; color: #3d3d3d; }
        .alert-danger-custom { background-color: #fff5f5; border-right-color: #dc3545; }
        .alert-warning-custom { background-color: #fffbf0; border-right-color: #ffc107; }
        .alert-info-custom { background-color: #f0f7ff; border-right-color: #17a2b8; }
        .btn-resolve { background-color: #28a745; color: white; border: none; padding: 10px 22px; border-radius: 8px; font-size: 14px; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-resolve:hover { background-color: #218838; color: white; }
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
            .menu-toggle-btn { display: inline-flex; }
            .sidebar-col-desktop { display: none; }
            .navbar-brand { font-size: 1rem; }
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
            .alert-item { padding: 10px; }
            .btn-resolve { padding: 3px 10px; font-size: 11px; }
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
        <a href="index.php?controller=admin&action=reports"><i class="fas fa-chart-line"></i> التقارير</a>
        <a href="index.php?controller=admin&action=settings"><i class="fas fa-cog"></i> الإعدادات</a>
        <a href="index.php?controller=admin&action=alerts" class="active">
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
        <button type="button" class="menu-toggle-btn" id="sidebarToggleBtn"><i class="fas fa-bars"></i></button>
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
                <a href="index.php?controller=admin&action=reports"><i class="fas fa-chart-line"></i> التقارير</a>
                <a href="index.php?controller=admin&action=settings"><i class="fas fa-cog"></i> الإعدادات</a>
                <a href="index.php?controller=admin&action=alerts" class="active">
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
                <h4 class="mb-3"><i class="fas fa-bell"></i> التنبيهات</h4>
                
                <?= displayFlashMessages() ?>
                
                <?php if (empty($alerts)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> لا توجد تنبيهات حالياً. كل شيء يعمل بشكل طبيعي!
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-bell"></i> لديك <strong><?= count($alerts) ?></strong> تنبيه(ات) بحاجة إلى اهتمامك.
                    </div>
                    
                    <?php 
                    $displayedProducts = [];
                    foreach ($alerts as $alert):
                        if (in_array($alert['product_id'], $displayedProducts)) continue;
                        $displayedProducts[] = $alert['product_id'];
                        
                        $alertClass = '';
                        if ($alert['type'] == 'expiry_critical' || $alert['type'] == 'expired') {
                            $alertClass = 'alert-danger-custom';
                        } elseif ($alert['type'] == 'stock_low') {
                            $alertClass = 'alert-warning-custom';
                        } else {
                            $alertClass = 'alert-info-custom';
                        }
                    ?>
                        <div class="alert-item <?= $alertClass ?>">
                            <div class="d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="alert-item-text mb-3">
                                    <strong><i class="fas fa-exclamation-circle"></i> <?= h($alert['product_name']) ?></strong>
                                    <p><?= h($alert['message']) ?></p>
                                </div>
                                <a href="index.php?controller=admin&action=resolveAlert&id=<?= $alert['id'] ?>" class="btn btn-resolve">
                                    <i class="fas fa-check"></i> تم الحل
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
</script>
</body>
</html>