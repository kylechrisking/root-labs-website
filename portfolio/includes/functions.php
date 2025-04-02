<?php

// Existing functions
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

function getExcerpt($content, $length = 150) {
    $excerpt = strip_tags($content);
    if (strlen($excerpt) > $length) {
        $excerpt = substr($excerpt, 0, $length) . '...';
    }
    return htmlspecialchars($excerpt);
}

function saveTags($db, $postId, $tagString) {
    $db->query("DELETE FROM post_tags WHERE post_id = ?", [$postId]);
    
    $tags = array_map('trim', explode(',', $tagString));
    
    foreach ($tags as $tag) {
        if (empty($tag)) continue;
        
        $slug = generateSlug($tag);
        
        $existingTag = $db->query(
            "SELECT id FROM tags WHERE slug = ?",
            [$slug]
        )->fetch(PDO::FETCH_ASSOC);
        
        if ($existingTag) {
            $tagId = $existingTag['id'];
        } else {
            $db->query(
                "INSERT INTO tags (name, slug) VALUES (?, ?)",
                [$tag, $slug]
            );
            $tagId = $db->lastInsertId();
        }
        
        $db->query(
            "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)",
            [$postId, $tagId]
        );
    }
}

// New authentication and user management functions

/**
 * Authenticate user
 */
function authenticateUser($username, $password) {
    if (!isset(ADMIN_USERS[$username])) {
        return false;
    }

    if (password_verify($password, ADMIN_USERS[$username])) {
        $_SESSION['user'] = [
            'username' => $username,
            'authenticated' => true,
            'last_activity' => time()
        ];
        return true;
    }

    return false;
}

/**
 * Check if user is logged in
 */
function isAuthenticated() {
    if (!isset($_SESSION['user']) || !$_SESSION['user']['authenticated']) {
        return false;
    }

    // Check session timeout (30 minutes)
    if (time() - $_SESSION['user']['last_activity'] > 1800) {
        logout();
        return false;
    }

    // Update last activity
    $_SESSION['user']['last_activity'] = time();
    return true;
}

/**
 * Get current user
 */
function getCurrentUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

/**
 * Logout user
 */
function logout() {
    unset($_SESSION['user']);
    session_destroy();
    session_start();
}

/**
 * Check if user has permission for action
 */
function hasPermission($action) {
    if (!isAuthenticated()) {
        return false;
    }

    $user = getCurrentUser();
    
    // Add specific permission checks here if needed
    // For now, all authenticated users have full permissions
    return true;
}

/**
 * Secure form token generation and validation
 */
function generateFormToken() {
    $token = bin2hex(random_bytes(32));
    $_SESSION['form_token'] = $token;
    return $token;
}

function validateFormToken($token) {
    if (!isset($_SESSION['form_token']) || empty($token)) {
        return false;
    }
    
    $valid = hash_equals($_SESSION['form_token'], $token);
    unset($_SESSION['form_token']); // One-time use
    return $valid;
}

/**
 * Sanitize and validate input
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Create secure password hash
 */
function createPasswordHash($password) {
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
}

/**
 * Require authentication or redirect
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: ' . SITE_ROOT . 'blog/admin/login.php');
        exit;
    }
}

/**
 * Log activity
 */
function logActivity($action, $details = '') {
    $user = getCurrentUser();
    $username = $user ? $user['username'] : 'system';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Log to file and/or database
    $logEntry = sprintf(
        "[%s] User: %s | Action: %s | IP: %s | Details: %s\n",
        $timestamp,
        $username,
        $action,
        $ip,
        $details
    );
    
    error_log($logEntry, 3, __DIR__ . '/../logs/activity.log');
} 