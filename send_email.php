<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Etc/UTC');

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
$mail->isSMTP();

$senderName = $_POST['name'];
$senderEmail = $_POST['email'];
$messageBody = $_POST['message'];
$serviceType = $_POST['service'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaSecret = getenv('RECAPTCHA_SECRET_KEY'); // Get the secret key from the environment variable
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify the reCAPTCHA response
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo 'reCAPTCHA verification failed. Please try again.';
        exit;
    }

    // Proceed with sending the email or processing the form
    // ...
}

try {
	//Server settings
	$mail->isSMTP();
	$mail->Host 		= getenv('SMTP_HOST');
	$mail->SMTPAuth		= true;
	$mail->Username 	= getenv('SMTP_USER');
	$mail->Password 	= getenv('SMTP_PASSWORD');
	$mail->SMTPSecure 	= PHPMailer::ENCRYPTION_SMTPS;
	$mail->Port 		= getenv('SMTP_PORT');

	//Recipients
	$mail->setFrom('info@rootlabs.us', $senderEmail);
	$mail->addAddress('james@rootlabs.us', 'James');
	$mail->addAddress('kyle@rootlabs.us', 'Kyle');

	//Content
	$mail->isHTML(true);
	$mail->Subject 		= 'New Contact Form Submission From ' . $senderName;
	$mail->Body 		= $senderName . ' is inquiring about ' . $serviceType . " regarding: \n" . $messageBody ;

	$mail->send();
//	echo 'Message has been sent';
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}