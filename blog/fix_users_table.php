<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Fixing Users Table</h1>";

try {
    // Check if password_hash column exists
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'password_hash'");
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        echo "Adding password_hash column...<br>";
        $db->exec("ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NOT NULL AFTER email");
        echo "Column added successfully!<br>";
    } else {
        echo "password_hash column already exists.<br>";
    }
    
    // Check if role column exists
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'role'");
    $roleExists = $stmt->rowCount() > 0;
    
    if (!$roleExists) {
        echo "Adding role column...<br>";
        $db->exec("ALTER TABLE users ADD COLUMN role ENUM('admin', 'editor', 'author', 'user') NOT NULL DEFAULT 'user' AFTER password_hash");
        echo "Role column added successfully!<br>";
    } else {
        echo "Role column already exists.<br>";
    }
    
    // Check if status column exists
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'status'");
    $statusExists = $stmt->rowCount() > 0;
    
    if (!$statusExists) {
        echo "Adding status column...<br>";
        $db->exec("ALTER TABLE users ADD COLUMN status ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active' AFTER role");
        echo "Status column added successfully!<br>";
    } else {
        echo "Status column already exists.<br>";
    }
    
    // Check if admin user exists
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        echo "Creating admin user...<br>";
        $password = 'admin123';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("
            INSERT INTO users (username, display_name, email, password_hash, role, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'admin', 'active', NOW(), NOW())
        ");
        $stmt->execute(['admin', 'Administrator', 'admin@example.com', $password_hash]);
        echo "Admin user created successfully!<br>";
    } else {
        echo "Admin user already exists.<br>";
        
        // Update admin password if needed
        if (empty($admin['password_hash'])) {
            echo "Updating admin password...<br>";
            $password = 'admin123';
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
            $stmt->execute([$password_hash, 'admin']);
            echo "Admin password updated successfully!<br>";
        }
        
        // Update admin role and status if needed
        if (!isset($admin['role']) || $admin['role'] !== 'admin') {
            echo "Updating admin role...<br>";
            $stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE username = ?");
            $stmt->execute(['admin']);
            echo "Admin role updated successfully!<br>";
        }
        
        if (!isset($admin['status']) || $admin['status'] !== 'active') {
            echo "Updating admin status...<br>";
            $stmt = $db->prepare("UPDATE users SET status = 'active' WHERE username = ?");
            $stmt->execute(['admin']);
            echo "Admin status updated successfully!<br>";
        }
        
        // Update display_name if needed
        if (empty($admin['display_name'])) {
            echo "Updating admin display name...<br>";
            $stmt = $db->prepare("UPDATE users SET display_name = 'Administrator' WHERE username = ?");
            $stmt->execute(['admin']);
            echo "Admin display name updated successfully!<br>";
        }
    }
    
    // Show current users table structure
    echo "<h2>Current Users Table Structure:</h2>";
    $stmt = $db->query("DESCRIBE users");
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
    
    // Show admin user details
    echo "<h2>Admin User Details:</h2>";
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    echo "<pre>";
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} 