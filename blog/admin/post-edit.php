<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once './includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = null;
$errors = [];

// Get categories
$categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Get tags
$tags = $db->query("SELECT id, name FROM tags ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if ($id > 0) {
    // Get existing post
    $stmt = $db->prepare("
        SELECT p.*, GROUP_CONCAT(pt.tag_id) as tag_ids
        FROM posts p
        LEFT JOIN post_tags pt ON p.id = pt.post_id
        WHERE p.id = ?
        GROUP BY p.id
    ");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        setFlashMessage('error', 'Post not found.');
        redirect('posts.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $status = $_POST['status'] ?? 'draft';
    $selected_tags = isset($_POST['tags']) ? array_map('intval', $_POST['tags']) : [];

    // Validate input
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    if (empty($slug)) {
        $slug = generateSlug($title);
    }
    if (empty($content)) {
        $errors[] = 'Content is required.';
    }
    if ($category_id === 0) {
        $errors[] = 'Category is required.';
    }

    // Check if slug is unique
    $stmt = $db->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
    $stmt->execute([$slug, $id]);
    if ($stmt->fetch()) {
        $errors[] = 'A post with this slug already exists.';
    }

    if (empty($errors)) {
        try {
            $db->beginTransaction();

            if ($id > 0) {
                // Update existing post
                $stmt = $db->prepare("
                    UPDATE posts 
                    SET title = ?, slug = ?, content = ?, excerpt = ?, 
                        category_id = ?, status = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$title, $slug, $content, $excerpt, $category_id, $status, $id]);

                // Delete existing tags
                $db->prepare("DELETE FROM post_tags WHERE post_id = ?")->execute([$id]);
            } else {
                // Create new post
                $stmt = $db->prepare("
                    INSERT INTO posts (title, slug, content, excerpt, category_id, 
                                     status, user_id, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                $stmt->execute([$title, $slug, $content, $excerpt, $category_id, $status, $_SESSION['user_id']]);
                $id = $db->lastInsertId();
            }

            // Add tags
            if (!empty($selected_tags)) {
                $stmt = $db->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                foreach ($selected_tags as $tag_id) {
                    $stmt->execute([$id, $tag_id]);
                }
            }

            $db->commit();
            setFlashMessage('success', $id > 0 ? 'Post updated successfully.' : 'Post created successfully.');
            redirect('posts.php');
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = 'Error saving post: ' . $e->getMessage();
        }
    }
}

// Get post tags for editing
$post_tags = [];
if ($post) {
    $stmt = $db->prepare("SELECT tag_id FROM post_tags WHERE post_id = ?");
    $stmt->execute([$id]);
    $post_tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            <?php echo $id > 0 ? 'Edit Post' : 'Create New Post'; ?>
        </h1>
        <a href="posts.php" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Back to Posts
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" 
                           value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           required>
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug" 
                           value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to generate from title</p>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo ($post['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <div class="mt-2 space-y-2">
                        <?php foreach ($tags as $tag): ?>
                            <div class="flex items-center">
                                <input type="checkbox" name="tags[]" id="tag_<?php echo $tag['id']; ?>" 
                                       value="<?php echo $tag['id']; ?>"
                                       <?php echo in_array($tag['id'], $post_tags) ? 'checked' : ''; ?>
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="tag_<?php echo $tag['id']; ?>" class="ml-2 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="20" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                    <p class="mt-1 text-sm text-gray-500">A short description of the post</p>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="posts.php" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <?php echo $id > 0 ? 'Update Post' : 'Create Post'; ?>
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('title').addEventListener('input', function() {
    if (!document.getElementById('slug').value) {
        document.getElementById('slug').value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});
</script>

<?php require_once './includes/footer.php'; ?> 