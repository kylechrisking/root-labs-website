<?php
// Redirect to the error page for now, see below for more info
header("Location: error.html");
exit(); // Ensure no further code is executed to prevent complications
?>













<!--restore me once php is configured

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $service = htmlspecialchars($_POST['service']);
    $message = htmlspecialchars($_POST['message']);

    // Set the recipient email addresses
    $to = "james@rootlabs.us, kyle@rootlabs.us"; // Add emails here
    $subject = "New Contact Form Submission from $name";

    // Create the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Service Interested In: $service\n";
    $email_content .= "Message:\n$message\n";

    // Set the email headers
    $headers = "From: $name <$email>";

    // Send the email
    if (mail($to, $subject, $email_content, $headers)) {
        // Redirect to a thank you page or display a success message
        echo "Thank you for contacting us, $name. We will get back to you soon!";
    } else {
        // Redirect to the error page
        header("Location: error.html");
        exit(); // Ensure no further code is executed
    }
} else {
    // Not a POST request
    header("Location: error.html");
    exit(); // Ensure no further code is executed
}
-->
