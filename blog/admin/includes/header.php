<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Check if user is logged in and is an admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Root Labs Blog</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                     alignleft aligncenter alignright alignjustify | \
                     bullist numlist outdent indent | removeformat | help'
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h1 class="text-xl font-bold">Root Labs Blog</h1>
                <p class="text-sm text-gray-400">Admin Dashboard</p>
            </div>
            <nav class="mt-4">
                <a href="index.php" class="block py-2 px-4 <?php echo $current_page === 'index.php' ? 'bg-gray-700' : 'hover:bg-gray-700'; ?>">
                    Dashboard
                </a>
                <a href="posts.php" class="block py-2 px-4 <?php echo $current_page === 'posts.php' ? 'bg-gray-700' : 'hover:bg-gray-700'; ?>">
                    Posts
                </a>
                <a href="categories.php" class="block py-2 px-4 <?php echo $current_page === 'categories.php' ? 'bg-gray-700' : 'hover:bg-gray-700'; ?>">
                    Categories
                </a>
                <a href="tags.php" class="block py-2 px-4 <?php echo $current_page === 'tags.php' ? 'bg-gray-700' : 'hover:bg-gray-700'; ?>">
                    Tags
                </a>
                <a href="comments.php" class="block py-2 px-4 <?php echo $current_page === 'comments.php' ? 'bg-gray-700' : 'hover:bg-gray-700'; ?>">
                    Comments
                </a>
                <a href="users.php" class="block py-2 px-4 <?php echo $current_page === 'users.php' ? 'bg-gray-700' : 'hover:bg-gray-700'; ?>">
                    Users
                </a>
                <a href="settings.php" class="block py-2 px-4 <?php echo $current_page === 'settings.php' ? 'bg-gray-700' : 'hover:bg-gray-700'; ?>">
                    Settings
                </a>
            </nav>
            <div class="mt-auto p-4 border-t border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="inline-block h-8 w-8 rounded-full bg-gray-500"></span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <a href="logout.php" class="text-xs text-gray-400 hover:text-white">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1">
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold text-gray-900">
                        <?php
                        switch ($current_page) {
                            case 'index.php':
                                echo 'Dashboard';
                                break;
                            case 'posts.php':
                                echo 'Posts';
                                break;
                            case 'categories.php':
                                echo 'Categories';
                                break;
                            case 'tags.php':
                                echo 'Tags';
                                break;
                            case 'comments.php':
                                echo 'Comments';
                                break;
                            case 'users.php':
                                echo 'Users';
                                break;
                            case 'settings.php':
                                echo 'Settings';
                                break;
                            default:
                                echo 'Admin Dashboard';
                        }
                        ?>
                    </h1>
                </div>
            </header>

            <?php
            // Display flash messages
            $flash = getFlashMessage();
            if ($flash): ?>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-md p-4 <?php echo $flash['type'] === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'; ?>">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <?php if ($flash['type'] === 'success'): ?>
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium"><?php echo htmlspecialchars($flash['message']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <main class="max-w-7xl mx-auto py-4 sm:px-6 lg:px-8"> 