<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Get current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get posts with pagination
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name, c.slug as category_slug,
           u.username as author_name, GROUP_CONCAT(t.name) as tags,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN post_tags pt ON p.id = pt.post_id
    LEFT JOIN tags t ON pt.tag_id = t.id
    WHERE p.status = 'published'
    GROUP BY p.id
    ORDER BY p.published_at DESC
    LIMIT ? OFFSET ?
");
$stmt->execute([$per_page, $offset]);
$posts = $stmt->fetchAll();

// Get total posts for pagination
$stmt = $db->query("SELECT COUNT(*) FROM posts WHERE status = 'published'");
$total_posts = $stmt->fetchColumn();
$total_pages = ceil($total_posts / $per_page);

// Get categories for sidebar
$stmt = $db->query("
    SELECT c.*, COUNT(p.id) as post_count 
    FROM categories c
    LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
    GROUP BY c.id
    HAVING post_count > 0
    ORDER BY c.name
");
$categories = $stmt->fetchAll();

// Get popular tags for sidebar
$stmt = $db->query("
    SELECT t.*, COUNT(pt.post_id) as post_count
    FROM tags t
    JOIN post_tags pt ON t.id = pt.tag_id
    JOIN posts p ON pt.post_id = p.id AND p.status = 'published'
    GROUP BY t.id
    HAVING post_count > 0
    ORDER BY post_count DESC
    LIMIT 10
");
$tags = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Labs Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/blog.css" rel="stylesheet">
</head>
<body>
    <header class="blog-header">
        <div class="header-container">
            <a href="/blog" class="blog-logo">
                <img src="/assets/images/kk-logo.svg" alt="Root Labs">
                Root Labs Blog
            </a>
            <nav class="blog-nav">
                <div class="nav-left">
                    <a href="../portfolio" class="nav-link">← Back to Portfolio</a>
                </div>
                <div class="nav-right">
                    <a href="admin/" class="nav-link">Admin</a>
                </div>
            </nav>
            <button class="mobile-menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <main class="blog-main">
        <div class="posts-grid">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <?php if ($post['featured_image']): ?>
                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                             alt="" class="post-image">
                    <?php endif; ?>
                    <div class="post-content">
                        <h2 class="post-title">
                            <a href="/blog/post/<?php echo htmlspecialchars($post['slug']); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h2>
                        <div class="post-excerpt">
                            <?php echo htmlspecialchars($post['excerpt'] ?? substr(strip_tags($post['content']), 0, 150) . '...'); ?>
                        </div>
                        <div class="post-meta">
                            <span><?php echo date('F j, Y', strtotime($post['published_at'])); ?></span>
                            <span>•</span>
                            <a href="/blog/category/<?php echo htmlspecialchars($post['category_slug']); ?>" 
                               class="category-link">
                                <?php echo htmlspecialchars($post['category_name']); ?>
                            </a>
                            <span>•</span>
                            <span><?php echo $post['comment_count']; ?> comments</span>
                        </div>
                        <?php if ($post['tags']): ?>
                            <div class="post-tags">
                                <?php foreach (explode(',', $post['tags']) as $tag): ?>
                                    <a href="/blog/tag/<?php echo generateSlug($tag); ?>" 
                                       class="tag-link">
                                        #<?php echo htmlspecialchars($tag); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" 
                           class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="page-link">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <aside class="blog-sidebar">
            <div class="sidebar-widget">
                <h3 class="widget-title">Categories</h3>
                <ul class="category-list">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="/blog/category/<?php echo htmlspecialchars($category['slug']); ?>" 
                               class="category-link">
                                <?php echo htmlspecialchars($category['name']); ?>
                                <span><?php echo $category['post_count']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="sidebar-widget">
                <h3 class="widget-title">Popular Tags</h3>
                <ul class="tag-list">
                    <?php foreach ($tags as $tag): ?>
                        <li>
                            <a href="/blog/tag/<?php echo htmlspecialchars($tag['slug']); ?>" 
                               class="tag-link">
                                #<?php echo htmlspecialchars($tag['name']); ?>
                                <span><?php echo $tag['post_count']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </main>

    <footer class="blog-footer">
        <div class="footer-container">
            <p>&copy; <?php echo date('Y'); ?> Root Labs. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const nav = document.querySelector('.blog-nav');
        
        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('show');
            menuToggle.classList.toggle('active');
        });
    </script>
</body>
</html> 