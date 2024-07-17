<?php

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function enviarCorreo($asunto, $cuerpo, $destinatario) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'suarezmuchairo@gmail.com';
        $mail->Password = 'xsxuemxevdpymcgw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('suarezmuchairo@gmail.com', 'Salon de Evento Carola');
        $mail->addAddress($destinatario);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;

        $mail->send();
        return 'Correo enviado correctamente';
    } catch (Exception $e) {
        return 'Error al enviar el correo: ' . $mail->ErrorInfo;
    }
}
?>
