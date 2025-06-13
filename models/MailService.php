<?php
// models/MailService.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // Asegúrate que apunta a autoload.php

class MailService {
    public static function enviarCorreo($destinatario, $asunto, $mensaje) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'telasycosturasinventario@gmail.com';              // ← pon aquí tu correo real
           // $mail->Password = 'yaavjloelwsvntum'; 
            $mail->Password = 'waznjmhgyzexfjgr'; // ← pon aquí tu contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('telasycosturasinventario@gmail.com', 'Telas y Costuras');
            $mail->addAddress($destinatario);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return 'Error al enviar correo: ' . $mail->ErrorInfo;
        }
    }
}
