<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #f4f6f9;
            font-family: 'Tahoma', 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar {
            background-color: #2C3E66;
            padding: 10px 20px;
        }
        .navbar-brand, .navbar .text-white { color: white !important; }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            text-decoration: none;
        }
        .logout-btn { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 8px; font-size: 13px; text-decoration: none; }
        .logout-btn:hover {
            background-color: #c82333;
            transform: scale(1.02);
            color: white;
        }
        .user-name { color: rgba(255,255,255,0.9); font-size: 14px; background-color: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 20px; }
        .user-name i {
            margin-left: 8px;
        }
        
        .menu-toggle-btn { background-color: rgba(255,255,255,0.12); color: white; border: none; font-size: 24px; cursor: pointer; width: 42px; height: 42px; border-radius: 50%; display: none; align-items: center; justify-content: center; transition: background-color 0.3s, transform 0.3s; }
        .menu-toggle-btn:hover { background-color: rgba(255,255,255,0.2); transform: scale(1.05); }
        .mobile-logout {
            display: none;
        }
        
        /* Sidebar - Desktop */
        .sidebar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 20px;
        }
        .sidebar h5 {
            color: #2C3E66;
            border-bottom: 2px solid #e8f0fe;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .sidebar a {
            color: #2C3E66;
            text-decoration: none;
            display: block;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.3s;
            font-size: 14px;
        }
        .sidebar a:hover {
            background-color: #e8f0fe;
            transform: translateX(-5px);
        }
        .sidebar a i {
            margin-left: 12px;
            width: 22px;
            text-align: center;
        }
        .sidebar a.active {
            background: linear-gradient(135deg, #2C3E66 0%, #1a2a4a 100%);
            color: white;
        }
        .sidebar a.active i {
            color: white;
        }
        .sidebar .logout-sidebar {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 15px;
            color: #dc3545;
        }
        .sidebar .logout-sidebar:hover {
            background-color: #fff5f5;
        }
        .badge-count {
            background-color: #dc3545;
            color: white;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
            float: left;
        }
        
        /* Cartes statistiques */
        .stat-card {
            text-align: center;
            padding: 20px 15px;
            background: linear-gradient(135deg, #2C3E66 0%, #1a2a4a 100%);
            color: white;
            border-radius: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-top: 8px;
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        /* Cartes de contenu */
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        .content-card h5 {
            color: #2C3E66;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: bold;
        }
        .content-card h5 i {
            margin-left: 8px;
        }
        
        /* Alertes */
        .alert-item {
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 12px;
            border-right: 4px solid;
        }
        .alert-danger-custom {
            background-color: #fff5f5;
            border-right-color: #dc3545;
        }
        .alert-warning-custom {
            background-color: #fffbf0;
            border-right-color: #ffc107;
        }
        .alert-info-custom {
            background-color: #f0f7ff;
            border-right-color: #17a2b8;
        }
        .btn-resolve {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .btn-resolve:hover {
            background-color: #218838;
            transform: scale(1.02);
            color: white;
        }
        
        /* Overlay mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 997;
        }
        
        /* Sidebar mobile */
        .sidebar-col-mobile {
            position: fixed;
            top: 0;
            right: -280px;
            width: 260px;
            height: 100vh;
            z-index: 998;
            transition: right 0.3s ease;
            padding: 0;
        }
        .sidebar-col-mobile .sidebar {
            border-radius: 0;
            height: 100%;
            overflow-y: auto;
            position: relative;
            top: 0;
        }
        .sidebar-col-mobile.open {
            right: 0;
        }
        .sidebar-overlay.open {
            display: block;
        }
        
        /* Actions rapides */
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .quick-btn {
            flex: 1;
            min-width: 120px;
            padding: 8px;
            border-radius: 10px;
            font-size: 13px;
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
        }
        .quick-btn i {
            margin-left: 8px;
        }
        
        /* Responsive - Tablette et mobile */
        @media (max-width: 992px) {
            .sidebar-col-desktop {
                display: none;
            }
            .stat-number {
                font-size: 24px;
            }
            .stat-card {
                padding: 12px;
            }
            .user-name {
                font-size: 12px;
                padding: 4px 10px;
            }
            .navbar-brand {
                font-size: 1rem;
            }
            .mobile-logout {
                display: block;
            }
            .menu-toggle-btn {
                display: flex;
            }
            .logout-btn {
                display: none;
            }
        }
        
        @media (min-width: 993px) {
            .sidebar-col-mobile {
                display: none;
            }
            .menu-toggle-btn {
                display: none !important;
            }
        }
        
        @media (max-width: 576px) {
            .quick-btn {
                min-width: calc(50% - 5px);
                font-size: 11px;
            }
            .stat-number {
                font-size: 18px;
            }
            .stat-label {
                font-size: 10px;
            }
            .stat-card i {
                font-size: 20px;
            }
        }
        @media (max-width: 576px) {
            .alert-item .btn-resolve {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar - Version Mobile (cachée par défaut) -->
<div class="sidebar-col-mobile" id="mobileSidebar">
    <div class="sidebar">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-bars"></i> القائمة</h5>
            <button type="button" class="btn-close" id="closeSidebarBtn"></button>
        </div>
        <hr>
        <a href="index.php?controller=admin&action=dashboard" class="active">
            <i class="fas fa-tachometer-alt"></i> الرئيسية
        </a>
        <a href="index.php?controller=admin&action=products">
            <i class="fas fa-pills"></i> المنتجات
        </a>
        <a href="index.php?controller=admin&action=categories">
            <i class="fas fa-tags"></i> الفئات
        </a>
        <a href="index.php?controller=admin&action=suppliers">
            <i class="fas fa-truck"></i> الموردين
        </a>
        <a href="index.php?controller=admin&action=stock">
            <i class="fas fa-boxes"></i> المخزون
        </a>
        <a href="index.php?controller=admin&action=users">
            <i class="fas fa-users"></i> المستخدمين
        </a>
        <a href="index.php?controller=admin&action=reports">
            <i class="fas fa-chart-line"></i> التقارير
        </a>
        <a href="index.php?controller=admin&action=settings">
            <i class="fas fa-cog"></i> الإعدادات
        </a>
        <a href="index.php?controller=admin&action=alerts">
            <i class="fas fa-bell"></i> التنبيهات
            <?php if (count($alerts) > 0): ?>
                <span class="badge-count"><?= count($alerts) ?></span>
            <?php endif; ?>
        </a>
        
        <!-- Logout dans le menu mobile -->
        <hr>
        <a href="index.php?controller=auth&action=logout" class="logout-sidebar">
            <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
        </a>
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

<div class="container-fluid mt-4">
    <div class="row">
        
        <!-- Sidebar - Version Desktop (visible sur grand écran) -->
        <div class="col-md-3 sidebar-col-desktop">
            <div class="sidebar">
                <h5><i class="fas fa-bars"></i> القائمة الرئيسية</h5>
                <hr>
                <a href="index.php?controller=admin&action=dashboard" class="active">
                    <i class="fas fa-tachometer-alt"></i> الرئيسية
                </a>
                <a href="index.php?controller=admin&action=products">
                    <i class="fas fa-pills"></i> المنتجات
                </a>
                <a href="index.php?controller=admin&action=categories">
                    <i class="fas fa-tags"></i> الفئات
                </a>
                <a href="index.php?controller=admin&action=suppliers">
                    <i class="fas fa-truck"></i> الموردين
                </a>
                <a href="index.php?controller=admin&action=stock">
                    <i class="fas fa-boxes"></i> المخزون
                </a>
                <a href="index.php?controller=admin&action=users">
                    <i class="fas fa-users"></i> المستخدمين
                </a>
                <a href="index.php?controller=admin&action=reports">
                    <i class="fas fa-chart-line"></i> التقارير
                </a>
                <a href="index.php?controller=admin&action=settings">
                    <i class="fas fa-cog"></i> الإعدادات
                </a>
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
            <h4 class="mb-3"><i class="fas fa-chart-line"></i> لوحة التحكم</h4>
            
            <?= displayFlashMessages() ?>
            
            <!-- Cartes statistiques -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-pills fa-2x"></i>
                        <div class="stat-number"><?= count($products) ?></div>
                        <div class="stat-label">المنتجات</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-users fa-2x"></i>
                        <div class="stat-number"><?= count($users) ?></div>
                        <div class="stat-label">المستخدمين</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-truck fa-2x"></i>
                        <div class="stat-number"><?= count($suppliers) ?></div>
                        <div class="stat-label">الموردين</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-bell fa-2x"></i>
                        <div class="stat-number"><?= count($alerts) ?></div>
                        <div class="stat-label">التنبيهات</div>
                    </div>
                </div>
            </div>
            
            <!-- Section Alertes -->
            <?php if (count($alerts) > 0): ?>
            <div class="content-card">
                <h5><i class="fas fa-exclamation-triangle text-warning"></i> التنبيهات النشطة</h5>
                <hr>
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
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                            <div class="mb-2 mb-sm-0">
                                <strong><i class="fas fa-exclamation-circle"></i> <?= h($alert['product_name']) ?></strong>
                                <span class="mx-2 d-none d-sm-inline">-</span>
                                <br class="d-sm-none">
                                <small><?= h($alert['message']) ?></small>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-sm-end align-items-center w-100">
                                <a href="index.php?controller=admin&action=resolveAlert&id=<?= $alert['id'] ?>" 
                                   class="btn-resolve btn-sm mt-2">
                                    <i class="fas fa-check"></i>  تم الحل 
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- Actions rapides -->
            <div class="content-card">
                <h5><i class="fas fa-bolt"></i> إجراءات سريعة</h5>
                <hr>
                <div class="quick-actions">
                    <a href="index.php?controller=admin&action=productCreate" class="btn btn-outline-primary quick-btn">
                        <i class="fas fa-plus"></i> إضافة منتج
                    </a>
                    <button class="btn btn-outline-primary quick-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-user-plus"></i> إضافة مستخدم
                    </button>
                    <a href="index.php?controller=admin&action=categoryCreate" class="btn btn-outline-primary quick-btn">
                        <i class="fas fa-plus"></i> إضافة فئة
                    </a>
                    <a href="index.php?controller=admin&action=supplierCreate" class="btn btn-outline-primary quick-btn">
                        <i class="fas fa-plus"></i> إضافة مورد
                    </a>
                    <a href="index.php?controller=admin&action=alerts" class="btn btn-outline-warning quick-btn">
                        <i class="fas fa-bell"></i> عرض التنبيهات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ajout utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> إضافة مستخدم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=admin&action=userCreate">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الدور</label>
                        <select name="role_id" class="form-control">
                            <option value="2">صيدلي</option>
                            <option value="3">محاسب</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary btn-sm">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    const mobileSidebar = document.getElementById('mobileSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const overlay = document.getElementById('sidebarOverlay');
    const closeBtn = document.getElementById('closeSidebarBtn');
    
    function openSidebar() {
        mobileSidebar.classList.add('open');
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
        mobileSidebar.classList.remove('open');
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    }
    
    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) closeSidebar();
    });
    
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992) setTimeout(closeSidebar, 200);
        });
    });
})();
</script>
</body>
</html>