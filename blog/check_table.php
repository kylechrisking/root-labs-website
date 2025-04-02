<?php
require_once 'config.php';
require_once 'includes/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Table Check</h2>";

try {
    // Check if users table exists
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() === 0) {
        die("Users table does not exist!");
    }
    
    // Get table structure
    echo "<h3>Users Table Structure:</h3>";
    $stmt = $db->query("DESCRIBE users");
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Type'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Null'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Key'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Get all users with password hashes
    echo "<h3>Current Users:</h3>";
    $stmt = $db->query("SELECT id, username, display_name, email, role, status, password_hash, created_at FROM users");
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Username</th><th>Display Name</th><th>Email</th><th>Role</th><th>Status</th><th>Password Hash</th><th>Created At</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['username'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['display_name'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['role'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['status'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['password_hash'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['created_at'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Try to verify admin password
    echo "<h3>Password Verification Test:</h3>";
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        $testPassword = 'admin123';
        $isValid = password_verify($testPassword, $admin['password_hash']);
        echo "Testing password 'admin123' for admin user:<br>";
        echo "Password hash: " . htmlspecialchars($admin['password_hash']) . "<br>";
        echo "Verification result: " . ($isValid ? "VALID" : "INVALID") . "<br>";
    } else {
        echo "Admin user not found!";
    }
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?> 