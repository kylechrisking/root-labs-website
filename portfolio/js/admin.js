/**
 * Admin Panel JavaScript
 * Handles authentication, post management, and notifications
 */

// Authentication handling
async function handleLogin(event) {
    event.preventDefault();
    const password = document.getElementById('adminPassword').value;
    
    try {
        const response = await fetch('/blog/admin/api/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ password: password })
        });
        
        if (!response.ok) throw new Error('Invalid password');
        
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('adminPanel').style.display = 'block';
        document.querySelector('.logout-btn').style.display = 'block';
        showNotification('Successfully logged in');
        loadPosts();
    } catch (error) {
        showNotification('Invalid password', 'error');
    }
}

// Notification system
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.style.display = 'block';
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }, 3000);
}

// Authentication check
function checkAuth() {
    if (localStorage.getItem('adminAuthenticated') === 'true') {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('adminPanel').style.display = 'block';
        document.querySelector('.logout-btn').style.display = 'block';
    }
}

// Logout functionality
function logout() {
    localStorage.removeItem('adminAuthenticated');
    showNotification('Logged out successfully');
    location.reload();
}

// Post form management
function showNewPostForm() {
    document.getElementById('postForm').style.display = 'block';
    // Initialize SimpleMDE if not already initialized
    if (!window.simplemde) {
        window.simplemde = new SimpleMDE({ 
            element: document.getElementById("postContent"),
            spellChecker: false,
            autosave: {
                enabled: true,
                unique_id: "blogPost"
            }
        });
    }
}

function cancelPost() {
    document.getElementById('postForm').style.display = 'none';
    document.getElementById('postTitle').value = '';
    document.getElementById('postTags').value = '';
    if (window.simplemde) {
        window.simplemde.value('');
        // Clear autosaved content
        localStorage.removeItem('smde_blogPost');
    }
}

// Post management
function refreshPosts() {
    showNotification('Refreshing posts...');
    loadPosts();
}

async function handleNewPost(event) {
    event.preventDefault();
    const title = document.getElementById('postTitle').value;
    const tags = document.getElementById('postTags').value;
    const content = window.simplemde.value();
    
    // Validate inputs
    if (!title.trim() || !content.trim()) {
        showNotification('Title and content are required', 'error');
        return;
    }
    
    try {
        const response = await fetch('/blog/admin/api/posts.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ title, tags, content })
        });
        
        if (!response.ok) throw new Error('Failed to create post');
        
        showNotification('Post created successfully');
        cancelPost();
        loadPosts();
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

async function loadPosts() {
    try {
        const response = await fetch('/blog/admin/api/posts.php');
        if (!response.ok) throw new Error('Failed to fetch posts');
        
        const posts = await response.json();
        
        const postsList = document.querySelector('.posts-list');
        postsList.innerHTML = posts.map(post => `
            <div class="post-item" data-post-id="${post.id}">
                <h3>${escapeHtml(post.title)}</h3>
                <p class="post-meta">
                    Posted on ${new Date(post.created_at).toLocaleDateString()}
                    <span class="post-tags">${escapeHtml(post.tags)}</span>
                </p>
                <div class="post-actions">
                    <button onclick="editPost(${post.id})" class="edit-btn">Edit</button>
                    <button onclick="confirmDelete(${post.id})" class="delete-btn">Delete</button>
                </div>
            </div>
        `).join('');
    } catch (error) {
        showNotification('Failed to load posts', 'error');
    }
}

// Helper function to escape HTML and prevent XSS
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Delete confirmation
function confirmDelete(postId) {
    if (confirm('Are you sure you want to delete this post?')) {
        deletePost(postId);
    }
}

// Delete post
async function deletePost(postId) {
    try {
        const response = await fetch(`/blog/admin/api/posts.php?id=${postId}`, {
            method: 'DELETE'
        });
        
        if (!response.ok) throw new Error('Failed to delete post');
        
        showNotification('Post deleted successfully');
        loadPosts();
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

// Edit post
async function editPost(postId) {
    try {
        const response = await fetch(`/blog/admin/api/posts.php?id=${postId}`);
        if (!response.ok) throw new Error('Failed to fetch post');
        
        const post = await response.json();
        
        document.getElementById('postTitle').value = post.title;
        document.getElementById('postTags').value = post.tags;
        if (window.simplemde) {
            window.simplemde.value(post.content);
        }
        
        document.getElementById('postForm').style.display = 'block';
        document.getElementById('postForm').dataset.editId = postId;
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
    
    // Add form submit event listeners
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    const postForm = document.getElementById('postForm');
    if (postForm) {
        postForm.addEventListener('submit', handleNewPost);
    }
}); 