<?php
require_once 'config.php';
require_once 'db.php';

header('Content-Type: application/json');
session_start();

// Check if user is authenticated via GitHub
if (!isset($_SESSION['github_user'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Authentication required']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle comment submission
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $parent_id = filter_input(INPUT_POST, 'parent_id', FILTER_VALIDATE_INT) ?: null;

    if (!$post_id || !$content) {
        http_response_code(400);
        exit(json_encode(['error' => 'Invalid input']));
    }

    try {
        $db->beginTransaction();

        // Insert comment
        $stmt = $db->prepare("
            INSERT INTO comments (
                post_id, user_id, content, parent_id, created_at
            ) VALUES (
                ?, (SELECT id FROM comment_users WHERE github_id = ?), ?, ?, NOW()
            )
        ");
        $stmt->execute([$post_id, $_SESSION['github_user']['id'], $content, $parent_id]);
        
        // Get the inserted comment with user data
        $stmt = $db->prepare("
            SELECT c.*, cu.username, cu.avatar_url
            FROM comments c
            JOIN comment_users cu ON c.user_id = cu.id
            WHERE c.id = ?
        ");
        $stmt->execute([$db->lastInsertId()]);
        $comment = $stmt->fetch();

        $db->commit();

        echo json_encode([
            'success' => true,
            'comment' => [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'created_at' => $comment['created_at'],
                'username' => $comment['username'],
                'avatar_url' => $comment['avatar_url']
            ]
        ]);
    } catch (Exception $e) {
        $db->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
        error_log("Comment error: " . $e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle comment retrieval
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);
    
    if (!$post_id) {
        http_response_code(400);
        exit(json_encode(['error' => 'Invalid post ID']));
    }

    try {
        // Get comments with user data
        $stmt = $db->prepare("
            SELECT 
                c.*, 
                cu.username,
                cu.avatar_url,
                (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'up') as upvotes,
                (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'down') as downvotes
            FROM comments c
            JOIN comment_users cu ON c.user_id = cu.id
            WHERE c.post_id = ?
            ORDER BY 
                CASE WHEN c.parent_id IS NULL THEN c.id ELSE c.parent_id END,
                c.parent_id IS NULL DESC,
                c.created_at ASC
        ");
        $stmt->execute([$post_id]);
        $comments = $stmt->fetchAll();

        // Organize comments into threads
        $threads = [];
        $comment_map = [];
        foreach ($comments as $comment) {
            $comment_data = [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'created_at' => $comment['created_at'],
                'username' => $comment['username'],
                'avatar_url' => $comment['avatar_url'],
                'upvotes' => $comment['upvotes'],
                'downvotes' => $comment['downvotes'],
                'replies' => []
            ];
            
            $comment_map[$comment['id']] = $comment_data;
            
            if ($comment['parent_id']) {
                $comment_map[$comment['parent_id']]['replies'][] = &$comment_map[$comment['id']];
            } else {
                $threads[] = &$comment_map[$comment['id']];
            }
        }

        echo json_encode([
            'success' => true,
            'comments' => $threads
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
        error_log("Comment retrieval error: " . $e->getMessage());
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
} 