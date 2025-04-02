<?php
/**
 * General Error Page
 * Displayed for various error conditions
 */

// Get the error code from the query string or default to 403
$errorCode = isset($_GET['code']) ? (int)$_GET['code'] : 403;
$errorMessage = '';

// Set the appropriate HTTP response code
http_response_code($errorCode);

// Set the error message based on the code
switch ($errorCode) {
    case 403:
        $errorMessage = 'Access Forbidden';
        break;
    case 404:
        $errorMessage = 'Page Not Found';
        break;
    case 500:
        $errorMessage = 'Server Error';
        break;
    default:
        $errorMessage = 'An Error Occurred';
}

// Include header if it exists
if (file_exists('includes/header.php')) {
    include 'includes/header.php';
}
?>

<div class="error-container">
    <div class="error-content">
        <h1><?php echo $errorCode; ?> - <?php echo $errorMessage; ?></h1>
        <p>Sorry, there was a problem with your request.</p>
        <div class="error-actions">
            <a href="/blog" class="btn btn-primary">Go to Homepage</a>
            <a href="/" class="btn btn-secondary">Go to Root Labs</a>
        </div>
    </div>
</div>

<?php
// Include footer if it exists
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
}
?> 