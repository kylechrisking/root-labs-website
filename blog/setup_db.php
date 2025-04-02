<?php
/**
 * Database Setup Script
 * This script creates the necessary database tables if they don't exist
 */

// Include the database connection file
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Set error reporting to display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting database setup...<br>";

// Check if tables exist
$tables = [
    'users',
    'categories',
    'tags',
    'posts',
    'post_tags',
    'comments',
    'post_meta',
    'settings'
];

$missingTables = [];
foreach ($tables as $table) {
    try {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() === 0) {
            $missingTables[] = $table;
        }
    } catch (PDOException $e) {
        die("Error checking table $table: " . $e->getMessage());
    }
}

echo "Missing tables: " . implode(", ", $missingTables) . "<br>";

// If any tables are missing, run the setup
if (!empty($missingTables)) {
    try {
        // Check if schema.sql exists
        if (!file_exists('schema.sql')) {
            die("Error: schema.sql file not found in " . getcwd());
        }
        
        // Read and execute schema.sql
        $schema = file_get_contents('schema.sql');
        if ($schema === false) {
            die("Error: Could not read schema.sql file");
        }
        
        echo "Schema file contents length: " . strlen($schema) . " bytes<br>";
        
        // Split the schema into individual statements
        $statements = array_filter(array_map('trim', explode(';', $schema)));
        
        echo "Found " . count($statements) . " SQL statements to execute<br>";
        
        // Execute each statement separately
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $db->exec($statement);
                    echo "Executed: " . substr($statement, 0, 100) . "...<br>";
                } catch (PDOException $e) {
                    echo "Error executing statement: " . $e->getMessage() . "<br>";
                    echo "Statement: " . htmlspecialchars($statement) . "<br>";
                }
            }
        }
        
        echo "Database schema creation completed.<br>";
    } catch (PDOException $e) {
        die("Error creating database schema: " . $e->getMessage());
    }
}

// Create default users if users table is empty
try {
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    
    echo "Current user count: " . $userCount . "<br>";
    
    if ($userCount === 0) {
        // Create default users
        $users = [
            [
                'username' => 'admin',
                'display_name' => 'Administrator',
                'email' => 'admin@rootlabs.us',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            [
                'username' => 'kking',
                'display_name' => 'Kyle King',
                'email' => 'kyle@rootlabs.us',
                'password' => 'kyle123',
                'role' => 'user'
            ],
            [
                'username' => 'JSamford',
                'display_name' => 'James Samford',
                'email' => 'james@rootlabs.us',
                'password' => 'james123',
                'role' => 'user'
            ]
        ];
        
        $stmt = $db->prepare("
            INSERT INTO users (username, display_name, email, password_hash, role, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, 'active', NOW(), NOW())
        ");
        
        foreach ($users as $user) {
            $passwordHash = password_hash($user['password'], PASSWORD_DEFAULT);
            $stmt->execute([
                $user['username'],
                $user['display_name'],
                $user['email'],
                $passwordHash,
                $user['role']
            ]);
            echo "Created user: " . $user['username'] . "<br>";
        }
        
        echo "Default users created successfully.<br>";
        echo "Default credentials:<br>";
        echo "Admin - Username: admin, Password: admin123<br>";
        echo "Kyle - Username: kking, Password: kyle123<br>";
        echo "James - Username: JSamford, Password: james123<br>";
    }
} catch (PDOException $e) {
    die("Error creating users: " . $e->getMessage());
}

// Create default category if categories table is empty
try {
    $stmt = $db->query("SELECT COUNT(*) FROM categories");
    $categoryCount = $stmt->fetchColumn();
    
    echo "Current category count: " . $categoryCount . "<br>";
    
    if ($categoryCount === 0) {
        $stmt = $db->prepare("
            INSERT INTO categories (name, slug, description, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        
        $stmt->execute(['Uncategorized', 'uncategorized', 'Default category for uncategorized posts']);
        echo "Default category created successfully.<br>";
    }
} catch (PDOException $e) {
    die("Error creating default category: " . $e->getMessage());
}

// Import settings if they don't exist
try {
    $stmt = $db->query("SELECT COUNT(*) FROM settings");
    $settingsCount = $stmt->fetchColumn();
    
    echo "Current settings count: " . $settingsCount . "<br>";
    
    if ($settingsCount === 0) {
        if (!file_exists('sql/settings.sql')) {
            die("Error: sql/settings.sql file not found");
        }
        
        $settingsSql = file_get_contents('sql/settings.sql');
        if ($settingsSql === false) {
            die("Error: Could not read settings.sql file");
        }
        
        $db->exec($settingsSql);
        echo "Default settings imported successfully.<br>";
    }
} catch (PDOException $e) {
    die("Error importing settings: " . $e->getMessage());
}

echo "<br>Setup completed successfully!<br>";
echo "You can now <a href='admin/login.php'>login to the admin panel</a>.<br>";
echo "<br><strong>Important:</strong> Please change the default passwords after your first login!";
?> 