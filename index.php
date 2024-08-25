<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start session to store OTP and email
session_start();

// Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST['to'])) {
    $to = trim($_POST['to']);
    $subject = 'Your OTP Code';  // OTP email subject
    $otp = rand(100000, 999999);  // Generate a 6-digit OTP
    $content = "Your OTP code is: $otp";

    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
        send_email($to, $subject, $content);
        $_SESSION['otp'] = $otp; // Store OTP in session for verification
        $_SESSION['email'] = $to; // Store email in session for verification
        echo "OTP has been sent to your email.";
    } else {
        echo "Invalid email address.";
    }
}

if (isset($_POST['otp'])) {
    $entered_otp = $_POST['otp'];

    // Verify the OTP
    if ($_SESSION['otp'] == $entered_otp) {
        // OTP is correct, redirect to welcome.php
        header("Location: welcome.php");
        exit();
    } else {
        // OTP is incorrect, display an error message
        echo "<center><p style='color:red;'>Incorrect OTP. Please try again.</p></center>";
    }
}

function send_email($to, $subject, $content){
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rathoredipanshu21@gmail.com'; // Your Gmail address
        $mail->Password   = 'mmnusgbvyakylzlu';   // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('your-email@gmail.com', 'Your Name');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body>
    <center>
        <h1>Enter Your Email to Receive OTP</h1>
        <form action="index.php" method="post">
            Send To: <input type="email" name="to" required><br><br>
            <input type="submit" value="Send OTP">
        </form>

        <h1>Enter OTP</h1>
        <form action="index.php" method="post">
            OTP: <input type="text" name="otp" required><br><br>
            <input type="submit" value="Verify OTP">
        </form>
    </center>
</body>
</html>
