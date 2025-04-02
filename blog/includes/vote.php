<?php
require_once 'config.php';
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

// Validate inputs
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
$vote_type = filter_input(INPUT_POST, 'vote_type', FILTER_SANITIZE_STRING);

if (!$post_id || !in_array($vote_type, ['up', 'down'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid input']));
}

// Hash IP address for privacy
$ip_hash = hash('sha256', $_SERVER['REMOTE_ADDR'] . VOTE_SALT); // Define VOTE_SALT in config.php

try {
    $db->beginTransaction();

    // Check if user has already voted
    $stmt = $db->prepare("SELECT vote_type FROM post_votes WHERE post_id = ? AND ip_hash = ?");
    $stmt->execute([$post_id, $ip_hash]);
    $existing_vote = $stmt->fetch();

    if ($existing_vote) {
        if ($existing_vote['vote_type'] === $vote_type) {
            // Remove vote if clicking same button
            $stmt = $db->prepare("DELETE FROM post_votes WHERE post_id = ? AND ip_hash = ?");
            $stmt->execute([$post_id, $ip_hash]);
        } else {
            // Change vote if clicking different button
            $stmt = $db->prepare("UPDATE post_votes SET vote_type = ? WHERE post_id = ? AND ip_hash = ?");
            $stmt->execute([$vote_type, $post_id, $ip_hash]);
        }
    } else {
        // Add new vote
        $stmt = $db->prepare("INSERT INTO post_votes (post_id, ip_hash, vote_type) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $ip_hash, $vote_type]);
    }

    // Get updated vote counts
    $stmt = $db->prepare("
        SELECT 
            SUM(CASE WHEN vote_type = 'up' THEN 1 ELSE 0 END) as upvotes,
            SUM(CASE WHEN vote_type = 'down' THEN 1 ELSE 0 END) as downvotes
        FROM post_votes 
        WHERE post_id = ?
    ");
    $stmt->execute([$post_id]);
    $votes = $stmt->fetch();

    $db->commit();

    echo json_encode([
        'success' => true,
        'upvotes' => (int)$votes['upvotes'],
        'downvotes' => (int)$votes['downvotes']
    ]);

} catch (Exception $e) {
    $db->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    error_log("Vote error: " . $e->getMessage());
} 