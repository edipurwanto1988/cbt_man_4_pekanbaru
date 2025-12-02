<?php

// Simple script to create admin user
echo "Creating admin user...\n";

// Direct database connection
$host = '127.0.0.1';
$port = '8889';
$dbname = 'cbt_man4_pekanbaru';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Check if admin exists
    $email = 'admin@edipurwanto.com';
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo "Admin user already exists. Updating password...\n";
        $hashedPassword = password_hash('12345678', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);
        echo "Password updated successfully!\n";
    } else {
        echo "Creating new admin user...\n";
        $name = 'Edi Purwanto';
        $hashedPassword = password_hash('12345678', PASSWORD_DEFAULT);
        $role = 'admin';
        
        $stmt = $pdo->prepare("INSERT INTO admins (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$name, $email, $hashedPassword, $role]);
        echo "Admin user created successfully!\n";
    }
    
    echo "\nAdmin User Details:\n";
    echo "Email: admin@edipurwanto.com\n";
    echo "Password: 12345678\n";
    echo "Login URL: http://127.0.0.1:8001/admin/login\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}