<?php
/**
 * Blog Header
 * Common header for all blog pages
 */

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current page for navigation highlighting
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('SITE_TITLE') ? SITE_TITLE : 'Root Labs Blog'; ?></title>
    <meta name="description" content="<?php echo defined('SITE_DESCRIPTION') ? SITE_DESCRIPTION : 'Tech News, Tutorials, and Updates from Root Labs'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/blog/assets/css/blog.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap">
    
    <!-- Scripts -->
    <script src="/blog/assets/js/blog.js" defer></script>
</head>
<body>
    <header class="blog-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">
                        <span class="logo-text">Root Labs</span>
                    </a>
                </div>
                
                <nav class="blog-nav">
                    <ul class="nav-links">
                        <li><a href="/blog" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="/blog/categories.php" class="<?php echo $currentPage === 'categories' ? 'active' : ''; ?>">Categories</a></li>
                        <li><a href="/blog/tags.php" class="<?php echo $currentPage === 'tags' ? 'active' : ''; ?>">Tags</a></li>
                        <li><a href="/blog/about.php" class="<?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a></li>
                        <li><a href="/blog/contact.php" class="<?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
                    </ul>
                </nav>
                
                <div class="mobile-nav-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>
    
    <main class="blog-main">
        <div class="container"> 