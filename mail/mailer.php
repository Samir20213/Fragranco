<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendOrderAcceptedMail($toEmail, $customerName, $orderId, $orderStatus) {
    $mail = new PHPMailer(true);

    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'fragranco.bd@gmail.com';
        $mail->Password   = 'fwxzzrcxbyqwfiel';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and receiver
        $mail->setFrom('fragranco.bd@gmail.com', 'FRAGRANCO');
        $mail->addAddress($toEmail, $customerName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Your Order #$orderId has been Accepted";

        $mail->Body = "
            <h2>FRAGRANCO</h2>
            <p>Dear <strong>$customerName</strong>,</p>
            <p>Your order <strong>#$orderId</strong> has been <strong>$orderStatus</strong>.</p>
            <p>Thank you for shopping with FRAGRANCO ❤️</p>
        ";

        $mail->AltBody = "Dear $customerName, Your order #$orderId has been $orderStatus. Thank you for shopping with FRAGRANCO.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}