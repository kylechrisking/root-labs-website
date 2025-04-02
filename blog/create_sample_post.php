<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/db.php';

echo "<h2>Creating Sample Post</h2>";

try {
    // Check if the posts table exists
    $tables_query = "SHOW TABLES LIKE 'posts'";
    $tables_result = $db->query($tables_query);
    
    if ($tables_result->rowCount() == 0) {
        echo "<p style='color: red;'>Posts table does not exist!</p>";
        die();
    }
    
    // Check if there are any users
    $users_query = "SELECT id FROM users LIMIT 1";
    $users_result = $db->query($users_query);
    
    if ($users_result->rowCount() == 0) {
        echo "<p style='color: red;'>No users found in the database!</p>";
        die();
    }
    
    $user = $users_result->fetch(PDO::FETCH_ASSOC);
    $user_id = $user['id'];
    
    // Check if there are any categories
    $categories_query = "SELECT id FROM categories LIMIT 1";
    $categories_result = $db->query($categories_query);
    
    if ($categories_result->rowCount() == 0) {
        echo "<p style='color: red;'>No categories found in the database!</p>";
        die();
    }
    
    $category = $categories_result->fetch(PDO::FETCH_ASSOC);
    $category_id = $category['id'];
    
    // Create a sample post
    $title = "Welcome to the Root Labs Blog";
    $slug = "welcome-to-the-root-labs-blog";
    $content = "<p>Welcome to the Root Labs blog! This is a sample post to test the blog functionality.</p>
                <p>We'll be sharing insights, tutorials, and updates from the Root Labs team here.</p>
                <p>Stay tuned for more content!</p>";
    $excerpt = "Welcome to the Root Labs blog! This is a sample post to test the blog functionality.";
    $status = "published";
    $created_at = date('Y-m-d H:i:s');
    
    $insert_query = "INSERT INTO posts (title, slug, content, excerpt, user_id, category_id, status, created_at) 
                     VALUES (:title, :slug, :content, :excerpt, :user_id, :category_id, :status, :created_at)";
    
    $stmt = $db->prepare($insert_query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':excerpt', $excerpt);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':created_at', $created_at);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✓ Sample post created successfully!</p>";
        echo "<p>You can now view the blog at <a href='index.php'>index.php</a></p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create sample post.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?> 