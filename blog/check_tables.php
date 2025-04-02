<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/db.php';

echo "<h1>Database Structure Check</h1>";

try {
    // Get all tables
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Existing Tables:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Required tables
    $requiredTables = [
        'users',
        'posts',
        'categories',
        'comments',
        'tags',
        'post_tags',
        'settings',
        'login_logs'
    ];
    
    echo "<h2>Missing Tables:</h2>";
    echo "<ul>";
    $missingTables = array_diff($requiredTables, $tables);
    if (empty($missingTables)) {
        echo "<li>No missing tables!</li>";
    } else {
        foreach ($missingTables as $table) {
            echo "<li>$table</li>";
        }
    }
    echo "</ul>";
    
    echo "<h2>Table Structures:</h2>";
    foreach ($tables as $table) {
        echo "<h3>$table</h3>";
        $columns = $db->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 