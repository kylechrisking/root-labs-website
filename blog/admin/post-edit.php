<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once './includes/header.php';

$id = $_GET['id'] ?? null;
$post = null;
$tags = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $excerpt = filter_input(INPUT_POST, 'excerpt', FILTER_SANITIZE_STRING);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $tags = explode(',', $_POST['tags']);
    
    try {
        $db->beginTransaction();
        
        // Handle image upload
        $featured_image = null;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload = handleImageUpload($_FILES['featured_image']);
            if ($upload['success']) {
                $featured_image = $upload['path'];
            } else {
                throw new Exception($upload['error']);
            }
        }
        
        // Generate slug
        $slug = generateSlug($title);
        if ($id) {
            // Check if slug exists for other posts
            $stmt = $db->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $id]);
        } else {
            $stmt = $db->prepare("SELECT id FROM posts WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        if ($stmt->fetch()) {
            $slug = $slug . '-' . time();
        }
        
        if ($id) {
            // Update existing post
            $sql = "UPDATE posts SET 
                    title = ?, content = ?, excerpt = ?, category_id = ?, 
                    status = ?, slug = ?, updated_at = NOW()";
            $params = [$title, $content, $excerpt, $category_id, $status, $slug];
            
            if ($featured_image) {
                $sql .= ", featured_image = ?";
                $params[] = $featured_image;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
        } else {
            // Create new post
            $sql = "INSERT INTO posts (title, content, excerpt, category_id, status, 
                    slug, author_id, featured_image, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $title, $content, $excerpt, $category_id, $status,
                $slug, $currentUser['id'], $featured_image
            ]);
            $id = $db->lastInsertId();
        }
        
        // Handle tags
        if ($id) {
            // Remove old tags
            $stmt = $db->prepare("DELETE FROM post_tags WHERE post_id = ?");
            $stmt->execute([$id]);
            
            // Add new tags
            foreach ($tags as $tag_name) {
                if (!empty(trim($tag_name))) {
                    // Get or create tag
                    $tag_slug = generateSlug($tag_name);
                    $stmt = $db->prepare("SELECT id FROM tags WHERE slug = ?");
                    $stmt->execute([$tag_slug]);
                    $tag = $stmt->fetch();
                    
                    if (!$tag) {
                        $stmt = $db->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
                        $stmt->execute([trim($tag_name), $tag_slug]);
                        $tag_id = $db->lastInsertId();
                    } else {
                        $tag_id = $tag['id'];
                    }
                    
                    // Link tag to post
                    $stmt = $db->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                    $stmt->execute([$id, $tag_id]);
                }
            }
        }
        
        $db->commit();
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Post saved successfully'];
        header('Location: posts.php');
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Error saving post: ' . $e->getMessage()];
    }
}

// Get post data for editing
if ($id) {
    $stmt = $db->prepare("
        SELECT p.*, GROUP_CONCAT(t.name) as tags
        FROM posts p
        LEFT JOIN post_tags pt ON p.id = pt.post_id
        LEFT JOIN tags t ON pt.tag_id = t.id
        WHERE p.id = ?
        GROUP BY p.id
    ");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if ($post) {
        $tags = $post['tags'] ? explode(',', $post['tags']) : [];
    }
}

// Get categories
$categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">
        <?php echo $id ? 'Edit Post' : 'New Post'; ?>
    </h1>
</div>

<form method="post" enctype="multipart/form-data" class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" required
                   value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>"
                   class="form-input">
        </div>

        <!-- Content -->
        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <textarea id="content" name="content" rows="20" required
                              onkeyup="updatePreview(this.value)"
                              class="form-textarea font-mono"><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                </div>
                <div>
                    <div id="preview" class="prose max-w-none p-4 border rounded-lg overflow-y-auto" style="height: 500px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Excerpt -->
        <div class="mb-6">
            <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3"
                      class="form-textarea"><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category_id" name="category_id" required class="form-select">
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                                <?php echo ($post['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" required class="form-select">
                    <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                    <option value="private" <?php echo ($post['status'] ?? '') === 'private' ? 'selected' : ''; ?>>Private</option>
                </select>
            </div>
        </div>

        <!-- Tags -->
        <div class="mt-6">
            <label for="tagInput" class="block text-sm font-medium text-gray-700">Tags</label>
            <div class="tag-container">
                <div id="tagContainer" class="flex flex-wrap gap-2">
                    <!-- Tags will be inserted here -->
                </div>
                <input type="text" id="tagInput" placeholder="Add tags..."
                       class="tag-input">
                <input type="hidden" id="tags" name="tags" 
                       value="<?php echo htmlspecialchars(implode(',', $tags)); ?>">
            </div>
        </div>

        <!-- Featured Image -->
        <div class="mt-6">
            <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
            <?php if (!empty($post['featured_image'])): ?>
                <div class="mt-2">
                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                         alt="" class="image-preview" id="imagePreview">
                </div>
            <?php else: ?>
                <img src="" alt="" class="image-preview hidden" id="imagePreview">
            <?php endif; ?>
            <input type="file" id="featured_image" name="featured_image" accept="image/*"
                   onchange="handleFileSelect(event)"
                   class="mt-1 block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700
                          hover:file:bg-indigo-100">
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <a href="posts.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Post</button>
    </div>
</form>

<script>
    // Initialize markdown preview
    updatePreview(document.getElementById('content').value);
</script>

<?php require_once './includes/footer.php'; ?> 