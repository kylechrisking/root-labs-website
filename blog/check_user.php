<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting check...<br>";

try {
    require_once 'config.php';
    echo "Config loaded<br>";
    
    require_once 'includes/db.php';
    echo "Database connection loaded<br>";

    // Check if users table exists
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() === 0) {
        die("Users table does not exist!");
    }
    echo "Users table exists<br>";

    // Check users table structure
    $stmt = $db->query("DESCRIBE users");
    echo "Users table structure:<br>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo htmlspecialchars($row['Field']) . " - " . htmlspecialchars($row['Type']) . "<br>";
    }

    // Check ALL users in the database
    $stmt = $db->query("SELECT * FROM users");
    echo "<br>All users in database:<br>";
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<br>User details:<br>";
        echo "ID: " . htmlspecialchars($user['id']) . "<br>";
        echo "Username: " . htmlspecialchars($user['username']) . "<br>";
        echo "Display Name: " . htmlspecialchars($user['display_name']) . "<br>";
        echo "Email: " . htmlspecialchars($user['email']) . "<br>";
        echo "Role: " . htmlspecialchars($user['role']) . "<br>";
        echo "Status: " . htmlspecialchars($user['status']) . "<br>";
        echo "Password hash exists: " . (!empty($user['password_hash']) ? 'Yes' : 'No') . "<br>";
        echo "Password hash length: " . strlen($user['password_hash']) . "<br>";
        echo "Created at: " . htmlspecialchars($user['created_at']) . "<br>";
        echo "Updated at: " . htmlspecialchars($user['updated_at']) . "<br>";
        echo "----------------------------------------<br>";
    }

    // Try to create admin user if it doesn't exist
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    if ($stmt->fetchColumn() == 0) {
        echo "<br>Admin user not found. Creating...<br>";
        
        $stmt = $db->prepare("
            INSERT INTO users (username, display_name, email, password_hash, role, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, 'admin', 'active', NOW(), NOW())
        ");
        
        $username = 'admin';
        $displayName = 'Administrator';
        $email = 'admin@rootlabs.us';
        $password = 'admin123';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->execute([$username, $displayName, $email, $passwordHash]);
        echo "Admin user created successfully!<br>";
    }

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
} catch (Exception $e) {
    die("General Error: " . $e->getMessage());
}
?> 