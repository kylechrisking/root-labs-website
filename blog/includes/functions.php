<?php
/**
 * Functions file for the blog
 * Contains utility functions used throughout the blog
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    $logged_in = isset($_SESSION['user_id']);
    
    // Debug information
    if (isset($_SESSION['debug'])) {
        $_SESSION['debug'][] = "isLoggedIn() called - Result: " . ($logged_in ? 'true' : 'false');
        $_SESSION['debug'][] = "Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set');
    }
    
    return $logged_in;
}

/**
 * Check if user is an admin
 * @return bool True if user is an admin, false otherwise
 */
function isAdmin() {
    $is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    
    // Debug information
    if (isset($_SESSION['debug'])) {
        $_SESSION['debug'][] = "isAdmin() called - Result: " . ($is_admin ? 'true' : 'false');
        $_SESSION['debug'][] = "Session role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set');
    }
    
    return $is_admin;
}

/**
 * Redirect to a URL
 * @param string $url URL to redirect to
 * @return void
 */
function redirect($url) {
    // Debug information
    if (isset($_SESSION['debug'])) {
        $_SESSION['debug'][] = "Redirecting to: " . $url;
    }
    
    // Get the current URL path
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $current_path = basename($current_path);
    
    // If we're already on the target page, don't redirect
    if ($current_path === $url) {
        if (isset($_SESSION['debug'])) {
            $_SESSION['debug'][] = "Already on target page, skipping redirect";
        }
        return;
    }
    
    header("Location: $url");
    exit;
}

/**
 * Set a flash message
 * @param string $type Type of message (success, error, warning, info)
 * @param string $message Message to display
 * @return void
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * @return array|null Flash message or null if none exists
 */
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Sanitize input
 * @param string $input Input to sanitize
 * @return string Sanitized input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a slug from a string
 * @param string $string String to generate slug from
 * @return string Generated slug
 */
function generateSlug($string) {
    // Convert to lowercase
    $string = strtolower($string);
    
    // Replace non-alphanumeric characters with hyphens
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    
    // Replace multiple hyphens with a single hyphen
    $string = preg_replace('/-+/', '-', $string);
    
    // Remove leading and trailing hyphens
    $string = trim($string, '-');
    
    return $string;
}

/**
 * Format date
 * @param string $date Date to format
 * @param string $format Format to use (default: F j, Y)
 * @return string Formatted date
 */
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Truncate text to a specific length
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $append String to append if truncated
 * @return string Truncated text
 */
function truncateText($text, $length = 100, $append = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= $append;
    }
    return $text;
}

/**
 * Log user activity
 * @param int $user_id User ID
 * @param string $action Action performed
 * @param string $ip_address IP address
 * @param string $user_agent User agent
 * @return void
 */
