<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once './includes/header.php';

// Get statistics
$stats = [
    'posts' => $db->query("SELECT COUNT(*) FROM posts")->fetchColumn(),
    'comments' => $db->query("SELECT COUNT(*) FROM comments")->fetchColumn(),
    'categories' => $db->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'pending_comments' => $db->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn()
];

// Get recent posts
$recentPosts = $db->query("
    SELECT p.*, u.username 
    FROM posts p 
    JOIN users u ON p.author_id = u.id 
    ORDER BY p.created_at DESC 
    LIMIT 5
")->fetchAll();

// Get recent comments
$recentComments = $db->query("
    SELECT c.*, p.title as post_title 
    FROM comments c 
    JOIN posts p ON c.post_id = p.id 
    ORDER BY c.created_at DESC 
    LIMIT 5
")->fetchAll();
?>

<h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>

<!-- Statistics -->
<div class="mt-4">
    <div class="grid grid-cols-1 gap-5 mt-2 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Posts count -->
        <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-400">Total Posts</span>
                    <span class="text-lg font-semibold"><?php echo $stats['posts']; ?></span>
                </div>
                <div class="p-2 bg-gray-100 rounded-md">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Comments count -->
        <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-400">Total Comments</span>
                    <span class="text-lg font-semibold"><?php echo $stats['comments']; ?></span>
                </div>
                <div class="p-2 bg-gray-100 rounded-md">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Categories count -->
        <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-400">Categories</span>
                    <span class="text-lg font-semibold"><?php echo $stats['categories']; ?></span>
                </div>
                <div class="p-2 bg-gray-100 rounded-md">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending comments -->
        <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-400">Pending Comments</span>
                    <span class="text-lg font-semibold"><?php echo $stats['pending_comments']; ?></span>
                </div>
                <div class="p-2 bg-gray-100 rounded-md">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 gap-5 mt-5 lg:grid-cols-2">
    <!-- Recent Posts -->
    <div class="p-4 bg-white border rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold text-gray-900">Recent Posts</h2>
        <div class="mt-4 space-y-4">
            <?php foreach ($recentPosts as $post): ?>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">
                        <a href="../post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="hover:underline">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500">
                        by <?php echo htmlspecialchars($post['username']); ?> • 
                        <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                    </p>
                </div>
                <a href="posts.php?action=edit&id=<?php echo $post['id']; ?>" class="text-indigo-600 hover:text-indigo-900">
                    Edit
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4">
            <a href="posts.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                View all posts →
            </a>
        </div>
    </div>

    <!-- Recent Comments -->
    <div class="p-4 bg-white border rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold text-gray-900">Recent Comments</h2>
        <div class="mt-4 space-y-4">
            <?php foreach ($recentComments as $comment): ?>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">
                        <?php echo htmlspecialchars($comment['author_name']); ?>
                        on
                        <a href="../post.php?slug=<?php echo htmlspecialchars($comment['post_title']); ?>" class="hover:underline">
                            <?php echo htmlspecialchars($comment['post_title']); ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500">
                        <?php echo date('M j, Y', strtotime($comment['created_at'])); ?> •
                        <span class="capitalize"><?php echo $comment['status']; ?></span>
                    </p>
                </div>
                <a href="comments.php?action=edit&id=<?php echo $comment['id']; ?>" class="text-indigo-600 hover:text-indigo-900">
                    Review
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4">
            <a href="comments.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                View all comments →
            </a>
        </div>
    </div>
</div>

<?php require_once './includes/footer.php'; ?> 