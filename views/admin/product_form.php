<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($product) ? 'تعديل منتج' : 'إضافة منتج جديد' ?> - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #f4f6f9;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: #2C3E66;">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">فارما فلو</a>
        <div class="ms-auto">
            <span class="text-white me-3">مرحباً، <?= h($_SESSION['user_name']) ?></span>
            <a href="index.php?controller=auth&action=logout" class="btn btn-danger btn-sm">تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?= isset($product) ? '✏️ تعديل منتج' : '➕ إضافة منتج جديد' ?></h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الرمز الشريطي</label>
                                <input type="text" name="barcode" class="form-control" 
                                       value="<?= isset($product) ? h($product['barcode']) : '' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= isset($product) ? h($product['name']) : '' ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الاسم العلمي</label>
                                <input type="text" name="scientific_name" class="form-control" 
                                       value="<?= isset($product) ? h($product['scientific_name']) : '' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الفئة</label>
                                <select name="category_id" class="form-control">
                                    <option value="">-- اختر فئة --</option>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                        <?= (isset($product) && $product['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= h($category['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">المورد</label>
                                <select name="supplier_id" class="form-control">
                                    <option value="">-- اختر مورد --</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>" 
                                        <?= (isset($product) && $product['supplier_id'] == $supplier['id']) ? 'selected' : '' ?>>
                                        <?= h($supplier['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الجرعة</label>
                                <input type="text" name="dosage" class="form-control" 
                                       value="<?= isset($product) ? h($product['dosage']) : '' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الشكل الصيدلاني</label>
                                <input type="text" name="form" class="form-control" 
                                       value="<?= isset($product) ? h($product['form']) : '' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="purchase_price" class="form-control" 
                                       value="<?= isset($product) ? $product['purchase_price'] : '' ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">سعر البيع <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" 
                                       value="<?= isset($product) ? $product['selling_price'] : '' ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">نسبة الضريبة (%)</label>
                                <input type="number" step="0.01" name="tax_rate" class="form-control" 
                                       value="<?= isset($product) ? $product['tax_rate'] : '0' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">حد التنبيه للمخزون</label>
                                <input type="number" name="alert_threshold" class="form-control" 
                                       value="<?= isset($product) ? $product['alert_threshold'] : '10' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">وحدة البيع</label>
                                <input type="text" name="unit" class="form-control" 
                                       value="<?= isset($product) ? h($product['unit']) : 'boîte' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الكمية الأولية</label>
                                <input type="number" name="initial_quantity" class="form-control" value="0">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم الدفعة</label>
                                <input type="text" name="batch_number" class="form-control">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاريخ انتهاء الصلاحية</label>
                                <input type="date" name="expiry_date" class="form-control">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">موقع التخزين</label>
                                <input type="text" name="location" class="form-control">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=admin&action=products" class="btn btn-secondary">إلغاء</a>
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>