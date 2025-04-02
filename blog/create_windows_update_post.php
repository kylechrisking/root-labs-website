<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/db.php';

echo "<h2>Creating Windows Update Post</h2>";

try {
    // Check if the posts table exists
    $tables_query = "SHOW TABLES LIKE 'posts'";
    $tables_result = $db->query($tables_query);
    
    if ($tables_result->rowCount() == 0) {
        echo "<p style='color: red;'>Posts table does not exist! Please run setup_db.php first.</p>";
        die();
    }
    
    // Check if there are any users, if not create admin user
    $users_query = "SELECT id FROM users LIMIT 1";
    $users_result = $db->query($users_query);
    
    if ($users_result->rowCount() == 0) {
        // Create admin user
        $adminUsername = 'admin';
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $adminEmail = 'admin@rootlabs.us';
        
        $create_admin = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'admin')";
        $stmt = $db->prepare($create_admin);
        $stmt->execute([$adminUsername, $adminPassword, $adminEmail]);
        
        $user_id = $db->lastInsertId();
        echo "<p style='color: green;'>✓ Admin user created successfully!</p>";
    } else {
        $user = $users_result->fetch(PDO::FETCH_ASSOC);
        $user_id = $user['id'];
    }
    
    // Check if there are any categories
    $categories_query = "SELECT id FROM categories WHERE name = 'Windows Updates' LIMIT 1";
    $categories_result = $db->query($categories_query);
    
    if ($categories_result->rowCount() == 0) {
        // Create Windows Updates category if it doesn't exist
        $create_category_query = "INSERT INTO categories (name, slug, description) VALUES ('Windows Updates', 'windows-updates', 'Latest Windows operating system updates and improvements')";
        $db->exec($create_category_query);
        $category_id = $db->lastInsertId();
        echo "<p style='color: green;'>✓ Windows Updates category created successfully!</p>";
    } else {
        $category = $categories_result->fetch(PDO::FETCH_ASSOC);
        $category_id = $category['id'];
    }
    
    // Create the Windows update post
    $title = "Windows 11 Update KB5053598 (OS Build 26100.3476) - March 2024";
    $slug = "windows-11-update-kb5053598-march-2024";
    $content = "<h2>Windows 11 Update KB5053598 Overview</h2>
                <p>Microsoft has released the March 2024 update for Windows 11, bringing several improvements and security enhancements to the operating system. This update, identified as KB5053598, brings the OS build to 26100.3476.</p>

                <h3>Release Information</h3>
                <ul>
                    <li><strong>Release Date:</strong> March 11, 2024</li>
                    <li><strong>Update Type:</strong> Security Update</li>
                    <li><strong>OS Build:</strong> 26100.3476</li>
                    <li><strong>KB Article:</strong> KB5053598</li>
                </ul>

                <h3>Key Improvements</h3>
                <ul>
                    <li>Enhanced security features and vulnerability patches</li>
                    <li>Improved system stability and performance</li>
                    <li>Updated Windows Defender definitions</li>
                    <li>Bug fixes and general system improvements</li>
                </ul>

                <h3>Installation Notes</h3>
                <p>This update is available through Windows Update and is recommended for all Windows 11 users. The update requires a system restart to complete the installation.</p>

                <h3>Compatibility</h3>
                <p>This update is compatible with all Windows 11 versions and editions that are currently supported by Microsoft.</p>

                <div class='business-support'>
                    <h3>Business Support</h3>
                    <p>Is your business experiencing issues with Windows updates? Root Labs provides expert support for managing Windows updates in enterprise environments. Our team can help ensure smooth update deployments and minimize disruption to your business operations.</p>
                    <p><a href='/contact' class='cta-button'>Contact Root Labs for Update Support</a></p>
                </div>

                <div class='source-info'>
                    <p><strong>Source:</strong> <a href='https://support.microsoft.com/en-us/topic/march-11-2024-kb5053598-os-build-26100-3476-2c5c3e66-1d9a-417f-a8e5-f0264317145e' target='_blank' rel='noopener noreferrer'>Microsoft Support Documentation</a></p>
                </div>";

    $excerpt = "Microsoft's March 2024 Windows 11 update (KB5053598) brings the OS to build 26100.3476, featuring security enhancements, performance improvements, and bug fixes.";
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
        echo "<p style='color: green;'>✓ Windows update post created successfully!</p>";
        echo "<p>You can now view the blog at <a href='index.php'>index.php</a></p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create Windows update post.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?> 