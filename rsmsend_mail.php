<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure Composer dependencies are installed

function sendBookingEmail($toEmail, $toName, $ticketDetails) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'your email'; // Your Gmail
        $mail->Password = 'your app password';     // Your App Password
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587;

        // Sender & Recipient
        $mail->setFrom('your email', 'Indian Railways');
        $mail->addAddress($toEmail, $toName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Train Ticket Booking Confirmation';
        $mail->Body = "<h2>Booking Confirmed!</h2>
                       <p>Dear $toName,</p>
                       <p>Your train ticket has been booked successfully.</p>
                       <p><strong>Details:</strong><br>$ticketDetails</p>
                       <p>Thank you for booking with Indian Railways.</p>";

        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
