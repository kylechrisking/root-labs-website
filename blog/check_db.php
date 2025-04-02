<?php
require_once 'config.php';
require_once 'includes/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Structure Check</h2>";

try {
    // Get all tables
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in database:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table) . "</li>";
    }
    echo "</ul>";
    
    // Get structure of each table
    foreach ($tables as $table) {
        echo "<h3>Structure of table: " . htmlspecialchars($table) . "</h3>";
        $stmt = $db->query("DESCRIBE `" . $table . "`");
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
        
        // Show sample data
        echo "<h4>Sample data (first 5 rows):</h4>";
        $stmt = $db->query("SELECT * FROM `" . $table . "` LIMIT 5");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            echo "<table border='1'>";
            echo "<tr>";
            foreach ($rows[0] as $key => $value) {
                echo "<th>" . htmlspecialchars($key) . "</th>";
            }
            echo "</tr>";
            
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No data in table</p>";
        }
        
        echo "<hr>";
    }
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?> 