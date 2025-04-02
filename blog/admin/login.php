<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to capture any errors
ob_start();

require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize debug array if it doesn't exist
if (!isset($_SESSION['debug'])) {
    $_SESSION['debug'] = [];
}

$_SESSION['debug'][] = "Script started";
$_SESSION['debug'][] = "Session ID: " . session_id();
$_SESSION['debug'][] = "Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set');
$_SESSION['debug'][] = "Session role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set');
$_SESSION['debug'][] = "Request method: " . $_SERVER['REQUEST_METHOD'];
$_SESSION['debug'][] = "POST data: " . print_r($_POST, true);

// Check if already logged in
if (isLoggedIn() && isAdmin()) {
    $_SESSION['debug'][] = "User already logged in and is admin, redirecting to index";
    header("Location: index.php");
    exit;
}

$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['debug'][] = "Processing POST request";
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $_SESSION['debug'][] = "Login attempt for username: " . $username;
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
        $_SESSION['debug'][] = "Error: Empty username or password";
    } else {
        try {
            // Check if the users table exists
            $tableExists = $db->query("SHOW TABLES LIKE 'users'")->rowCount() > 0;
            $_SESSION['debug'][] = "Users table exists: " . ($tableExists ? 'yes' : 'no');
            
            if (!$tableExists) {
                $error = 'Database configuration error. Please contact the administrator.';
                $_SESSION['debug'][] = "Error: Users table does not exist";
            } else {
                $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $_SESSION['debug'][] = "User found in database: " . ($user ? 'yes' : 'no');
                
                if ($user) {
                    $_SESSION['debug'][] = "User ID: " . $user['id'];
                    $_SESSION['debug'][] = "User role: " . $user['role'];
                    $_SESSION['debug'][] = "Password verification result: " . (password_verify($password, $user['password_hash']) ? 'true' : 'false');
                }
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['debug'][] = "Password verified successfully";
                    
                    if ($user['status'] === 'blocked') {
                        $error = 'Your account has been blocked. Please contact the administrator.';
                        $_SESSION['debug'][] = "Error: User account blocked";
                    } else {
                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = $user['role'];
                        
                        // Log successful login
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $user_agent = $_SERVER['HTTP_USER_AGENT'];
                        logUserActivity($user['id'], 'login', $ip, $user_agent);
                        
                        $_SESSION['debug'][] = "Login successful, session variables set";
                        $_SESSION['debug'][] = "New session user_id: " . $_SESSION['user_id'];
                        $_SESSION['debug'][] = "New session role: " . $_SESSION['role'];
                        
                        // Redirect to index page
                        header("Location: index.php");
                        exit;
                    }
                } else {
                    $error = 'Invalid username or password';
                    $_SESSION['debug'][] = "Error: Invalid credentials";
                }
            }
        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again later.';
            error_log("Login error: " . $e->getMessage());
            $_SESSION['debug'][] = "Database error: " . $e->getMessage();
        }
    }
}

// Get any output buffer errors
$output_errors = ob_get_clean();
if (!empty($output_errors)) {
    $_SESSION['debug'][] = "Output errors: " . $output_errors;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Root Labs Blog Admin</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%237E22CE'%3E%3Cpath d='M4 4v16a2 2 0 002 2h12a2 2 0 002-2V8.342a2 2 0 00-.602-1.43l-4.44-4.342A2 2 0 0013.56 2H6a2 2 0 00-2 2z'/%3E%3C/svg%3E">
    <link rel="stylesheet" href="../assets/css/admin-shared.css">
    <style>
        :root {
            --primary: #7e22ce;
            --primary-dark: #6b21a8;
            --primary-light: #a855f7;
            --surface: #ffffff;
            --background: #f9fafb;
            --text: #1f2937;
            --text-secondary: #6b7280;
            --border: #e5e7eb;
            --error: #ef4444;
            --error-light: #fee2e2;
            --success: #10b981;
            --success-light: #d1fae5;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #7e22ce 0%, #6b21a8 100%);
            margin: 0;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        
        .login-container {
            background: var(--surface);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: var(--text);
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        
        .login-header p {
            color: var(--text-secondary);
            margin: 0.5rem 0 0;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s;
            background-color: var(--background);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(126, 34, 206, 0.1);
        }
        
        .error-message {
            color: var(--error);
            margin-bottom: 1.5rem;
            padding: 0.75rem 1rem;
            background: var(--error-light);
            border-radius: 8px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }
        
        .error-message::before {
            content: '!';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background: var(--error);
            color: white;
            border-radius: 50%;
            margin-right: 0.75rem;
            font-weight: bold;
        }
        
        .submit-button {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        
        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(126, 34, 206, 0.2);
        }
        
        .submit-button:active {
            transform: translateY(0);
        }
        
        .submit-button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
        }
        
        .submit-button:hover::after {
            transform: translateX(100%);
            transition: transform 0.6s ease;
        }
        
        .login-footer {
            margin-top: 2rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .debug-toggle {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--background);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 0.7rem;
            color: var(--text-secondary);
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        
        .debug-toggle:hover {
            opacity: 1;
        }
        
        .debug-info {
            margin-top: 2rem;
            padding: 1rem;
            background: var(--background);
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.75rem;
            white-space: pre-wrap;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }
        
        .debug-info.visible {
            display: block;
        }
        
        .debug-info strong {
            color: var(--text);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Root Labs Blog Admin</h1>
            <p>Sign in to access your dashboard</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                       placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                       placeholder="Enter your password">
            </div>
            
            <button type="submit" class="submit-button">Sign In</button>
        </form>
        
        <div class="login-footer">
            <p>© <?php echo date('Y'); ?> Root Labs. All rights reserved.</p>
        </div>
        
        <button class="debug-toggle" onclick="toggleDebug()">Show Debug</button>
        
        <?php if (isset($_SESSION['debug'])): ?>
            <div class="debug-info" id="debugInfo">
                <strong>Debug Information:</strong>
                <?php
                foreach ($_SESSION['debug'] as $debug) {
                    echo htmlspecialchars($debug) . "\n";
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function toggleDebug() {
            const debugInfo = document.getElementById('debugInfo');
            const debugToggle = document.querySelector('.debug-toggle');
            
            if (debugInfo.classList.contains('visible')) {
                debugInfo.classList.remove('visible');
                debugToggle.textContent = 'Show Debug';
            } else {
                debugInfo.classList.add('visible');
                debugToggle.textContent = 'Hide Debug';
            }
        }
    </script>
</body>
</html> 