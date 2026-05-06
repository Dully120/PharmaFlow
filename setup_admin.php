<?php
// setup_admin.php - Créer un utilisateur admin avec un mot de passe valide
require_once 'config/database.php';

$db = Database::getInstance()->getConnection();

// Mot de passe: admin123
$password = 'admin123';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Vérifier si l'utilisateur existe déjà
$checkStmt = $db->prepare("SELECT * FROM users WHERE email = 'admin@pharmaflow.com'");
$checkStmt->execute();
$existingUser = $checkStmt->fetch();

if ($existingUser) {
    // Mettre à jour le mot de passe
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@pharmaflow.com'");
    $stmt->execute([$hashedPassword]);
    echo "✅ Mot de passe admin mis à jour avec succès !\n";
    echo "Email: admin@pharmaflow.com\n";
    echo "Mot de passe: admin123\n";
} else {
    // Créer un nouvel admin
    $stmt = $db->prepare("
        INSERT INTO users (name, email, password, role_id, is_active) 
        VALUES ('Administrateur', 'admin@pharmaflow.com', ?, 1, 1)
    ");
    $stmt->execute([$hashedPassword]);
    echo "✅ Utilisateur admin créé avec succès !\n";
    echo "Email: admin@pharmaflow.com\n";
    echo "Mot de passe: admin123\n";
}
?>