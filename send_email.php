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
	echo 'Message has been sent';
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}