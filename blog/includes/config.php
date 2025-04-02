<?php
/**
 * Configuration File
 * Loads environment variables and sets up basic configuration
 */

// Load environment variables from .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^(["\']).*\1$/', $value)) {
                $value = substr($value, 1, -1);
            }
            
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Set default environment if not set
if (!getenv('ENVIRONMENT')) {
    putenv('ENVIRONMENT=production');
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'admin');
define('DB_PASS', 'Cub!cJawb0ne');

// Security settings
define('VOTE_SALT', getenv('VOTE_SALT') ?: 'your-secret-salt-here');
define('SESSION_SECRET', getenv('SESSION_SECRET') ?: 'your-session-secret-here');

// Site configuration
define('SITE_URL', getenv('SITE_URL') ?: 'https://rootlabs.us');
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'admin@rootlabs.us');

// Error reporting configuration
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', 1800); // 30 minutes

// Set secure headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
if (getenv('ENVIRONMENT') === 'production') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
} 