<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فاتورة - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Tahoma', sans-serif; }
        .navbar { background-color: #2C3E66; padding: 10px 15px; }
        .navbar-brand, .navbar .text-white { color: white !important; }
        .form-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .product-row { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px; }
        .btn-add-product { background-color: #28a745; color: white; border: none; padding: 8px 20px; border-radius: 8px; }
        .btn-save { background-color: #2C3E66; color: white; border: none; padding: 10px 30px; border-radius: 8px; }
        .btn-cancel { background-color: #6c757d; color: white; border: none; padding: 10px 30px; border-radius: 8px; text-decoration: none; }
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
        @media (max-width: 768px) { .form-card { padding: 15px; } }
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
    <div class="form-card">
        <h4 class="mb-3"><i class="fas fa-plus"></i> إضافة فاتورة مشتريات جديدة</h4>
        
        <?= displayFlashMessages() ?>
        
        <form method="POST" action="index.php?controller=accountant&action=storeInvoice" id="invoiceForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">المورد <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-control" required>
                        <option value="">-- اختر مورد --</option>
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= h($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">رقم الفاتورة <span class="text-danger">*</span></label>
                    <input type="text" name="invoice_number" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">المبلغ الإجمالي <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="total_amount" class="form-control" id="totalAmount" readonly value="0">
                </div>
            </div>
            
            <hr>
            <h5><i class="fas fa-boxes"></i> المنتجات</h5>
            <div id="productsContainer">
                <div class="product-row" id="productRow0">
                    <div class="row">
                        <div class="col-md-5 mb-2">
                            <label class="form-label">المنتج <span class="text-danger">*</span></label>
                            <select name="product_id[]" class="form-control product-select" required>
                                <option value="">-- اختر منتج --</option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-price="<?= $p['purchase_price'] ?>"><?= h($p['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">الكمية <span class="text-danger">*</span></label>
                            <input type="number" name="quantity[]" class="form-control quantity" value="1" min="1" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="purchase_price[]" class="form-control price" required>
                        </div>
                        <div class="col-md-1 mb-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-product" style="display: none;"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn-add-product mt-2" id="addProductBtn"><i class="fas fa-plus"></i> إضافة منتج آخر</button>
            
            <hr>
            <div class="d-flex justify-content-between mt-3">
                <a href="index.php?controller=accountant&action=invoices" class="btn-cancel"><i class="fas fa-arrow-right"></i> إلغاء</a>
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ الفاتورة</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let productCount = 1;

// Calculer le total
function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        let quantity = row.querySelector('.quantity')?.value || 0;
        let price = row.querySelector('.price')?.value || 0;
        total += quantity * price;
    });
    document.getElementById('totalAmount').value = total.toFixed(2);
}

// Mettre à jour le prix par défaut quand on sélectionne un produit
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        let selectedOption = e.target.options[e.target.selectedIndex];
        let price = selectedOption.getAttribute('data-price');
        if (price) {
            let row = e.target.closest('.product-row');
            row.querySelector('.price').value = price;
            calculateTotal();
        }
    }
});

// Ajouter une ligne de produit
document.getElementById('addProductBtn').addEventListener('click', function() {
    let container = document.getElementById('productsContainer');
    let newRow = document.createElement('div');
    newRow.className = 'product-row';
    newRow.id = 'productRow' + productCount;
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-5 mb-2">
                <label class="form-label">المنتج <span class="text-danger">*</span></label>
                <select name="product_id[]" class="form-control product-select" required>
                    <option value="">-- اختر منتج --</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>" data-price="<?= $p['purchase_price'] ?>"><?= h($p['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">الكمية <span class="text-danger">*</span></label>
                <input type="number" name="quantity[]" class="form-control quantity" value="1" min="1" required onchange="calculateTotal()">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="purchase_price[]" class="form-control price" required onchange="calculateTotal()">
            </div>
            <div class="col-md-1 mb-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    productCount++;
    
    // Afficher les boutons supprimer
    document.querySelectorAll('.remove-product').forEach(btn => btn.style.display = 'inline-block');
});

// Supprimer une ligne
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product') || e.target.parentElement.classList.contains('remove-product')) {
        let btn = e.target.classList.contains('remove-product') ? e.target : e.target.parentElement;
        let row = btn.closest('.product-row');
        if (document.querySelectorAll('.product-row').length > 1) {
            row.remove();
            calculateTotal();
        } else {
            alert('لا يمكن حذف المنتج الوحيد');
        }
    }
});

// Écouter les changements de quantité et prix
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
        calculateTotal();
    }
});
</script>
</body>
</html>