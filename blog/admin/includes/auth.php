<?php
/**
 * Admin Authentication Check
 * Ensures that only authenticated administrators can access admin pages
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Store the requested URL for redirect after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Get current user information
$currentUser = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['username']
];

// Update last activity timestamp
$_SESSION['last_activity'] = time();

// Check for session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session has expired
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
} 