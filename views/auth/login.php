<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - فارما فلو</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Tahoma', sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #2C3E66;
            font-size: 28px;
            font-weight: bold;
        }
        .logo p {
            color: #6c757d;
            font-size: 14px;
        }
        .btn-login {
            background-color: #2C3E66;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #1a2a4a;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <h1>فارما فلو</h1>
            <p>نظام إدارة الصيدليات المتكامل</p>
        </div>
        
        <?= displayFlashMessages() ?>
        
        <form action="index.php?controller=auth&action=login" method="POST">
            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-login">تسجيل الدخول</button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">© 2026 - كلية الدعوة الإسلامية</small>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>