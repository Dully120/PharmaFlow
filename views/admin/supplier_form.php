<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($supplier) ? 'تعديل مورد' : 'إضافة مورد جديد' ?> - فارما فلو</title>
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?= isset($supplier) ? '✏️ تعديل مورد' : '➕ إضافة مورد جديد' ?></h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">اسم المورد <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?= isset($supplier) ? h($supplier['name']) : '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea name="address" class="form-control" rows="2"><?= isset($supplier) ? h($supplier['address']) : '' ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="<?= isset($supplier) ? h($supplier['phone']) : '' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= isset($supplier) ? h($supplier['email']) : '' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">شخص الاتصال</label>
                            <input type="text" name="contact_person" class="form-control" 
                                   value="<?= isset($supplier) ? h($supplier['contact_person']) : '' ?>">
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=admin&action=suppliers" class="btn btn-secondary">إلغاء</a>
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