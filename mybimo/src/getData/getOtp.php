<?php
session_start();
//Load Composer's autoloader
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include "../koneksi/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Check if email exists and last OTP request time
    $currentTime = time();
    $lastRequestTime = isset($_SESSION['last_otp_request'][$email]) ? $_SESSION['last_otp_request'][$email] : 0;
    
    // Allow new OTP only after 60 seconds
    if ($currentTime - $lastRequestTime < 60) {
        echo json_encode(['status' => 'error', 'message' => 'Please wait before requesting new OTP']);
        exit;
    }
    
    $_SESSION['last_otp_request'][$email] = $currentTime;
    
    $otp = rand(100000, 999999);
    
    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mybimo85@gmail.com';
        $mail->Password   = 'tdyn ypsh ukaz gpgn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('noreply@mybimoapps.com', 'My Bimo Application');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification Forgot Password';
        $mail->Body    = 'Hello ' . $email . ' <br> Your OTP code is: ' . $otp . '.';
        $mail->AltBody = "Reset password to access code easy application.";

        // Send email
        if ($mail->send()) {
            echo json_encode([
                'status' => 'success',
                'otp' => $otp,
                'email' => $email
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
        }

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>