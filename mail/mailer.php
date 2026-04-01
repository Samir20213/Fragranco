<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function createMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    // SMTP setup
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'fragranco.bd@gmail.com';
    $mail->Password   = 'fwxzzrcxbyqwfiel';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender
    $mail->setFrom('fragranco.bd@gmail.com', 'FRAGRANCO');
    $mail->isHTML(true);

    return $mail;
}

function sendOrderAcceptedMail($toEmail, $customerName, $orderId, $orderStatus) {
    try {
        $mail = createMailer();

        // Receiver
        $mail->addAddress($toEmail, $customerName);

        // Content
        $mail->Subject = "Your Order #$orderId has been Accepted";

        $mail->Body = "
            <h2>FRAGRANCO</h2>
            <p>Dear <strong>{$customerName}</strong>,</p>
            <p>Your order <strong>#{$orderId}</strong> has been <strong>{$orderStatus}</strong>.</p>
            <p>Thank you for shopping with FRAGRANCO ❤️</p>
        ";

        $mail->AltBody = "Dear {$customerName}, Your order #{$orderId} has been {$orderStatus}. Thank you for shopping with FRAGRANCO.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

function sendContactReplyMail($toEmail, $customerName, $subject, $replyMessage) {
    try {
        $mail = createMailer();

        // Receiver
        $mail->addAddress($toEmail, $customerName);

        // Content
        $mail->Subject = "Reply to your message: $subject";

        $mail->Body = "
            <h2>FRAGRANCO Support Reply</h2>
            <p>Dear <strong>{$customerName}</strong>,</p>
            <p>Thank you for contacting us.</p>
            <p><strong>Our Reply:</strong></p>
            <p>" . nl2br(htmlspecialchars($replyMessage)) . "</p>
            <br>
            <p>Best regards,<br>FRAGRANCO Team</p>
        ";

        $mail->AltBody = "Dear {$customerName}, Our reply: {$replyMessage}";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}