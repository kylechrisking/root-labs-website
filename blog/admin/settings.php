<?php
require_once 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => trim($_POST['site_name'] ?? ''),
        'site_description' => trim($_POST['site_description'] ?? ''),
        'site_url' => trim($_POST['site_url'] ?? ''),
        'admin_email' => trim($_POST['admin_email'] ?? ''),
        'posts_per_page' => (int)($_POST['posts_per_page'] ?? 10),
        'comments_per_page' => (int)($_POST['comments_per_page'] ?? 20),
        'allow_comments' => isset($_POST['allow_comments']),
        'moderate_comments' => isset($_POST['moderate_comments']),
        'allow_registration' => isset($_POST['allow_registration']),
        'default_user_role' => trim($_POST['default_user_role'] ?? 'user'),
        'maintenance_mode' => isset($_POST['maintenance_mode']),
        'maintenance_message' => trim($_POST['maintenance_message'] ?? ''),
        'google_analytics_id' => trim($_POST['google_analytics_id'] ?? ''),
        'disqus_shortname' => trim($_POST['disqus_shortname'] ?? ''),
        'recaptcha_site_key' => trim($_POST['recaptcha_site_key'] ?? ''),
        'recaptcha_secret_key' => trim($_POST['recaptcha_secret_key'] ?? '')
    ];

    $errors = [];

    // Validate input
    if (empty($settings['site_name'])) {
        $errors[] = 'Site name is required.';
    }
    if (empty($settings['site_url'])) {
        $errors[] = 'Site URL is required.';
    } elseif (!filter_var($settings['site_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'Invalid site URL format.';
    }
    if (empty($settings['admin_email'])) {
        $errors[] = 'Admin email is required.';
    } elseif (!filter_var($settings['admin_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid admin email format.';
    }
    if ($settings['posts_per_page'] < 1) {
        $errors[] = 'Posts per page must be at least 1.';
    }
    if ($settings['comments_per_page'] < 1) {
        $errors[] = 'Comments per page must be at least 1.';
    }
    if (!in_array($settings['default_user_role'], ['user', 'admin'])) {
        $errors[] = 'Invalid default user role.';
    }

    if (empty($errors)) {
        try {
            // Update settings using the new function
            $success = true;
            foreach ($settings as $name => $value) {
                if (!updateSetting($name, $value)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                setFlashMessage('success', 'Settings updated successfully.');
                redirect('settings.php');
            } else {
                $errors[] = 'Error saving settings.';
            }
        } catch (Exception $e) {
            $errors[] = 'Error saving settings: ' . $e->getMessage();
        }
    }
}

// Get current settings using the new function
$settings = getAllSettings();

// Set default values if not set
$defaults = [
    'site_name' => 'Root Labs Blog',
    'site_description' => 'Technology news and updates',
    'site_url' => 'https://rootlabs.us',
    'admin_email' => 'admin@rootlabs.us',
    'posts_per_page' => 10,
    'comments_per_page' => 20,
    'allow_comments' => true,
    'moderate_comments' => true,
    'allow_registration' => true,
    'default_user_role' => 'user',
    'maintenance_mode' => false,
    'maintenance_message' => 'Site is under maintenance. Please check back later.',
    'google_analytics_id' => '',
    'disqus_shortname' => '',
    'recaptcha_site_key' => '',
    'recaptcha_secret_key' => ''
];

$settings = array_merge($defaults, $settings);
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Settings</h1>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form method="POST" class="space-y-6 p-6">
            <!-- General Settings -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">General Settings</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input type="text" name="site_name" id="site_name" required
                               value="<?php echo htmlspecialchars($settings['site_name']); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="site_url" class="block text-sm font-medium text-gray-700">Site URL</label>
                        <input type="url" name="site_url" id="site_url" required
                               value="<?php echo htmlspecialchars($settings['site_url']); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                        <textarea name="site_description" id="site_description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
                    </div>
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-700">Admin Email</label>
                        <input type="email" name="admin_email" id="admin_email" required
                               value="<?php echo htmlspecialchars($settings['admin_email']); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Content Settings -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Content Settings</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="posts_per_page" class="block text-sm font-medium text-gray-700">Posts Per Page</label>
                        <input type="number" name="posts_per_page" id="posts_per_page" required min="1"
                               value="<?php echo (int)$settings['posts_per_page']; ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="comments_per_page" class="block text-sm font-medium text-gray-700">Comments Per Page</label>
                        <input type="number" name="comments_per_page" id="comments_per_page" required min="1"
                               value="<?php echo (int)$settings['comments_per_page']; ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Comment Settings -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Comment Settings</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="allow_comments" id="allow_comments"
                               <?php echo $settings['allow_comments'] ? 'checked' : ''; ?>
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="allow_comments" class="ml-2 block text-sm text-gray-900">
                            Allow Comments
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="moderate_comments" id="moderate_comments"
                               <?php echo $settings['moderate_comments'] ? 'checked' : ''; ?>
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="moderate_comments" class="ml-2 block text-sm text-gray-900">
                            Moderate Comments
                        </label>
                    </div>
                </div>
            </div>

            <!-- User Settings -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">User Settings</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="allow_registration" id="allow_registration"
                               <?php echo $settings['allow_registration'] ? 'checked' : ''; ?>
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="allow_registration" class="ml-2 block text-sm text-gray-900">
                            Allow User Registration
                        </label>
                    </div>
                    <div>
                        <label for="default_user_role" class="block text-sm font-medium text-gray-700">Default User Role</label>
                        <select name="default_user_role" id="default_user_role"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="user" <?php echo $settings['default_user_role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $settings['default_user_role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Maintenance Mode</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode"
                               <?php echo $settings['maintenance_mode'] ? 'checked' : ''; ?>
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                            Enable Maintenance Mode
                        </label>
                    </div>
                    <div>
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700">Maintenance Message</label>
                        <textarea name="maintenance_message" id="maintenance_message" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"><?php echo htmlspecialchars($settings['maintenance_message']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Integration Settings -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Integration Settings</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="google_analytics_id" class="block text-sm font-medium text-gray-700">Google Analytics ID</label>
                        <input type="text" name="google_analytics_id" id="google_analytics_id"
                               value="<?php echo htmlspecialchars($settings['google_analytics_id']); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="disqus_shortname" class="block text-sm font-medium text-gray-700">Disqus Shortname</label>
                        <input type="text" name="disqus_shortname" id="disqus_shortname"
                               value="<?php echo htmlspecialchars($settings['disqus_shortname']); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700">reCAPTCHA Site Key</label>
                        <input type="text" name="recaptcha_site_key" id="recaptcha_site_key"
                               value="<?php echo htmlspecialchars($settings['recaptcha_site_key']); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700">reCAPTCHA Secret Key</label>
                        <input type="text" name="recaptcha_secret_key" id="recaptcha_secret_key"
                               value="<?php echo htmlspecialchars($settings['recaptcha_secret_key']); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 