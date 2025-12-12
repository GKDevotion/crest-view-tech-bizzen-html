<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

// Only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

// Get form data
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if ($name === '' || $email === '' || $phone === '') {
    echo json_encode(['status' => 'error', 'message' => 'Please fill all fields']);
    exit;
}

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Use PHP's mail() function
    $mail->isMail();

    // Sender (must be from your domain)
    $mail->setFrom('no-reply@gmail.com', 'Website Contact');

    // Reply-to is the user
    $mail->addReplyTo($email, $name);

    // Main recipient
    $mail->addAddress('tech.crestview@gmail.com');

    // CC recipient
    $mail->addCC('gk@devotiontech.io');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission';
    $mail->Body = "
        <h3>New Contact Message</h3>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";

    // Send the email
    if ($mail->send()) {
        echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to send email']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
}
?>
