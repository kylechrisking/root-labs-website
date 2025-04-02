<?php
/**
 * Error Logging Script
 * This script helps diagnose issues with the blog
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Function to log errors
function logError($message, $data = null) {
    $logMessage = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $logMessage .= " - Data: " . print_r($data, true);
    }
    error_log($logMessage);
    echo "<p><strong>Error:</strong> " . htmlspecialchars($message) . "</p>";
}

// Test database connection
echo "<h1>Blog Error Diagnostics</h1>";

try {
    require_once 'includes/db.php';
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if tables exist
    $tables = ['users', 'categories', 'posts', 'tags', 'post_tags', 'comments'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '{$table}'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ Table '{$table}' exists</p>";
        } else {
            echo "<p style='color: red;'>✗ Table '{$table}' does not exist</p>";
            logError("Table '{$table}' does not exist");
        }
    }
    
    // Check if there are any posts
    $stmt = $db->query("SELECT COUNT(*) FROM posts WHERE status = 'published'");
    $postCount = $stmt->fetchColumn();
    echo "<p>Published posts: {$postCount}</p>";
    
    if ($postCount == 0) {
        echo "<p style='color: orange;'>⚠ No published posts found. This might be why the blog appears empty.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    logError("Database connection failed", $e->getMessage());
}

// Check file permissions
$files = [
    'index.php',
    'post.php',
    'includes/db.php',
    'includes/config.php',
    '.htaccess'
];

echo "<h2>File Permissions</h2>";
foreach ($files as $file) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        $perms = substr(sprintf('%o', $perms), -4);
        echo "<p>✓ {$file} exists (permissions: {$perms})</p>";
    } else {
        echo "<p style='color: red;'>✗ {$file} does not exist</p>";
        logError("File '{$file}' does not exist");
    }
}

// Check PHP version and extensions
echo "<h2>PHP Environment</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";

$requiredExtensions = ['pdo', 'pdo_mysql', 'mysqli', 'gd', 'json'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✓ {$ext} extension is loaded</p>";
    } else {
        echo "<p style='color: red;'>✗ {$ext} extension is not loaded</p>";
        logError("PHP extension '{$ext}' is not loaded");
    }
}

// Check server configuration
echo "<h2>Server Configuration</h2>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

// Check if mod_rewrite is enabled
if (in_array('mod_rewrite', apache_get_modules())) {
    echo "<p style='color: green;'>✓ mod_rewrite is enabled</p>";
} else {
    echo "<p style='color: red;'>✗ mod_rewrite is not enabled</p>";
    logError("Apache mod_rewrite is not enabled");
}

echo "<h2>Recommendations:</h2>";
echo "<ol>";
echo "<li>Check the error.log file for detailed error messages</li>";
echo "<li>Ensure the database has content (posts, categories, etc.)</li>";
echo "<li>Verify that the .htaccess file is being read by Apache</li>";
echo "<li>Check if there are any PHP errors in the Apache error log</li>";
echo "</ol>"; 