function logUserActivity($user_id, $action, $ip_address, $user_agent) {
    global $db;
    
    try {
        // Check if the login_logs table exists
        $tableExists = $db->query("SHOW TABLES LIKE 'login_logs'")->rowCount() > 0;
        
        if ($tableExists) {
            $stmt = $db->prepare("INSERT INTO login_logs (user_id, ip_address, user_agent, action) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $ip_address, $user_agent, $action]);
        } else {
            // Log to error log if table doesn't exist
            error_log("login_logs table does not exist. User activity not logged.");
        }
    } catch (PDOException $e) {
        // Log the error but don't stop execution
        error_log("Error logging user activity: " . $e->getMessage());
    }
}

/**
 * Check if a post exists
 * @param string $slug Post slug
 * @return bool True if post exists, false otherwise
 */
function postExists($slug) {
    global $db;
    
    $stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE slug = ?");
    $stmt->execute([$slug]);
    
    return $stmt->fetchColumn() > 0;
}

/**
 * Get post by slug
 * @param string $slug Post slug
 * @return array|null Post data or null if not found
 */
function getPostBySlug($slug) {
    global $db;
    
    $stmt = $db->prepare("
        SELECT p.*, u.username as author_name, c.name as category_name, c.slug as category_slug
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.slug = ? AND p.status = 'published'
    ");
    $stmt->execute([$slug]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get category by slug
 * @param string $slug Category slug
 * @return array|null Category data or null if not found
 */
function getCategoryBySlug($slug) {
    global $db;
    
    $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get tag by slug
 * @param string $slug Tag slug
 * @return array|null Tag data or null if not found
 */
function getTagBySlug($slug) {
    global $db;
    
    $stmt = $db->prepare("SELECT * FROM tags WHERE slug = ?");
    $stmt->execute([$slug]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get a setting value
 * 
 * @param string $name Setting name
 * @param mixed $default Default value if setting not found
 * @return mixed Setting value or default
 */
function getSetting($name, $default = null) {
    global $db;
    static $settings = null;

    // Cache settings in memory
    if ($settings === null) {
        $settings = [];
        $stmt = $db->query("SELECT name, value FROM settings");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['name']] = $row['value'];
        }
    }

    return $settings[$name] ?? $default;
}

/**
 * Update a setting value
 * 
 * @param string $name Setting name
 * @param mixed $value Setting value
 * @return bool Success status
 */
function updateSetting($name, $value) {
    global $db;
    try {
        $stmt = $db->prepare("
            INSERT INTO settings (name, value) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE value = VALUES(value)
        ");
        return $stmt->execute([$name, is_bool($value) ? (int)$value : $value]);
    } catch (Exception $e) {
        error_log("Error updating setting: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all settings
 * 
 * @return array All settings
 */
function getAllSettings() {
    global $db;
    $settings = [];
    $stmt = $db->query("SELECT name, value FROM settings");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['name']] = $row['value'];
    }
    return $settings;
}

/**
 * Get user by ID
 * 
 * @param int $id User ID
 * @return array|null User data or null if not found
 */
function getUserById($id) {
    global $db;
    
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get user by username
 * 
 * @param string $username Username
 * @return array|null User data or null if not found
 */
function getUserByUsername($username) {
    global $db;
    
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update user
 * 
 * @param int $id User ID
 * @param array $data User data
 * @return bool True if successful, false otherwise
 */
function updateUser($id, $data) {
    global $db;
    
    try {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'password_hash') {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[] = $id;
        
        $stmt = $db->prepare("
            UPDATE users 
            SET " . implode(', ', $fields) . " 
            WHERE id = ?
        ");
        
        return $stmt->execute($values);
    } catch (PDOException $e) {
        error_log("Error updating user: " . $e->getMessage());
        return false;
    }
}

/**
 * Update user password
 * 
 * @param int $id User ID
 * @param string $password New password
 * @return bool True if successful, false otherwise
 */
function updateUserPassword($id, $password) {
    global $db;
    
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("
            UPDATE users 
            SET password_hash = ? 
            WHERE id = ?
        ");
        
        return $stmt->execute([$hash, $id]);
    } catch (PDOException $e) {
        error_log("Error updating user password: " . $e->getMessage());
        return false;
    }
}

/**
 * Create user
 * 
 * @param array $data User data
 * @return int|false User ID if successful, false otherwise
 */
function createUser($data) {
    global $db;
    
    try {
        $fields = [];
        $placeholders = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = $key;
                $placeholders[] = '?';
                $values[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $stmt = $db->prepare("
            INSERT INTO users (" . implode(', ', $fields) . ") 
            VALUES (" . implode(', ', $placeholders) . ")
        ");
        
        if ($stmt->execute($values)) {
            return $db->lastInsertId();
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Error creating user: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete user
 * 
 * @param int $id User ID
 * @return bool True if successful, false otherwise
 */
function deleteUser($id) {
    global $db;
    
    try {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error deleting user: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all users
 * 
 * @return array Array of users
 */
function getAllUsers() {
    global $db;
    
    $stmt = $db->query("SELECT * FROM users ORDER BY username");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all categories
 * 
 * @return array Array of categories
 */
function getAllCategories() {
    global $db;
    
    $stmt = $db->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all tags
 * 
 * @return array Array of tags
 */
function getAllTags() {
    global $db;
    
    $stmt = $db->query("SELECT * FROM tags ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all posts
 * 
 * @param string $status Post status (published, draft, all)
 * @return array Array of posts
 */
function getAllPosts($status = 'all') {
    global $db;
    
    $sql = "
        SELECT p.*, u.username as author_name, c.name as category_name
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
    ";
    
    if ($status !== 'all') {
        $sql .= " WHERE p.status = ?";
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    $stmt = $db->prepare($sql);
    
    if ($status !== 'all') {
        $stmt->execute([$status]);
    } else {
        $stmt->execute();
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all comments
 * 
 * @param string $status Comment status (approved, pending, spam, all)
 * @return array Array of comments
 */
function getAllComments($status = 'all') {
    global $db;
    
    $sql = "
        SELECT c.*, p.title as post_title, u.username as author_name
        FROM comments c
        LEFT JOIN posts p ON c.post_id = p.id
        LEFT JOIN users u ON c.author_id = u.id
    ";
    
    if ($status !== 'all') {
        $sql .= " WHERE c.status = ?";
    }
    
    $sql .= " ORDER BY c.created_at DESC";
    
    $stmt = $db->prepare($sql);
    
    if ($status !== 'all') {
        $stmt->execute([$status]);
    } else {
        $stmt->execute();
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?> 