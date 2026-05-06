<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
    <title>المستخدمين - فارما فلو</title>
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
        .search-input-wrapper { position: relative; width: 220px; max-width: 100%; }
        .search-input { border-radius: 20px; padding: 8px 40px 8px 15px; border: 1px solid #ddd; width: 100%; font-size: 14px; }
        .search-input:focus { outline: none; border-color: #2C3E66; }
        .search-input-icon { position: absolute; top: 50%; right: 10px; transform: translateY(-50%); color: #6c757d; font-size: 14px; pointer-events: none; }
        .search-control-row { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; width: auto; }
        .search-control-row .search-input-wrapper { flex: 1 1 220px; min-width: 0; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .btn-sm { padding: 5px 8px; font-size: 12px; }
        
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 997; }
        .sidebar-col-mobile { position: fixed; top: 0; right: -280px; width: 260px; height: 100vh; z-index: 998; transition: right 0.3s ease; padding: 0; }
        .sidebar-col-mobile .sidebar { border-radius: 0; height: 100%; overflow-y: auto; position: relative; top: 0; }
        .sidebar-col-mobile.open { right: 0; }
        .sidebar-overlay.open { display: block; }
        .badge-count {
            background-color: #dc3545;
            color: white;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
            float: left;
        }
        .badge-admin { background-color: #dc3545; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-pharmacist { background-color: #0d6efd; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-accountant { background-color: #198754; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-active { background-color: #198754; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-inactive { background-color: #6c757d; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; display: inline-block; }
        
        .modal-header { background-color: #2C3E66; color: white; border-radius: 10px 10px 0 0; }
        .modal-header .btn-close { filter: invert(1); }
        
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
            .search-control-row { flex-wrap: nowrap; width: 100%; justify-content: flex-end; }
            .search-input-wrapper { flex: 1 1 auto; min-width: 0; width: auto; }
            .search-input { width: 100%; padding-right: 45px; font-size: 11px; }
            .search-control-row button { flex: 0 0 auto; white-space: nowrap; }
            h4 { font-size: 16px; }
            .table { font-size: 11px; }
            .table th, .table td { padding: 5px 3px; }
            .btn-sm { padding: 3px 5px; font-size: 10px; }
            .badge-admin, .badge-pharmacist, .badge-accountant, .badge-active, .badge-inactive { font-size: 9px; padding: 2px 6px; }
        }
        @media (max-width: 576px) {
            .navbar { padding: 8px 10px; }
            .logout-btn { padding: 4px 8px; font-size: 11px; }
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
        <a href="index.php?controller=admin&action=users" class="active"><i class="fas fa-users"></i> المستخدمين</a>
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
                <a href="index.php?controller=admin&action=stock"><i class="fas fa-boxes"></i> المخزون</a>
                <a href="index.php?controller=admin&action=users" class="active"><i class="fas fa-users"></i> المستخدمين</a>
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
                    <h4 class="mb-0"><i class="fas fa-users"></i> المستخدمين</h4>
                    <div class="search-control-row">
                        <div class="search-input-wrapper">
                            <input type="text" id="searchInput" class="search-input form-control" placeholder="بحث...">
                            <span class="search-input-icon"><i class="fas fa-search"></i></span>
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus"></i> جديد</button>
                    </div>
                </div>
                
                <?= displayFlashMessages() ?>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الدور</th>
                                <th>الهاتف</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= $u['id'] ?></td>
                                    <td><?= h($u['name']) ?></td>
                                    <td><?= h($u['email']) ?></td>
                                    <td>
                                        <?php if($u['role_name'] == 'admin'): ?>
                                            <span class="badge-admin">مدير</span>
                                        <?php elseif($u['role_name'] == 'pharmacist'): ?>
                                            <span class="badge-pharmacist">صيدلي</span>
                                        <?php else: ?>
                                            <span class="badge-accountant">محاسب</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= h($u['phone']) ?></td>
                                    <td>
                                        <?php if($u['is_active']): ?>
                                            <span class="badge-active">نشط</span>
                                        <?php else: ?>
                                            <span class="badge-inactive">غير نشط</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <button class="btn btn-sm btn-warning" onclick='editUser(<?= $u['id'] ?>, "<?= addslashes($u['name']) ?>", "<?= addslashes($u['email']) ?>", <?= $u['role_id'] ?>, "<?= addslashes($u['phone']) ?>")'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="index.php?controller=admin&action=userDelete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php if($u['id'] != $_SESSION['user_id']): ?>
                                            <a href="index.php?controller=admin&action=userToggle&id=<?= $u['id'] ?>" class="btn btn-sm <?= $u['is_active'] ? 'btn-secondary' : 'btn-success' ?>">
                                                <?= $u['is_active'] ? '<i class="fas fa-ban"></i>' : '<i class="fas fa-check"></i>' ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد مستخدمين بعد</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> إضافة مستخدم</h5>
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
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modification Utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-edit"></i> تعديل مستخدم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=admin&action=userUpdate" id="editUserForm">
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" id="editUserName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الدور</label>
                        <select name="role_id" id="editUserRole" class="form-control">
                            <option value="2">صيدلي</option>
                            <option value="3">محاسب</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" name="phone" id="editUserPhone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" name="new_password" class="form-control" placeholder="اتركه فارغاً إذا كنت لا تريد التغيير">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
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
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        let name = row.cells[1]?.textContent.toLowerCase();
        let email = row.cells[2]?.textContent.toLowerCase();
        if (name.includes(val) || email.includes(val)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Edit user
function editUser(id, name, email, roleId, phone) {
    document.getElementById('editUserId').value = id;
    document.getElementById('editUserName').value = name;
    document.getElementById('editUserEmail').value = email;
    document.getElementById('editUserRole').value = roleId;
    document.getElementById('editUserPhone').value = phone || '';
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
</script>
</body>
</html>