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
    
    // Connect to database
    $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if admins table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
    if ($stmt->rowCount() == 0) {
        echo "Admins table does not exist!";
        exit(1);
    }
    
    // Check if admin user exists
    $email = 'admin@edipurwanto.com';
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h2>Admin user found:</h2>";
        echo "<p>ID: " . $admin['id'] . "</p>";
        echo "<p>Name: " . $admin['name'] . "</p>";
        echo "<p>Email: " . $admin['email'] . "</p>";
        echo "<p>Role: " . $admin['role'] . "</p>";
        
        // Update password
        $password = password_hash('12345678', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $stmt->execute([$password, $admin['id']]);
        
        echo "<h3>Admin password updated successfully!</h3>";
    } else {
        // Create new admin
        $name = 'Edi Purwanto';
        $password = password_hash('12345678', PASSWORD_DEFAULT);
        $role = 'admin';
        
        $stmt = $pdo->prepare("INSERT INTO admins (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$name, $email, $password, $role]);
        
        echo "<h2>Admin user created successfully!</h2>";
        echo "<p>Email: $email</p>";
        echo "<p>Password: 12345678</p>";
    }
    
    // Verify the admin user
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>Admin Details:</h2>";
    echo "<p>ID: " . $admin['id'] . "</p>";
    echo "<p>Name: " . $admin['name'] . "</p>";
    echo "<p>Email: " . $admin['email'] . "</p>";
    echo "<p>Role: " . $admin['role'] . "</p>";
    
    echo "<h2>You can now login with:</h2>";
    echo "<p>Email: $email</p>";
    echo "<p>Password: 12345678</p>";
    echo "<p>URL: <a href='http://127.0.0.1:8001/admin/login'>http://127.0.0.1:8001/admin/login</a></p>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit(1);
}