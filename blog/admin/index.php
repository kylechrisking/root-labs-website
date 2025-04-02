<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to capture any errors
ob_start();

require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize debug array if it doesn't exist
if (!isset($_SESSION['debug'])) {
    $_SESSION['debug'] = [];
}

$_SESSION['debug'][] = "Script started";
$_SESSION['debug'][] = "Session ID: " . session_id();
$_SESSION['debug'][] = "Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set');
$_SESSION['debug'][] = "Session role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set');

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['debug'][] = "User not logged in, redirecting to login";
    header("Location: login.php");
    exit;
}

// Check if user is admin
if (!isAdmin()) {
    $_SESSION['debug'][] = "User not admin, redirecting to login";
    header("Location: login.php");
    exit;
}

// Get user data
$user = getUserById($_SESSION['user_id']);
if (!$user) {
    $_SESSION['debug'][] = "User not found in database, redirecting to login";
    session_unset();
    header("Location: login.php");
    exit;
}

// Get counts for dashboard
try {
    $post_count = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $user_count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $comment_count = $db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    
    $_SESSION['debug'][] = "Dashboard counts retrieved successfully";
} catch (PDOException $e) {
    error_log("Error getting dashboard counts: " . $e->getMessage());
    $_SESSION['debug'][] = "Error getting dashboard counts: " . $e->getMessage();
    
    $post_count = 0;
    $user_count = 0;
    $comment_count = 0;
}

// Get recent posts
try {
    $stmt = $db->query("
        SELECT p.*, u.username as author_name, c.name as category_name
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
        LIMIT 5
    ");
    $recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $_SESSION['debug'][] = "Recent posts retrieved successfully";
} catch (PDOException $e) {
    error_log("Error getting recent posts: " . $e->getMessage());
    $_SESSION['debug'][] = "Error getting recent posts: " . $e->getMessage();
    
    $recent_posts = [];
}

// Get recent comments
try {
    $stmt = $db->query("
        SELECT c.*, p.title as post_title, u.username as author_name
        FROM comments c
        LEFT JOIN posts p ON c.post_id = p.id
        LEFT JOIN users u ON c.author_id = u.id
        ORDER BY c.created_at DESC
        LIMIT 5
    ");
    $recent_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $_SESSION['debug'][] = "Recent comments retrieved successfully";
} catch (PDOException $e) {
    error_log("Error getting recent comments: " . $e->getMessage());
    $_SESSION['debug'][] = "Error getting recent comments: " . $e->getMessage();
    
    $recent_comments = [];
}

// Get any output buffer errors
$output_errors = ob_get_clean();
if (!empty($output_errors)) {
    $_SESSION['debug'][] = "Output errors: " . $output_errors;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Root Labs Blog Admin</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%237E22CE'%3E%3Cpath d='M4 4v16a2 2 0 002 2h12a2 2 0 002-2V8.342a2 2 0 00-.602-1.43l-4.44-4.342A2 2 0 0013.56 2H6a2 2 0 00-2 2z'/%3E%3C/svg%3E">
    <link rel="stylesheet" href="../assets/css/admin-shared.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .stat-card h3 {
            margin: 0 0 0.5rem 0;
            color: #4a5568;
            font-size: 1rem;
        }
        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .recent-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        .recent-section h2 {
            margin: 0 0 1rem 0;
            color: #4a5568;
            font-size: 1.25rem;
        }
        .recent-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .recent-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .recent-list li:last-child {
            border-bottom: none;
        }
        .recent-list a {
            color: #4a5568;
            text-decoration: none;
        }
        .recent-list a:hover {
            color: #667eea;
        }
        .recent-meta {
            font-size: 0.875rem;
            color: #718096;
        }
        .dashboard-footer {
            margin-top: 2rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .debug-toggle {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background: var(--background);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 0.7rem;
            color: var(--text-secondary);
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
            z-index: 100;
        }
        
        .debug-toggle:hover {
            opacity: 1;
        }
        
        .debug-info {
            margin-top: 2rem;
            padding: 1rem;
            background: var(--background);
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.75rem;
            white-space: pre-wrap;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }
        
        .debug-info.visible {
            display: block;
        }
        
        .debug-info strong {
            color: var(--text);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <div class="nav-brand">Root Labs Blog Admin</div>
            <div class="nav-links">
                <a href="index.php" class="active">Dashboard</a>
                <a href="posts.php">Posts</a>
                <a href="categories.php">Categories</a>
                <a href="comments.php">Comments</a>
                <a href="users.php">Users</a>
                <a href="settings.php">Settings</a>
            </div>
            <div class="nav-user">
                <span>Welcome, <?php echo htmlspecialchars($user['username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
        
        <main class="main-content">
            <div class="dashboard-grid">
                <div class="stat-card">
                    <h3>Total Posts</h3>
                    <div class="number"><?php echo $post_count; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="number"><?php echo $user_count; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Comments</h3>
                    <div class="number"><?php echo $comment_count; ?></div>
                </div>
            </div>
            
            <div class="recent-section">
                <h2>Recent Posts</h2>
                <?php if (!empty($recent_posts)): ?>
                    <ul class="recent-list">
                        <?php foreach ($recent_posts as $post): ?>
                            <li>
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                                <div class="recent-meta">
                                    By <?php echo htmlspecialchars($post['author_name']); ?> 
                                    in <?php echo htmlspecialchars($post['category_name']); ?>
                                    on <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No posts found.</p>
                <?php endif; ?>
            </div>
            
            <div class="recent-section">
                <h2>Recent Comments</h2>
                <?php if (!empty($recent_comments)): ?>
                    <ul class="recent-list">
                        <?php foreach ($recent_comments as $comment): ?>
                            <li>
                                <a href="edit_comment.php?id=<?php echo $comment['id']; ?>">
                                    <?php echo htmlspecialchars(truncateText($comment['content'], 100)); ?>
                                </a>
                                <div class="recent-meta">
                                    By <?php echo htmlspecialchars($comment['author_name']); ?> 
                                    on <?php echo htmlspecialchars($comment['post_title']); ?>
                                    on <?php echo date('M j, Y', strtotime($comment['created_at'])); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No comments found.</p>
                <?php endif; ?>
            </div>
            
            <div class="dashboard-footer">
                <p>&copy; <?php echo date('Y'); ?> Root Labs Blog. All rights reserved.</p>
            </div>
        </main>
    </div>
    <button class="debug-toggle" onclick="toggleDebug()">Show Debug</button>
    <div id="debugInfo" class="debug-info">
        <strong>Debug Information:</strong>
        <?php
        if (isset($_SESSION['debug'])) {
            echo '<pre>';
            print_r($_SESSION['debug']);
            echo '</pre>';
        }
        ?>
    </div>
    <script>
        function toggleDebug() {
            const debugInfo = document.getElementById('debugInfo');
            const toggleButton = document.querySelector('.debug-toggle');
            if (debugInfo.classList.contains('visible')) {
                debugInfo.classList.remove('visible');
                toggleButton.textContent = 'Show Debug';
            } else {
                debugInfo.classList.add('visible');
                toggleButton.textContent = 'Hide Debug';
            }
        }
    </script>
</body>
</html> 