<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Get category slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (empty($slug)) {
    header('Location: index.php');
    exit;
}

// Get category details
$category_query = "SELECT * FROM categories WHERE slug = ?";
$stmt = $conn->prepare($category_query);
$stmt->bind_param("s", $slug);
$stmt->execute();
$category_result = $stmt->get_result();
$category = $category_result->fetch_assoc();

if (!$category) {
    header('Location: 404.php');
    exit;
}

// Get current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total number of posts in category
$total_posts_query = "SELECT COUNT(*) as count FROM posts WHERE category_id = ? AND status = 'published'";
$stmt = $conn->prepare($total_posts_query);
$stmt->bind_param("i", $category['id']);
$stmt->execute();
$total_posts_result = $stmt->get_result();
$total_posts = $total_posts_result->fetch_assoc()['count'];
$total_pages = ceil($total_posts / $per_page);

// Get posts for current page
$posts_query = "SELECT p.*, u.username as author_name 
                FROM posts p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.category_id = ? AND p.status = 'published' 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";
$stmt = $conn->prepare($posts_query);
$stmt->bind_param("iii", $category['id'], $per_page, $offset);
$stmt->execute();
$posts_result = $stmt->get_result();
$posts = $posts_result->fetch_all(MYSQLI_ASSOC);

// Get popular tags for sidebar
$tags_query = "SELECT t.*, COUNT(pt.post_id) as post_count 
               FROM tags t 
               LEFT JOIN post_tags pt ON t.id = pt.tag_id 
               LEFT JOIN posts p ON pt.post_id = p.id AND p.status = 'published' 
               GROUP BY t.id 
               ORDER BY post_count DESC 
               LIMIT 20";
$tags_result = $conn->query($tags_query);
$tags = $tags_result->fetch_all(MYSQLI_ASSOC);

// Include header
include 'includes/header.php';
?>

<div class="blog-hero">
    <div class="container">
        <h1 class="blog-title"><?php echo htmlspecialchars($category['name']); ?></h1>
        <p class="blog-subtitle"><?php echo htmlspecialchars($category['description']); ?></p>
    </div>
</div>

<div class="container">
    <div class="blog-layout">
        <main class="posts-grid">
            <?php if (empty($posts)): ?>
                <div class="no-posts">
                    <h2>No Posts Found</h2>
                    <p>No posts have been published in this category yet.</p>
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
                            </div>
                            <p class="post-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                            <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">Read More</a>
                        </div>
                    </article>
                <?php endforeach; ?>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?slug=<?php echo htmlspecialchars($slug); ?>&page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?slug=<?php echo htmlspecialchars($slug); ?>&page=<?php echo $i; ?>" class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?slug=<?php echo htmlspecialchars($slug); ?>&page=<?php echo $page + 1; ?>" class="page-link">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>

        <aside class="blog-sidebar">
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