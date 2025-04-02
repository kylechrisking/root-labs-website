<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_STRING);
if (!$slug) {
    header('Location: /blog');
    exit;
}

// Get post data
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name, c.slug as category_slug,
           u.username as author_name, GROUP_CONCAT(t.name) as tags,
           (SELECT COUNT(*) FROM post_votes WHERE post_id = p.id AND vote_type = 'up') as upvotes,
           (SELECT COUNT(*) FROM post_votes WHERE post_id = p.id AND vote_type = 'down') as downvotes
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN post_tags pt ON p.id = pt.post_id
    LEFT JOIN tags t ON pt.tag_id = t.id
    WHERE p.slug = ? AND p.status = 'published'
    GROUP BY p.id
");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: /blog');
    exit;
}

// Get user's vote if they've voted
$user_vote = null;
if (isset($_SERVER['REMOTE_ADDR'])) {
    $ip_hash = hash('sha256', $_SERVER['REMOTE_ADDR'] . VOTE_SALT);
    $stmt = $db->prepare("SELECT vote_type FROM post_votes WHERE post_id = ? AND ip_hash = ?");
    $stmt->execute([$post['id'], $ip_hash]);
    $vote = $stmt->fetch();
    if ($vote) {
        $user_vote = $vote['vote_type'];
    }
}

// Parse markdown content
require_once 'vendor/autoload.php';
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);
$content = $parsedown->text($post['content']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Root Labs Blog</title>
    <link href="/assets/css/tailwind.min.css" rel="stylesheet">
    <link href="/blog/assets/css/blog.css" rel="stylesheet">
    <link href="/blog/assets/css/markdown.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <article class="bg-white rounded-lg shadow-sm overflow-hidden">
            <?php if ($post['featured_image']): ?>
                <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                     alt="" class="w-full h-96 object-cover">
            <?php endif; ?>
            
            <div class="p-8">
                <header class="mb-8">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                        <span class="mx-2">•</span>
                        <a href="/blog/category/<?php echo htmlspecialchars($post['category_slug']); ?>" 
                           class="hover:text-indigo-600">
                            <?php echo htmlspecialchars($post['category_name']); ?>
                        </a>
                        <span class="mx-2">•</span>
                        <span>By <?php echo htmlspecialchars($post['author_name']); ?></span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>
                    <?php if ($post['tags']): ?>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach (explode(',', $post['tags']) as $tag): ?>
                                <a href="/blog/tag/<?php echo generateSlug($tag); ?>" 
                                   class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200">
                                    <?php echo htmlspecialchars($tag); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Post Content -->
                <div class="prose max-w-none">
                    <?php echo $content; ?>
                </div>

                <!-- Voting Section -->
                <div class="flex items-center space-x-4 mt-8 pt-8 border-t">
                    <div class="flex items-center">
                        <button onclick="vote('up')" 
                                class="vote-btn <?php echo $user_vote === 'up' ? 'text-green-600' : 'text-gray-400'; ?> hover:text-green-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                            </svg>
                            <span id="upvotes"><?php echo $post['upvotes']; ?></span>
                        </button>
                        <button onclick="vote('down')"
                                class="vote-btn ml-4 <?php echo $user_vote === 'down' ? 'text-red-600' : 'text-gray-400'; ?> hover:text-red-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z" />
                            </svg>
                            <span id="downvotes"><?php echo $post['downvotes']; ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Comments</h2>
            
            <?php if (isset($_SESSION['github_user'])): ?>
                <!-- Comment Form -->
                <form id="commentForm" class="mb-8">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700">Your comment</label>
                        <textarea id="content" name="content" rows="3" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Post Comment
                    </button>
                </form>
            <?php else: ?>
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <p class="text-gray-600 mb-4">Sign in with GitHub to join the discussion</p>
                    <a href="/blog/includes/github_auth.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-800 hover:bg-gray-900">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
                        </svg>
                        Sign in with GitHub
                    </a>
                </div>
            <?php endif; ?>

            <!-- Comments List -->
            <div id="commentsList" class="space-y-8">
                <!-- Comments will be loaded here -->
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Voting functionality
        async function vote(type) {
            try {
                const response = await fetch('/blog/includes/vote.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        post_id: <?php echo $post['id']; ?>,
                        vote_type: type
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    document.getElementById('upvotes').textContent = data.upvotes;
                    document.getElementById('downvotes').textContent = data.downvotes;
                    
                    // Update button styles
                    document.querySelectorAll('.vote-btn').forEach(btn => {
                        btn.classList.remove('text-green-600', 'text-red-600');
                        btn.classList.add('text-gray-400');
                    });
                    
                    if (data.user_vote === type) {
                        event.target.closest('.vote-btn').classList.add(
                            type === 'up' ? 'text-green-600' : 'text-red-600'
                        );
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Load comments
        async function loadComments() {
            try {
                const response = await fetch(`/blog/includes/comments.php?post_id=<?php echo $post['id']; ?>`);
                const data = await response.json();
                
                if (data.success) {
                    const commentsList = document.getElementById('commentsList');
                    commentsList.innerHTML = '';
                    
                    data.comments.forEach(comment => {
                        commentsList.appendChild(createCommentElement(comment));
                    });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Create comment HTML element
        function createCommentElement(comment) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-lg shadow-sm p-6';
            div.innerHTML = `
                <div class="flex items-start space-x-3">
                    <img src="${comment.avatar_url}" alt="" class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-900">${comment.username}</h3>
                            <time class="text-sm text-gray-500">${new Date(comment.created_at).toLocaleDateString()}</time>
                        </div>
                        <div class="mt-2 text-sm text-gray-700">
                            ${comment.content}
                        </div>
                        <div class="mt-2 flex items-center space-x-4">
                            <button onclick="voteComment(${comment.id}, 'up')" 
                                    class="text-sm text-gray-500 hover:text-green-600">
                                ↑ ${comment.upvotes}
                            </button>
                            <button onclick="voteComment(${comment.id}, 'down')" 
                                    class="text-sm text-gray-500 hover:text-red-600">
                                ↓ ${comment.downvotes}
                            </button>
                            <button onclick="replyTo(${comment.id})" 
                                    class="text-sm text-gray-500 hover:text-indigo-600">
                                Reply
                            </button>
                        </div>
                    </div>
                </div>
                ${comment.replies ? `
                    <div class="mt-4 ml-13 space-y-4">
                        ${comment.replies.map(reply => createCommentElement(reply).outerHTML).join('')}
                    </div>
                ` : ''}
            `;
            return div;
        }

        // Handle comment form submission
        const commentForm = document.getElementById('commentForm');
        if (commentForm) {
            commentForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                try {
                    const response = await fetch('/blog/includes/comments.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            post_id: <?php echo $post['id']; ?>,
                            content: commentForm.content.value,
                            parent_id: commentForm.dataset.replyTo || null
                        })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        commentForm.reset();
                        commentForm.dataset.replyTo = '';
                        await loadComments();
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        }

        // Handle reply to comment
        function replyTo(commentId) {
            const form = document.getElementById('commentForm');
            if (form) {
                form.dataset.replyTo = commentId;
                form.content.focus();
                form.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Load comments on page load
        loadComments();
    </script>
</body>
</html> 