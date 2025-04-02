<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Connection Test</h2>";

// Step 1: Check if config.php exists
echo "<h3>Step 1: Checking config.php</h3>";
if (file_exists('includes/config.php')) {
    echo "<p style='color: green;'>✓ config.php exists</p>";
} else {
    echo "<p style='color: red;'>✗ config.php not found</p>";
    die();
}

// Step 2: Include config.php
echo "<h3>Step 2: Including config.php</h3>";
try {
    require_once 'includes/config.php';
    echo "<p style='color: green;'>✓ config.php included successfully</p>";
    echo "<p>Database settings:</p>";
    echo "<ul>";
    echo "<li>Host: " . DB_HOST . "</li>";
    echo "<li>Database: " . DB_NAME . "</li>";
    echo "<li>User: " . DB_USER . "</li>";
    echo "</ul>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error including config.php: " . htmlspecialchars($e->getMessage()) . "</p>";
    die();
}

// Step 3: Check if db.php exists
echo "<h3>Step 3: Checking db.php</h3>";
if (file_exists('includes/db.php')) {
    echo "<p style='color: green;'>✓ db.php exists</p>";
} else {
    echo "<p style='color: red;'>✗ db.php not found</p>";
    die();
}

// Step 4: Include db.php
echo "<h3>Step 4: Including db.php</h3>";
try {
    require_once 'includes/db.php';
    echo "<p style='color: green;'>✓ db.php included successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error including db.php: " . htmlspecialchars($e->getMessage()) . "</p>";
    die();
}

// Step 5: Test database connection
echo "<h3>Step 5: Testing database connection</h3>";
try {
    // Create a new PDO connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $test_db = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test a simple query
    $result = $test_db->query("SELECT 1");
    echo "<p style='color: green;'>✓ Simple query executed successfully</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    die();
}

echo "<h3 style='color: green;'>All tests passed successfully!</h3>";
?> 