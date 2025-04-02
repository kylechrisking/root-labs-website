<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/db.php';

try {
    // Get current page number
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    // Get total number of published posts
    $total_posts_query = "SELECT COUNT(*) as count FROM posts WHERE status = 'published'";
    $total_posts_result = $db->query($total_posts_query);
    $total_posts = $total_posts_result->fetch(PDO::FETCH_ASSOC)['count'];
    $total_pages = ceil($total_posts / $per_page);

    // Get posts for current page
    $posts_query = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.username as author_name 
                    FROM posts p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN users u ON p.user_id = u.id 
                    WHERE p.status = 'published' 
                    ORDER BY p.created_at DESC 
                    LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($posts_query);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get categories with post counts
    $categories_query = "SELECT c.*, COUNT(p.id) as post_count 
                        FROM categories c 
                        LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published' 
                        GROUP BY c.id 
                        ORDER BY post_count DESC";
    $categories_result = $db->query($categories_query);
    $categories = $categories_result->fetchAll(PDO::FETCH_ASSOC);

    // Get popular tags
    $tags_query = "SELECT t.*, COUNT(pt.post_id) as post_count 
                   FROM tags t 
                   LEFT JOIN post_tags pt ON t.id = pt.tag_id 
                   LEFT JOIN posts p ON pt.post_id = p.id AND p.status = 'published' 
                   GROUP BY t.id 
                   ORDER BY post_count DESC 
                   LIMIT 20";
    $tags_result = $db->query($tags_query);
    $tags = $tags_result->fetchAll(PDO::FETCH_ASSOC);

    // Include header
    include 'includes/header.php';
    ?>

    <div class="blog-hero">
        <div class="container">
            <h1 class="blog-title">Root Labs Blog</h1>
            <p class="blog-subtitle">Insights, tutorials, and updates from the Root Labs team</p>
        </div>
    </div>

    <div class="container">
        <div class="blog-layout">
            <main class="posts-grid">
                <?php if (empty($posts)): ?>
                    <div class="no-posts">
                        <h2>No Posts Found</h2>
                        <p>Check back soon for new content!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card">
                            <?php if ($post['featured_image']): ?>
                                <div class="post-image">
                                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h2 class="post-title">
                                    <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h2>
                                <div class="post-meta">
                                    <span class="post-date"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                                    <span class="post-author">by <?php echo htmlspecialchars($post['author_name']); ?></span>
                                    <?php if ($post['category_name']): ?>
                                    <a href="category.php?slug=<?php echo htmlspecialchars($post['category_slug']); ?>" class="category-link">
                                        <?php echo htmlspecialchars($post['category_name']); ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <p class="post-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">Read More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>

                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>" class="page-link">Next</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </main>

            <aside class="blog-sidebar">
                <div class="sidebar-widget">
                    <h3 class="widget-title">Categories</h3>
                    <ul class="category-list">
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="category.php?slug=<?php echo htmlspecialchars($category['slug']); ?>" class="category-link">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                    <span class="count"><?php echo $category['post_count']; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">Popular Tags</h3>
                    <div class="tag-cloud">
                        <?php foreach ($tags as $tag): ?>
                            <a href="tag.php?slug=<?php echo htmlspecialchars($tag['slug']); ?>" class="tag-link">
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">Follow Us</h3>
                    <div class="social-links">
                        <a href="https://github.com/rootlabs" class="social-link" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="https://twitter.com/rootlabs" class="social-link" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://linkedin.com/company/rootlabs" class="social-link" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
<?php
} catch (PDOException $e) {
    // Log the error
    error_log("Database error: " . $e->getMessage());
    
    // Display a user-friendly error message
    include '500.php';
}
?> 