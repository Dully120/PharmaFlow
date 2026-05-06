<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
    <title>نقطة البيع - فارما فلو</title>
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
        .logout-btn:hover {
            background-color: #c82333;
            transform: scale(1.02);
            color: white;
        }
        .user-name { color: rgba(255,255,255,0.9); font-size: 14px; background-color: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 20px; }
        .user-name i {
            margin-left: 8px;
        }
        
        .search-card, .cart-card { background: white; border-radius: 15px; padding: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .product-item { border-bottom: 1px solid #eee; padding: 10px; cursor: pointer; transition: background 0.3s; display: flex; justify-content: space-between; align-items: center; }
        .product-item:hover { background-color: #e8f0fe; }
        .cart-item { border-bottom: 1px solid #eee; padding: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .quantity-input { width: 60px; text-align: center; border-radius: 8px; border: 1px solid #ddd; padding: 5px; }
        .btn-checkout { background-color: #28a745; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: bold; width: 100%; }
        .alert-badge { position: fixed; bottom: 20px; right: 20px; z-index: 1000; background-color: #dc3545; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2); text-decoration: none; }
        
        @media (max-width: 768px) {
            .product-item, .cart-item { font-size: 12px; }
            .quantity-input { width: 50px; }
            h4, h5 { font-size: 16px; }
        }
    </style>
</head>
<body>

<!-- Alerte flottante -->
<!-- Alerte flottante (visible seulement pour l'admin) -->
<?php if ($_SESSION['user_role'] === 'admin' && $alertCount > 0): ?>
<a href="index.php?controller=admin&action=alerts" class="alert-badge">
    <i class="fas fa-bell"></i>
    <span style="position: absolute; top: -5px; right: -5px; background-color: #ffc107; color: #000; border-radius: 50%; width: 20px; height: 20px; font-size: 10px; display: flex; align-items: center; justify-content: center;"><?= $alertCount ?></span>
</a>
<?php endif; ?>

<!-- Navbar -->
<nav class="navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="#"><i class="fas fa-hospital-user"></i> فارما فلو - POS</a>
        <div class="d-flex align-items-center">
            <span class="user-name me-3"><i class="fas fa-user-circle"></i> <?= h($_SESSION['user_name']) ?></span>
            <a href="index.php?controller=auth&action=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="row">
        <!-- Colonne recherche -->
        <div class="col-md-5 mb-3">
            <div class="search-card">
                <h5><i class="fas fa-search"></i> البحث عن منتج</h5>
                <input type="text" id="searchInput" class="form-control form-control-lg mt-2" placeholder="اسم الدواء أو الرمز الشريطي...">
                <div id="searchResults" class="mt-3" style="max-height: 500px; overflow-y: auto;">
                    <!-- Résultats AJAX -->
                </div>
            </div>
        </div>
        
        <!-- Colonne panier -->
        <div class="col-md-7 mb-3">
            <div class="cart-card">
                <h5><i class="fas fa-shopping-cart"></i> سلة المشتريات</h5>
                <div id="cartItems">
                    <div class="text-center text-muted py-5">السلة فارغة</div>
                </div>
                
                <hr>
                <div class="row">
                    <div class="col-6">المجموع الفرعي :</div>
                    <div class="col-6 text-end" id="subtotal">0.00 د.ل</div>
                </div>
                <div class="row">
                    <div class="col-6">الضريبة :</div>
                    <div class="col-6 text-end" id="taxAmount">0.00 د.ل</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>الإجمالي :</strong></div>
                    <div class="col-6 text-end" id="totalAmount">0.00 د.ل</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">المبلغ المدفوع :</div>
                    <div class="col-6"><input type="number" id="paidAmount" class="form-control" step="0.01" placeholder="0.00"></div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">الباقي :</div>
                    <div class="col-6 text-end" id="changeAmount">0.00 د.ل</div>
                </div>
                
                <div class="mt-3">
                    <button id="validateSaleBtn" class="btn-checkout"><i class="fas fa-check-circle"></i> إتمام البيع</button>
                    <button id="clearCartBtn" class="btn btn-secondary w-100 mt-2"><i class="fas fa-trash"></i> إفراغ السلة</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let cart = [];
let searchTimeout;

// Recherche AJAX
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    let query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.innerHTML = '';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`index.php?controller=pharmacist&action=searchProduct&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(products => {
                if (products.length === 0) {
                    searchResults.innerHTML = '<div class="alert alert-info">لا توجد نتائج</div>';
                    return;
                }
                
                let html = '';
                for (let p of products) {
                    let disabled = p.stock <= 0 ? 'disabled' : '';
                    html += `
                        <div class="product-item">
                            <div>
                                <strong>${p.name}</strong><br>
                                <small>السعر: ${p.price} د.ل | المتبقي: ${p.stock}</small>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.price}, ${p.stock})" ${disabled}>
                                <i class="fas fa-plus"></i> إضافة
                            </button>
                        </div>
                    `;
                }
                searchResults.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                searchResults.innerHTML = '<div class="alert alert-danger">حدث خطأ في البحث</div>';
            });
    }, 300);
});

// Ajouter au panier
function addToCart(id, name, price, stock) {
    if (stock <= 0) {
        alert('هذا المنتج غير متوفر حالياً');
        return;
    }
    
    let existing = cart.find(item => item.id === id);
    if (existing) {
        if (existing.quantity + 1 > stock) {
            alert(`لا يتوفر سوى ${stock} من هذا المنتج`);
            return;
        }
        existing.quantity++;
    } else {
        cart.push({ id: id, name: name, price: price, quantity: 1 });
    }
    updateCartDisplay();
}

// Mettre à jour l'affichage du panier
function updateCartDisplay() {
    let cartDiv = document.getElementById('cartItems');
    let subtotal = 0;
    
    if (cart.length === 0) {
        cartDiv.innerHTML = '<div class="text-center text-muted py-5">السلة فارغة</div>';
    } else {
        let html = '';
        for (let i = 0; i < cart.length; i++) {
            let item = cart[i];
            let lineTotal = item.price * item.quantity;
            subtotal += lineTotal;
            
            html += `
                <div class="cart-item">
                    <div style="flex: 2;"><strong>${item.name}</strong></div>
                    <div style="flex: 1;"><input type="number" class="quantity-input" value="${item.quantity}" min="1" onchange="updateQuantity(${i}, this.value)"></div>
                    <div style="flex: 1;">${item.price} د.ل</div>
                    <div style="flex: 1;">${lineTotal.toFixed(2)} د.ل</div>
                    <div><button class="btn btn-sm btn-danger" onclick="removeFromCart(${i})"><i class="fas fa-trash"></i></button></div>
                </div>
            `;
        }
        cartDiv.innerHTML = html;
    }
    
    let taxAmount = 0;
    let total = subtotal;
    
    document.getElementById('subtotal').innerText = subtotal.toFixed(2) + ' د.ل';
    document.getElementById('taxAmount').innerText = taxAmount.toFixed(2) + ' د.ل';
    document.getElementById('totalAmount').innerText = total.toFixed(2) + ' د.ل';
    
    let paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    let change = paid - total;
    document.getElementById('changeAmount').innerText = change.toFixed(2) + ' د.ل';
}

// Modifier quantité
function updateQuantity(index, newQuantity) {
    let qty = parseInt(newQuantity);
    if (isNaN(qty) || qty < 1) qty = 1;
    cart[index].quantity = qty;
    updateCartDisplay();
}

// Supprimer du panier
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

// Vider le panier
document.getElementById('clearCartBtn').addEventListener('click', function() {
    if (cart.length > 0 && confirm('هل أنت متأكد من إفراغ السلة؟')) {
        cart = [];
        updateCartDisplay();
        document.getElementById('paidAmount').value = '';
        document.getElementById('changeAmount').innerText = '0.00 د.ل';
    }
});

// Calculer le rendu de monnaie
document.getElementById('paidAmount').addEventListener('input', function() {
    let total = parseFloat(document.getElementById('totalAmount').innerText);
    let paid = parseFloat(this.value) || 0;
    let change = paid - total;
    document.getElementById('changeAmount').innerText = change.toFixed(2) + ' د.ل';
});

// Valider la vente
document.getElementById('validateSaleBtn').addEventListener('click', function() {
    if (cart.length === 0) {
        alert('السلة فارغة. أضف منتجات أولاً.');
        return;
    }
    
    let total = parseFloat(document.getElementById('totalAmount').innerText);
    let paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    
    if (paid < total) {
        alert(`المبلغ المدفوع (${paid}) أقل من الإجمالي (${total})`);
        return;
    }
    
    fetch('index.php?controller=pharmacist&action=saveSale', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cart=${encodeURIComponent(JSON.stringify(cart))}&paid_amount=${paid}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `index.php?controller=pharmacist&action=ticket&id=${data.sale_id}`;
        } else {
            alert('خطأ: ' + (data.error || 'حدث خطأ أثناء إتمام البيع'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال بالخادم');
    });
});
</script>
</body>
</html>