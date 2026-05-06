<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
    <title>الإعدادات - فارما فلو</title>
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
        .content-card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .settings-group { margin-bottom: 25px; }
        .settings-group h5 { color: #2C3E66; border-right: 3px solid #2C3E66; padding-right: 10px; margin-bottom: 15px; }
        .info-text { color: #6c757d; font-size: 12px; margin-top: 5px; }
        
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 997; }
        .sidebar-col-mobile { position: fixed; top: 0; right: -280px; width: 260px; height: 100vh; z-index: 998; transition: right 0.3s ease; padding: 0; }
        .sidebar-col-mobile .sidebar { border-radius: 0; height: 100%; overflow-y: auto; position: relative; top: 0; }
        .sidebar-col-mobile.open { right: 0; }
        .sidebar-overlay.open { display: block; }
        
        .btn-change-password { background-color: #28a745; color: white; border: none; padding: 8px 20px; border-radius: 8px; transition: all 0.3s; }
        .btn-change-password:hover { background-color: #218838; transform: scale(1.02); color: white; }
        .btn-backup { background-color: #17a2b8; color: white; border: none; padding: 8px 20px; border-radius: 8px; transition: all 0.3s; }
        .btn-backup:hover { background-color: #138496; transform: scale(1.02); color: white; }
        .btn-clean { background-color: #ffc107; color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: all 0.3s; }
        .btn-clean:hover { background-color: #e0a800; transform: scale(1.02); color: #000; }
        
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
            .content-card { padding: 15px; }
            h4 { font-size: 18px; }
            .settings-group h5 { font-size: 14px; }
            .form-label { font-size: 13px; }
            .btn-change-password, .btn-backup, .btn-clean { font-size: 12px; padding: 6px 12px; }
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
        <a href="index.php?controller=admin&action=settings" class="active"><i class="fas fa-cog"></i> الإعدادات</a>
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
                <a href="index.php?controller=admin&action=products"><i class="fas fa-pills"></i> المنتجات</a>
                <a href="index.php?controller=admin&action=categories"><i class="fas fa-tags"></i> الفئات</a>
                <a href="index.php?controller=admin&action=suppliers"><i class="fas fa-truck"></i> الموردين</a>
                <a href="index.php?controller=admin&action=stock"><i class="fas fa-boxes"></i> المخزون</a>
                <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> المستخدمين</a>
                <a href="index.php?controller=admin&action=reports"><i class="fas fa-chart-line"></i> التقارير</a>
                <a href="index.php?controller=admin&action=settings" class="active"><i class="fas fa-cog"></i> الإعدادات</a>
                <a href="index.php?controller=admin&action=alerts">
                    <i class="fas fa-bell"></i> التنبيهات
                    <?php if (isset($alerts) && count($alerts) > 0): ?>
                        <span class="badge-count"><?= count($alerts) ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="col-md-9">
            <div class="content-card">
                <h4 class="mb-3"><i class="fas fa-cog"></i> الإعدادات</h4>
                
                <?= displayFlashMessages() ?>
                
                <!-- Section 1: تغيير كلمة المرور -->
                <div class="settings-group">
                    <h5><i class="fas fa-key"></i> تغيير كلمة المرور</h5>
                    <form method="POST" action="index.php?controller=admin&action=changePassword">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">كلمة المرور الحالية</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">تأكيد كلمة المرور</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-change-password"><i class="fas fa-save"></i> تغيير كلمة المرور</button>
                    </form>
                </div>
                
                <!-- Section 2: معلومات الصيدلية -->
                <div class="settings-group">
                    <h5><i class="fas fa-building"></i> معلومات الصيدلية</h5>
                    <div class="mb-3">
                        <label class="form-label">اسم الصيدلية</label>
                        <input type="text" class="form-control" value="فارما فلو - نظام إدارة الصيدليات" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" value="info@pharmaflow.com" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control" value="+218 00 000 0000" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <textarea class="form-control" rows="2" disabled>ليبيا - طرابلس</textarea>
                    </div>
                    <button class="btn btn-secondary" disabled><i class="fas fa-save"></i> حفظ المعلومات (قريباً)</button>
                </div>
                
                <!-- Section 3: النسخ الاحتياطي -->
                <div class="settings-group">
                    <h5><i class="fas fa-database"></i> النسخ الاحتياطي</h5>
                    <div class="mb-3">
                        <label class="form-label">آخر نسخة احتياطية</label>
                        <input type="text" class="form-control" value="<?php 
                            $backupDir = __DIR__ . '/../backups/';
                            $latestBackup = 'لم يتم عمل نسخة احتياطية بعد';
                            if (is_dir($backupDir)) {
                                $files = glob($backupDir . 'backup_*.sql');
                                if (!empty($files)) {
                                    $latestBackup = basename(max($files));
                                }
                            }
                            echo $latestBackup;
                        ?>" disabled>
                    </div>
                    <a href="index.php?controller=admin&action=backupDatabase" class="btn-backup"><i class="fas fa-download"></i> عمل نسخة احتياطية</a>
                </div>
                
                <!-- Section 4: تنظيف النظام -->
                <div class="settings-group">
                    <h5><i class="fas fa-trash-alt"></i> تنظيف النظام</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <a href="index.php?controller=admin&action=cleanAlerts" class="btn-clean w-100" onclick="return confirm('هل أنت متأكد من حذف جميع التنبيهات التي تم حلها؟')">
                                <i class="fas fa-brush"></i> تنظيف التنبيهات
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <a href="index.php?controller=admin&action=cleanLogs" class="btn-clean w-100" onclick="return confirm('هل أنت متأكد من حذف سجل العمليات القديم؟')">
                                <i class="fas fa-history"></i> تنظيف السجلات
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Section 5: معلومات النظام -->
                <div class="settings-group">
                    <h5><i class="fas fa-info-circle"></i> معلومات النظام</h5>
                    <div class="mb-3">
                        <label class="form-label">الإصدار الحالي</label>
                        <input type="text" class="form-control" value="PharmaFlow v1.0.0" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PHP Version</label>
                        <input type="text" class="form-control" value="<?= phpversion() ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ آخر تحديث</label>
                        <input type="text" class="form-control" value="<?= date('Y-m-d') ?>" disabled>
                    </div>
                </div>
                
                <!-- Section 6: المظهر -->
                <div class="settings-group">
                    <h5><i class="fas fa-palette"></i> المظهر</h5>
                    <div class="mb-3">
                        <label class="form-label">الوضع</label>
                        <select class="form-control" disabled>
                            <option>فاتح</option>
                            <option>داكن (قريباً)</option>
                        </select>
                    </div>
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
</script>
</body>
</html>