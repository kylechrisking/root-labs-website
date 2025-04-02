<?php
require_once 'includes/header.php';

// Handle tag actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    switch ($action) {
        case 'delete':
            if ($id > 0) {
                try {
                    // Check if tag has posts
                    $stmt = $db->prepare("SELECT COUNT(*) FROM post_tags WHERE tag_id = ?");
                    $stmt->execute([$id]);
                    if ($stmt->fetchColumn() > 0) {
                        setFlashMessage('error', 'Cannot delete tag with existing posts.');
                        break;
                    }

                    $db->prepare("DELETE FROM tags WHERE id = ?")->execute([$id]);
                    setFlashMessage('success', 'Tag deleted successfully.');
                } catch (Exception $e) {
                    setFlashMessage('error', 'Error deleting tag: ' . $e->getMessage());
                }
            }
            break;
    }

    // Redirect to prevent form resubmission
    redirect('tags.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $errors = [];

    // Validate input
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($slug)) {
        $slug = generateSlug($name);
    }

    // Check if slug is unique
    $stmt = $db->prepare("SELECT id FROM tags WHERE slug = ? AND id != ?");
    $stmt->execute([$slug, $id]);
    if ($stmt->fetch()) {
        $errors[] = 'A tag with this slug already exists.';
    }

    if (empty($errors)) {
        try {
            if ($id > 0) {
                // Update existing tag
                $stmt = $db->prepare("
                    UPDATE tags 
                    SET name = ?, slug = ?, description = ?
                    WHERE id = ?
                ");
                $stmt->execute([$name, $slug, $description, $id]);
                setFlashMessage('success', 'Tag updated successfully.');
            } else {
                // Create new tag
                $stmt = $db->prepare("
                    INSERT INTO tags (name, slug, description)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$name, $slug, $description]);
                setFlashMessage('success', 'Tag created successfully.');
            }
            redirect('tags.php');
        } catch (Exception $e) {
            $errors[] = 'Error saving tag: ' . $e->getMessage();
        }
    }
}

// Get tags with post count
$tags = $db->query("
    SELECT t.*, COUNT(pt.post_id) as post_count
    FROM tags t
    LEFT JOIN post_tags pt ON t.id = pt.tag_id
    GROUP BY t.id
    ORDER BY t.name
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Tags</h1>
        <button type="button" onclick="openModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Tag
        </button>
    </div>

    <?php if (empty($tags)): ?>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-500">No tags found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($tags as $tag): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars($tag['slug']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars($tag['description']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $tag['post_count']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="openModal(<?php echo htmlspecialchars(json_encode($tag)); ?>)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <?php if ($tag['post_count'] == 0): ?>
                                        <a href="tags.php?action=delete&id=<?php echo $tag['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this tag?')">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="tagModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form id="tagForm" method="POST" class="space-y-4">
                <input type="hidden" name="id" id="tagId">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to generate from name</p>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                        Save
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(tag = null) {
    const modal = document.getElementById('tagModal');
    const form = document.getElementById('tagForm');
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const descriptionInput = document.getElementById('description');
    const idInput = document.getElementById('tagId');

    if (tag) {
        nameInput.value = tag.name;
        slugInput.value = tag.slug;
        descriptionInput.value = tag.description;
        idInput.value = tag.id;
    } else {
        form.reset();
        idInput.value = '';
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('tagModal');
    modal.classList.add('hidden');
}

document.getElementById('name').addEventListener('input', function() {
    const slugInput = document.getElementById('slug');
    if (!slugInput.value) {
        slugInput.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});

// Close modal when clicking outside
document.getElementById('tagModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php require_once 'includes/footer.php'; ?> 