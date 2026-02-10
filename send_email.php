<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust if needed

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                     
    $mail->Host = 'smtp.gmail.com';                     
    $mail->SMTPAuth = true;                              
    $mail->Username = 'gody12919@gmail.com';           
    $mail->Password = 'dnhw yklv vsvb mgoj'; // NOT your Gmail password
    $mail->SMTPSecure = 'tls';                           
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('gody12919@gmail.com', 'gody12919');
    $mail->addAddress('godyezekiel35@gmail.com', 'Recipient Name');

    // Content
    $mail->isHTML(true);                                
    $mail->Subject = 'Password Reset';
    $mail->Body    = 'Click here to reset your password: <a href="https://yourdomain.com/reset_password.php?token=xxx">Reset</a>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
