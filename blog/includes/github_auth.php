<?php
require_once 'config.php';
require_once 'db.php';

session_start();

// GitHub OAuth configuration
define('GITHUB_CLIENT_ID', getenv('GITHUB_CLIENT_ID')); // Set this in your environment
define('GITHUB_CLIENT_SECRET', getenv('GITHUB_CLIENT_SECRET')); // Set this in your environment
define('GITHUB_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . '/blog/includes/github_auth.php');

// Handle the OAuth callback
if (isset($_GET['code'])) {
    // Exchange code for access token
    $token_url = 'https://github.com/login/oauth/access_token';
    $params = [
        'client_id' => GITHUB_CLIENT_ID,
        'client_secret' => GITHUB_CLIENT_SECRET,
        'code' => $_GET['code'],
        'redirect_uri' => GITHUB_REDIRECT_URI
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $token_data = json_decode($response, true);
    curl_close($ch);

    if (isset($token_data['access_token'])) {
        // Get user data from GitHub
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: token ' . $token_data['access_token'],
            'User-Agent: Root-Labs-Blog'
        ]);
        
        $user_data = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if ($user_data && isset($user_data['id'])) {
            // Store minimal user data in session
            $_SESSION['github_user'] = [
                'id' => $user_data['id'],
                'login' => $user_data['login'],
                'avatar_url' => $user_data['avatar_url']
            ];

            // Store or update user in database
            $stmt = $db->prepare("
                INSERT INTO comment_users (github_id, username, avatar_url, last_login)
                VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE 
                    username = VALUES(username),
                    avatar_url = VALUES(avatar_url),
                    last_login = NOW()
            ");
            $stmt->execute([
                $user_data['id'],
                $user_data['login'],
                $user_data['avatar_url']
            ]);

            // Redirect back to the post or comment section
            $redirect_to = $_SESSION['comment_redirect'] ?? '/blog';
            unset($_SESSION['comment_redirect']);
            header('Location: ' . $redirect_to);
            exit;
        }
    }

    // If we get here, something went wrong
    $_SESSION['auth_error'] = 'Failed to authenticate with GitHub';
    header('Location: /blog');
    exit;
}

// Initialize OAuth flow
if (isset($_GET['redirect'])) {
    $_SESSION['comment_redirect'] = $_GET['redirect'];
    $auth_url = 'https://github.com/login/oauth/authorize?' . http_build_query([
        'client_id' => GITHUB_CLIENT_ID,
        'redirect_uri' => GITHUB_REDIRECT_URI,
        'scope' => 'read:user'
    ]);
    header('Location: ' . $auth_url);
    exit;
} 