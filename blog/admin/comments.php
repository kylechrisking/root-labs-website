<?php
require_once 'includes/header.php';

// Handle comment actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    switch ($action) {
        case 'delete':
            if ($id > 0) {
                try {
                    $db->prepare("DELETE FROM comments WHERE id = ?")->execute([$id]);
                    setFlashMessage('success', 'Comment deleted successfully.');
                } catch (Exception $e) {
                    setFlashMessage('error', 'Error deleting comment: ' . $e->getMessage());
                }
            }
            break;
        case 'approve':
            if ($id > 0) {
                try {
                    $db->prepare("UPDATE comments SET status = 'approved' WHERE id = ?")->execute([$id]);
                    setFlashMessage('success', 'Comment approved successfully.');
                } catch (Exception $e) {
                    setFlashMessage('error', 'Error approving comment: ' . $e->getMessage());
                }
            }
            break;
        case 'spam':
            if ($id > 0) {
                try {
                    $db->prepare("UPDATE comments SET status = 'spam' WHERE id = ?")->execute([$id]);
                    setFlashMessage('success', 'Comment marked as spam.');
                } catch (Exception $e) {
                    setFlashMessage('error', 'Error marking comment as spam: ' . $e->getMessage());
                }
            }
            break;
    }

    // Redirect to prevent form resubmission
    redirect('comments.php');
}

// Get filter parameters
$status = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$query = "
    SELECT c.*, p.title as post_title, u.username as author_name
    FROM comments c
    LEFT JOIN posts p ON c.post_id = p.id
    LEFT JOIN users u ON c.user_id = u.id
    WHERE 1=1
";
$params = [];

if ($status !== 'all') {
    $query .= " AND c.status = ?";
    $params[] = $status;
}

if ($search) {
    $query .= " AND (c.content LIKE ? OR p.title LIKE ? OR u.username LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
}

$query .= " ORDER BY c.created_at DESC";

// Get comments
$stmt = $db->prepare($query);
$stmt->execute($params);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get comment counts by status
$counts = $db->query("
    SELECT status, COUNT(*) as count
    FROM comments
    GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Comments</h1>
        <div class="flex space-x-4">
            <form method="GET" class="flex space-x-2">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search comments..." 
                       class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>All</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="spam" <?php echo $status === 'spam' ? 'selected' : ''; ?>>Spam</option>
                </select>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex space-x-4">
                <a href="?status=all" class="text-sm font-medium <?php echo $status === 'all' ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700'; ?>">
                    All (<?php echo array_sum($counts); ?>)
                </a>
                <a href="?status=pending" class="text-sm font-medium <?php echo $status === 'pending' ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700'; ?>">
                    Pending (<?php echo $counts['pending'] ?? 0; ?>)
                </a>
                <a href="?status=approved" class="text-sm font-medium <?php echo $status === 'approved' ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700'; ?>">
                    Approved (<?php echo $counts['approved'] ?? 0; ?>)
                </a>
                <a href="?status=spam" class="text-sm font-medium <?php echo $status === 'spam' ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700'; ?>">
                    Spam (<?php echo $counts['spam'] ?? 0; ?>)
                </a>
            </div>
        </div>

        <?php if (empty($comments)): ?>
            <div class="p-6">
                <p class="text-gray-500">No comments found.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-200">
                <?php foreach ($comments as $comment): ?>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php echo $comment['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                            ($comment['status'] === 'spam' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                        <?php echo ucfirst($comment['status']); ?>
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($comment['author_name'] ?? 'Anonymous'); ?>
                                    </span>
                                    <span class="text-sm text-gray-500">•</span>
                                    <span class="text-sm text-gray-500">
                                        <?php echo date('M j, Y g:i a', strtotime($comment['created_at'])); ?>
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-900">
                                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    On post: <a href="../post.php?slug=<?php echo htmlspecialchars($comment['post_slug']); ?>" class="text-indigo-600 hover:text-indigo-900"><?php echo htmlspecialchars($comment['post_title']); ?></a>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <?php if ($comment['status'] === 'pending'): ?>
                                    <a href="?action=approve&id=<?php echo $comment['id']; ?>" class="text-green-600 hover:text-green-900">Approve</a>
                                <?php endif; ?>
                                <?php if ($comment['status'] !== 'spam'): ?>
                                    <a href="?action=spam&id=<?php echo $comment['id']; ?>" class="text-yellow-600 hover:text-yellow-900">Mark as Spam</a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $comment['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 