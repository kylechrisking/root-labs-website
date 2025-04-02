<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';

// Simple header
echo "<!DOCTYPE html>
<html>
<head>
    <title>Simple Blog Test</title>
</head>
<body>
    <h1>Simple Blog Test</h1>";

// Test database connection
try {
    // Test a simple query
    $result = $db->query("SELECT 1");
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if posts table exists
    $tables_query = "SHOW TABLES LIKE 'posts'";
    $tables_result = $db->query($tables_query);
    if ($tables_result->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Posts table exists</p>";
        
        // Try to get posts count
        $count_query = "SELECT COUNT(*) as count FROM posts";
        $count_result = $db->query($count_query);
        $count = $count_result->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<p>Total posts: " . $count . "</p>";
        
        // Try to get a few posts
        $posts_query = "SELECT * FROM posts LIMIT 5";
        $posts_result = $db->query($posts_query);
        $posts = $posts_result->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h2>Recent Posts:</h2>";
        if (count($posts) > 0) {
            echo "<ul>";
            foreach ($posts as $post) {
                echo "<li>" . htmlspecialchars($post['title']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No posts found</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Posts table does not exist</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Simple footer
echo "</body>
</html>";
?> 