<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Tahoma', sans-serif; }
        .navbar { background-color: #2C3E66; padding: 10px 15px; }
        .navbar-brand, .navbar .text-white { color: white !important; }
        .sidebar { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .sidebar a { color: #2C3E66; text-decoration: none; display: block; padding: 10px; border-radius: 10px; margin-bottom: 5px; }
        .sidebar a:hover { background-color: #e8f0fe; }
        .sidebar a i { margin-left: 10px; width: 25px; }
        .sidebar a.active { background: linear-gradient(135deg, #2C3E66 0%, #1a2a4a 100%); color: white; }
        .content-card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .filter-box { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .btn-pdf { background-color: #dc3545; color: white; border: none; padding: 8px 20px; border-radius: 8px; transition: opacity 0.3s; }
        .btn-excel { background-color: #28a745; color: white; border: none; padding: 8px 20px; border-radius: 8px; transition: opacity 0.3s; }
        .btn-pdf:hover, .btn-excel:hover { opacity: 0.85; color: white; }
        .report-icon { font-size: 48px; margin-bottom: 15px; }
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
        @media (max-width: 768px) { .content-card { padding: 15px; } .btn-pdf, .btn-excel { padding: 6px 15px; font-size: 12px; } }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="#"><i class="fas fa-hospital-user"></i> فارما فلو</a>
        <div class="d-flex align-items-center">
            <span class="user-name me-3"><i class="fas fa-user-circle"></i> <?= h($_SESSION['user_name']) ?></span>
            <a href="index.php?controller=auth&action=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar">
                <h5><i class="fas fa-bars"></i> القائمة</h5>
                <hr>
                <a href="index.php?controller=accountant&action=invoices"><i class="fas fa-file-invoice"></i> الفواتير</a>
                <a href="index.php?controller=accountant&action=reports" class="active"><i class="fas fa-chart-line"></i> التقارير</a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="content-card">
                <h4><i class="fas fa-chart-line"></i> التقارير المالية</h4>
                
                <?= displayFlashMessages() ?>
                
                <!-- Filtres -->
                <div class="filter-box">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">نوع التقرير</label>
                            <select id="reportType" class="form-control">
                                <option value="sales">📊 تقرير المبيعات</option>
                                <option value="profits">💰 تقرير الأرباح</option>
                                <option value="purchases">📦 تقرير المشتريات</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">من تاريخ</label>
                            <input type="date" id="startDate" class="form-control" value="<?= date('Y-m-01') ?>">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">إلى تاريخ</label>
                            <input type="date" id="endDate" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="d-flex gap-2">
                                <button id="exportPdfBtn" class="btn-pdf w-100"><i class="fas fa-file-pdf"></i> PDF</button>
                                <button id="exportExcelBtn" class="btn-excel w-100"><i class="fas fa-file-excel"></i> Excel</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cartes des rapports -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-primary">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h5>تقرير المبيعات</h5>
                                <p class="small text-muted">إحصائيات المبيعات اليومية والشهرية</p>
                                <button class="btn btn-sm btn-outline-primary" onclick="exportReport('sales')">تصدير</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-success">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <h5>تقرير الأرباح</h5>
                                <p class="small text-muted">عرض الأرباح والخسائر</p>
                                <button class="btn btn-sm btn-outline-success" onclick="exportReport('profits')">تصدير</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <div class="report-icon text-warning">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h5>تقرير المشتريات</h5>
                                <p class="small text-muted">فواتير الشراء والموردين</p>
                                <button class="btn btn-sm btn-outline-warning" onclick="exportReport('purchases')">تصدير</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> 
                    <strong>طريقة الاستخدام :</strong>
                    <ul class="mb-0 mt-2">
                        <li>📄 <strong>PDF :</strong> يفتح التقرير في نافذة جديدة يمكن <strong>طباعتها</strong> أو <strong>حفظها كـ PDF</strong> (Ctrl+P ثم اختيار "حفظ بتنسيق PDF")</li>
                        <li>📊 <strong>Excel :</strong> يتم تحميل التقرير بصيغة <strong>CSV</strong> (يمكن فتحه مباشرة في Excel)</li>
                        <li>📅 يمكنك <strong>تحديد الفترة</strong> المطلوبة قبل التصدير</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Exporter avec les filtres actuels
function exportReport(type) {
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    window.open(`index.php?controller=accountant&action=exportPDF&type=${type}&start_date=${startDate}&end_date=${endDate}`, '_blank');
}

// Exporter avec les filtres (boutons principaux)
document.getElementById('exportPdfBtn').addEventListener('click', function() {
    let type = document.getElementById('reportType').value;
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    window.open(`index.php?controller=accountant&action=exportPDF&type=${type}&start_date=${startDate}&end_date=${endDate}`, '_blank');
});

document.getElementById('exportExcelBtn').addEventListener('click', function() {
    let type = document.getElementById('reportType').value;
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    window.location.href = `index.php?controller=accountant&action=exportExcel&type=${type}&start_date=${startDate}&end_date=${endDate}`;
});
</script>
</body>
</html>