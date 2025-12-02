<?php

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';

try {
    // Get database configuration
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $dbPort = $_ENV['DB_PORT'] ?? '3306';
    $dbDatabase = $_ENV['DB_DATABASE'] ?? '';
    $dbUsername = $_ENV['DB_USERNAME'] ?? '';
    $dbPassword = $_ENV['DB_PASSWORD'] ?? '';
    
    echo "Database Configuration:\n";
    echo "Host: $dbHost:$dbPort\n";
    echo "Database: $dbDatabase\n";
    echo "Username: $dbUsername\n";
    echo "Password: " . (empty($dbPassword) ? '(empty)' : '****') . "\n\n";
    
    // Connect to database
    $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n\n";
    
    // Check if admins table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
    if ($stmt->rowCount() == 0) {
        echo "Admins table does not exist!\n";
        exit(1);
    }
    
    echo "Admins table exists!\n\n";
    
    // Check if admin user already exists
    $email = 'admin@edipurwanto.com';
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo "Admin user with email $email already exists.\n";
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Update password
        $password = password_hash('12345678', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $stmt->execute([$password, $admin['id']]);
        
        echo "Admin password updated successfully!\n";
    } else {
        // Create new admin
        $name = 'Edi Purwanto';
        $password = password_hash('12345678', PASSWORD_DEFAULT);
        $role = 'admin';
        
        $stmt = $pdo->prepare("INSERT INTO admins (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$name, $email, $password, $role]);
        
        echo "Admin user created successfully!\n";
    }
    
    // Verify the admin user
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nAdmin Details:\n";
    echo "ID: " . $admin['id'] . "\n";
    echo "Name: " . $admin['name'] . "\n";
    echo "Email: " . $admin['email'] . "\n";
    echo "Role: " . $admin['role'] . "\n";
    
    echo "\nYou can now login with:\n";
    echo "Email: $email\n";
    echo "Password: 12345678\n";
    echo "URL: http://127.0.0.1:8001/admin/login\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}