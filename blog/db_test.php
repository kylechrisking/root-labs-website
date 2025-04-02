<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

// Test if config file exists
if (!file_exists('config.php')) {
    die("<p style='color: red;'>Error: config.php not found</p>");
}

require_once 'config.php';

// Display connection parameters (without password)
echo "<p>Attempting to connect with:</p>";
echo "<ul>";
echo "<li>Host: " . DB_HOST . "</li>";
echo "<li>Database: " . DB_NAME . "</li>";
echo "<li>User: " . DB_USER . "</li>";
echo "</ul>";

try {
    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "<p style='color: green;'>✓ Database connection successful!</p>";

    // Test query for users
    $result = $conn->query("SELECT id, username, display_name, email FROM users");
    if ($result) {
        echo "<h3>Users Table Test:</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>User: " . htmlspecialchars($row['display_name']) . 
                 " (". htmlspecialchars($row['username']) . ")</li>";
        }
        echo "</ul>";
    }

    // Test query for categories
    $result = $conn->query("SELECT id, name FROM categories");
    if ($result) {
        echo "<h3>Categories Table Test:</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['name']) . "</li>";
        }
        echo "</ul>";
    }

    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?> 