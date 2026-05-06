<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الفاتورة - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Tahoma', sans-serif; }
        .navbar { background-color: #2C3E66; padding: 10px 15px; }
        .navbar-brand, .navbar .text-white { color: white !important; }
        .content-card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .badge-pending { background-color: #ffc107; color: #000; padding: 5px 12px; border-radius: 20px; }
        .badge-approved { background-color: #28a745; color: white; padding: 5px 12px; border-radius: 20px; }
        .badge-rejected { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 20px; }
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

<div class="container mt-3">
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="fas fa-file-invoice"></i> تفاصيل الفاتورة #<?= $invoice['id'] ?></h4>
            <span class="badge-pending <?= $invoice['status'] == 'approved' ? 'badge-approved' : ($invoice['status'] == 'rejected' ? 'badge-rejected' : 'badge-pending') ?>">
                <?php if ($invoice['status'] == 'pending'): ?>
                    قيد الانتظار
                <?php elseif ($invoice['status'] == 'approved'): ?>
                    معتمدة
                <?php else: ?>
                    مرفوضة
                <?php endif; ?>
            </span>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <strong>المورد:</strong> <?= h($invoice['supplier_name']) ?>
            </div>
            <div class="col-md-6">
                <strong>رقم الفاتورة:</strong> <?= h($invoice['invoice_number']) ?>
            </div>
            <div class="col-md-6">
                <strong>التاريخ:</strong> <?= $invoice['invoice_date'] ?>
            </div>
            <div class="col-md-6">
                <strong>تاريخ التسجيل:</strong> <?= $invoice['created_at'] ?>
            </div>
        </div>
        
        <h5>المنتجات</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoice['items'] as $item): ?>
                    <tr>
                        <td><?= h($item['product_name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['purchase_price'], 2) ?> د.ل</td>
                        <td><?= number_format($item['line_total'], 2) ?> د.ل</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="3" class="text-end"><strong>الإجمالي</strong></td>
                        <td><strong><?= number_format($invoice['total_amount'], 2) ?> د.ل</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <?php if ($invoice['status'] == 'rejected' && $invoice['rejection_reason']): ?>
        <div class="alert alert-danger">
            <strong>سبب الرفض:</strong> <?= h($invoice['rejection_reason']) ?>
        </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between mt-3">
            <a href="index.php?controller=accountant&action=invoices" class="btn btn-secondary"><i class="fas fa-arrow-right"></i> رجوع</a>
            <?php if ($invoice['status'] == 'pending'): ?>
            <div>
                <a href="index.php?controller=accountant&action=approve&id=<?= $invoice['id'] ?>" class="btn btn-success" onclick="return confirm('هل أنت متأكد من اعتماد هذه الفاتورة؟')"><i class="fas fa-check"></i> اعتماد</a>
                <button class="btn btn-danger" onclick="showRejectModal(<?= $invoice['id'] ?>)"><i class="fas fa-times"></i> رفض</button>
            </div>
            <?php endif; ?>
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
            <form method="POST" action="index.php?controller=accountant&action=reject">
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