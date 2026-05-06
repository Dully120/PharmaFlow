<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفواتير - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f9; font-family: 'Tahoma', sans-serif; }
        .navbar { background-color: #2C3E66; padding: 10px 15px; }
        .navbar-brand, .navbar .text-white { color: white !important; }
        .sidebar { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .sidebar a { color: #2C3E66; text-decoration: none; display: block; padding: 10px; border-radius: 10px; margin-bottom: 5px; }
        .sidebar a:hover { background-color: #e8f0fe; }
        .sidebar a i { margin-left: 10px; width: 25px; }
        .sidebar a.active { background: linear-gradient(135deg, #2C3E66 0%, #1a2a4a 100%); color: white; }
        .content-card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .badge-pending { background-color: #ffc107; color: #000; padding: 4px 10px; border-radius: 20px; font-size: 11px; }
        .badge-approved { background-color: #28a745; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; }
        .badge-rejected { background-color: #dc3545; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; }
        .btn-sm { padding: 5px 10px; }
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
        @media (max-width: 768px) { .content-card { padding: 10px; } .table { font-size: 12px; } }
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
                <a href="index.php?controller=accountant&action=invoices" class="active"><i class="fas fa-file-invoice"></i> الفواتير</a>
                <a href="index.php?controller=accountant&action=reports"><i class="fas fa-chart-line"></i> التقارير</a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fas fa-file-invoice"></i> فواتير المشتريات</h4>
                    <a href="index.php?controller=accountant&action=createInvoice" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> فاتورة جديدة</a>
                </div>
                
                <?= displayFlashMessages() ?>
                
                <!-- Factures en attente -->
                <h5 class="mt-3">📋 فواتير قيد الانتظار</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>#</th><th>المورد</th><th>رقم الفاتورة</th><th>التاريخ</th><th>المبلغ</th><th>المسجل</th><th>الإجراءات</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pending)): ?>
                                <tr><td colspan="7" class="text-center">لا توجد فواتير قيد الانتظار</td></tr>
                            <?php else: ?>
                                <?php foreach ($pending as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= h($p['supplier_name']) ?></td>
                                    <td><?= h($p['invoice_number']) ?></td>
                                    <td><?= $p['invoice_date'] ?></td>
                                    <td><?= number_format($p['total_amount'], 2) ?> د.ل</td>
                                    <td><?= h($p['recorder_name']) ?></td>
                                    <td>>
                                        <a href="index.php?controller=accountant&action=viewInvoice&id=<?= $p['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                        <a href="index.php?controller=accountant&action=approve&id=<?= $p['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Approuver cette facture ?')"><i class="fas fa-check"></i></a>
                                        <button class="btn btn-sm btn-danger" onclick="showRejectModal(<?= $p['id'] ?>)"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Factures approuvées -->
                <h5 class="mt-4">✅ فواتير معتمدة</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>#</th><th>المورد</th><th>رقم الفاتورة</th><th>التاريخ</th><th>المبلغ</th><th>معتمد من</th><th>تاريخ الاعتماد</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($approved)): ?>
                                <tr><td colspan="7" class="text-center">لا توجد فواتير معتمدة</td></tr>
                            <?php else: ?>
                                <?php foreach ($approved as $a): ?>
                                <tr>
                                    <td><?= $a['id'] ?></td>
                                    <td><?= h($a['supplier_name']) ?></td>
                                    <td><?= h($a['invoice_number']) ?></td>
                                    <td><?= $a['invoice_date'] ?></td>
                                    <td><?= number_format($a['total_amount'], 2) ?> د.ل</td>
                                    <td><?= h($a['approver_name']) ?></td>
                                    <td><?= $a['approved_at'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفض الفاتورة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=accountant&action=reject" id="rejectForm">
                <input type="hidden" name="id" id="rejectId">
                <div class="modal-body">
                    <label class="form-label">سبب الرفض</label>
                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showRejectModal(id) {
    document.getElementById('rejectId').value = id;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
</body>
</html>