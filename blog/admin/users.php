<?php
require_once 'includes/header.php';

// Handle user actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    switch ($action) {
        case 'delete':
            if ($id > 0) {
                try {
                    // Check if user has posts
                    $stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ?");
                    $stmt->execute([$id]);
                    if ($stmt->fetchColumn() > 0) {
                        setFlashMessage('error', 'Cannot delete user with existing posts.');
                        break;
                    }

                    $db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
                    setFlashMessage('success', 'User deleted successfully.');
                } catch (Exception $e) {
                    setFlashMessage('error', 'Error deleting user: ' . $e->getMessage());
                }
            }
            break;
        case 'block':
            if ($id > 0) {
                try {
                    $db->prepare("UPDATE users SET status = 'blocked' WHERE id = ?")->execute([$id]);
                    setFlashMessage('success', 'User blocked successfully.');
                } catch (Exception $e) {
                    setFlashMessage('error', 'Error blocking user: ' . $e->getMessage());
                }
            }
            break;
        case 'unblock':
            if ($id > 0) {
                try {
                    $db->prepare("UPDATE users SET status = 'active' WHERE id = ?")->execute([$id]);
                    setFlashMessage('success', 'User unblocked successfully.');
                } catch (Exception $e) {
                    setFlashMessage('error', 'Error unblocking user: ' . $e->getMessage());
                }
            }
            break;
    }

    // Redirect to prevent form resubmission
    redirect('users.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'user');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $errors = [];

    // Validate input
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($password) && $id === 0) {
        $errors[] = 'Password is required for new users.';
    }
    if (!in_array($role, ['user', 'admin'])) {
        $errors[] = 'Invalid role.';
    }

    // Check if username or email is unique
    $stmt = $db->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $stmt->execute([$username, $email, $id]);
    if ($stmt->fetch()) {
        $errors[] = 'Username or email already exists.';
    }

    if (empty($errors)) {
        try {
            if ($id > 0) {
                // Update existing user
                if (!empty($password)) {
                    $stmt = $db->prepare("
                        UPDATE users 
                        SET username = ?, email = ?, password = ?, role = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role, $id]);
                } else {
                    $stmt = $db->prepare("
                        UPDATE users 
                        SET username = ?, email = ?, role = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, $role, $id]);
                }
                setFlashMessage('success', 'User updated successfully.');
            } else {
                // Create new user
                $stmt = $db->prepare("
                    INSERT INTO users (username, email, password, role, status)
                    VALUES (?, ?, ?, ?, 'active')
                ");
                $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
                setFlashMessage('success', 'User created successfully.');
            }
            redirect('users.php');
        } catch (Exception $e) {
            $errors[] = 'Error saving user: ' . $e->getMessage();
        }
    }
}

// Get users with post and comment counts
$users = $db->query("
    SELECT u.*, 
           COUNT(DISTINCT p.id) as post_count,
           COUNT(DISTINCT c.id) as comment_count
    FROM users u
    LEFT JOIN posts p ON u.id = p.user_id
    LEFT JOIN comments c ON u.id = c.user_id
    GROUP BY u.id
    ORDER BY u.username
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Users</h1>
        <button type="button" onclick="openModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add User
        </button>
    </div>

    <?php if (empty($users)): ?>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-500">No users found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $user['post_count']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $user['comment_count']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="openModal(<?php echo htmlspecialchars(json_encode($user)); ?>)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <?php if ($user['status'] === 'active'): ?>
                                        <a href="?action=block&id=<?php echo $user['id']; ?>" class="text-yellow-600 hover:text-yellow-900">Block</a>
                                    <?php else: ?>
                                        <a href="?action=unblock&id=<?php echo $user['id']; ?>" class="text-green-600 hover:text-green-900">Unblock</a>
                                    <?php endif; ?>
                                    <?php if ($user['post_count'] == 0 && $user['comment_count'] == 0): ?>
                                        <a href="?action=delete&id=<?php echo $user['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
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
<div id="userModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form id="userForm" method="POST" class="space-y-4">
                <input type="hidden" name="id" id="userId">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep existing password</p>
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
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
function openModal(user = null) {
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const roleInput = document.getElementById('role');
    const idInput = document.getElementById('userId');

    if (user) {
        usernameInput.value = user.username;
        emailInput.value = user.email;
        passwordInput.value = '';
        roleInput.value = user.role;
        idInput.value = user.id;
    } else {
        form.reset();
        idInput.value = '';
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('userModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php require_once 'includes/footer.php'; ?> 