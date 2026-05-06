<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيصال البيع - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Tahoma', sans-serif; }
        .ticket { max-width: 450px; margin: 20px auto; background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .ticket-header { text-align: center; border-bottom: 2px dashed #ddd; padding-bottom: 10px; margin-bottom: 15px; }
        .ticket-footer { text-align: center; border-top: 2px dashed #ddd; padding-top: 10px; margin-top: 15px; font-size: 12px; color: #666; }
        .items-table td, .items-table th { padding: 5px 0; border: none; }
        .total-row { border-top: 2px solid #ddd; font-weight: bold; }
        @media print { body { background-color: white; } .no-print { display: none; } .ticket { box-shadow: none; margin: 0; padding: 10px; } }
    </style>
</head>
<body>

<div class="ticket">
    <div class="ticket-header">
        <h4><i class="fas fa-hospital-user"></i> فارما فلو</h4>
        <p>نظام إدارة الصيدليات</p>
        <small>تاريخ: <?= date('Y-m-d H:i', strtotime($sale['sale_date'])) ?></small><br>
        <small>رقم الفاتورة: #<?= $sale['id'] ?></small><br>
        <small>الصيدلي: <?= h($sale['user_name']) ?></small>
    </div>
    
    <table class="table items-table">
        <thead>
            <tr style="border-bottom: 1px solid #ddd;">
                <th>المنتج</th>
                <th class="text-center">الكمية</th>
                <th class="text-center">السعر</th>
                <th class="text-center">الإجمالي</th>
             </tr>
        </thead>
        <tbody>
            <?php foreach ($sale['items'] as $item): ?>
            <tr>
                <td><?= h($item['product_name']) ?></td>
                <td class="text-center"><?= $item['quantity'] ?></td>
                <td class="text-center"><?= number_format($item['unit_price'], 2) ?> د.ل</td>
                <td class="text-center"><?= number_format($item['line_total'], 2) ?> د.ل</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="row mt-3">
        <div class="col-6">المجموع الفرعي :</div>
        <div class="col-6 text-end"><?= number_format($sale['subtotal'], 2) ?> د.ل</div>
    </div>
    <div class="row">
        <div class="col-6">الضريبة :</div>
        <div class="col-6 text-end"><?= number_format($sale['tax_amount'], 2) ?> د.ل</div>
    </div>
    <div class="row total-row pt-2 mt-2">
        <div class="col-6"><strong>الإجمالي :</strong></div>
        <div class="col-6 text-end"><strong><?= number_format($sale['total'], 2) ?> د.ل</strong></div>
    </div>
    <div class="row">
        <div class="col-6">المبلغ المدفوع :</div>
        <div class="col-6 text-end"><?= number_format($sale['paid_amount'], 2) ?> د.ل</div>
    </div>
    <div class="row">
        <div class="col-6">الباقي :</div>
        <div class="col-6 text-end"><?= number_format($sale['change_amount'], 2) ?> د.ل</div>
    </div>
    
    <div class="ticket-footer">
        <p>شكراً لزيارتكم</p>
        <p>نتمنى لكم دوام الصحة والعافية</p>
    </div>
</div>

<div class="text-center mt-3 no-print">
    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> طباعة الإيصال</button>
    <a href="index.php?controller=pharmacist&action=pos" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> بيع جديد</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>