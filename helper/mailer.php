<?php
require 'vendor/autoload.php';
class Mailer
{
    public static function send($to, $message = '')
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        // SMTP configuration
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '920dd813281570';
        $mail->Password = 'ee31e22ace6ece';

        // Email content
        $mail->setFrom('saify@thecoders.com', 'saify');
        $mail->addAddress($to);
        $mail->Subject = 'Test Email';
        $mail->Body = $message;

        return $mail->send();
    }
}
