<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configure email settings
$to_email = "uzairf2580@gmail.com"; // Your email address
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: uzairf2580@gmail.com" . "\r\n"; // Change this to your domain email

// Function to sanitize input
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $first_name = isset($_POST['First_Name']) ? sanitize_input($_POST['First_Name']) : '';
    $last_name = isset($_POST['Last_Name']) ? sanitize_input($_POST['Last_Name']) : '';
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $email = isset($_POST['Email']) ? sanitize_input($_POST['Email']) : '';
    $subject = isset($_POST['Subject']) ? sanitize_input($_POST['Subject']) : '';
    $message = isset($_POST['Message']) ? sanitize_input($_POST['Message']) : '';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    // Construct email body
    $email_body = "
    <html>
    <head>
        <title>New Contact Form Submission</title>
    </head>
    <body>
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> $first_name $last_name</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Subject:</strong> $subject</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
    </body>
    </html>
    ";

    // Send email
    $mail_sent = mail($to_email, "New Contact Form Submission: $subject", $email_body, $headers);

    // Return response
    header('Content-Type: application/json');
    if ($mail_sent) {
        echo json_encode(["status" => "success", "message" => "Thank you for your message. We will get back to you soon."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to send message. Please try again later."]);
    }
    exit;
}
?>