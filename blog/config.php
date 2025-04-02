<?php
/**
 * Blog Configuration Example
 * Copy this file to config.php and update the values
 */

// Site Settings
define('SITE_TITLE', 'Root Labs Blog');
define('SITE_DESCRIPTION', 'Tech News, Tutorials, and Updates from Root Labs');
define('SITE_URL', 'https://rootlabs.us/blog');
define('ADMIN_EMAIL', 'admin@rootlabs.us');

// Database Settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'rootlabs_blog');
define('DB_USER', 'rootlabs_blog');
define('DB_PASS', 'Cub!cJawb0ne');

// Blog Settings
define('POSTS_PER_PAGE', 10);
define('EXCERPT_LENGTH', 150);
define('UPLOAD_PATH', __DIR__ . '/uploads');
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB

// Cache Settings
define('ENABLE_CACHE', true);
define('CACHE_DURATION', 3600); // 1 hour
define('CACHE_PATH', __DIR__ . '/cache');

// Security Settings
define('ENCRYPTION_KEY', 'your-random-encryption-key');
define('SESSION_LIFETIME', 1800); // 30 minutes
define('LOGIN_ATTEMPTS_LIMIT', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// API Settings
define('ENABLE_API', false);
define('API_KEY', 'your-api-key');

// Comment Settings
define('ENABLE_COMMENTS', true);
define('MODERATE_COMMENTS', true);
define('COMMENT_FLOOD_CONTROL', 60); // Seconds between comments

// Social Media
define('SOCIAL_LINKS', [
    'linkedin' => 'https://www.linkedin.com/company/root-labs-us',
    'github' => 'https://github.com/root-labs-us',
    'twitter' => 'https://twitter.com/rootlabsus'
]);

// Development Mode
define('DEV_MODE', false);
if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('America/Chicago'); 