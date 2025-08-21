<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre && $email && $telefono && $mensaje && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);

        try {
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dsegurasevilla@gmail.com';
            $mail->Password   = 'evzb nsoq ofte lkzf';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            
            $mail->setFrom('dsegurasevilla@gmail.com', 'UTN Solutions Real Estate');

           
            $mail->addAddress('dsegurasevilla@gmail.com');

            
            $mail->addReplyTo($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Nuevo mensaje desde el formulario';
            $mail->Body    = "
                <h3>Datos de contacto</h3>
                <p><b>Nombre:</b> $nombre</p>
                <p><b>Email:</b> $email</p>
                <p><b>Teléfono:</b> $telefono</p>
                <p><b>Mensaje:</b> $mensaje</p>
            ";
            $mail->AltBody = "Nombre: $nombre\nEmail: $email\nTeléfono: $telefono\nMensaje: $mensaje";

            $mail->send();
            echo "<script>alert('¡Mensaje enviado correctamente!');window.location.href='../index.php#contacto';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error al enviar: {$mail->ErrorInfo}');window.location.href='../index.php#contacto';</script>";
        }
    } else {
        echo "<script>alert('Por favor, completa todos los campos correctamente.');window.location.href='../index.php#contacto';</script>";
    }
} else {
    header('Location: ../index.php');
    exit;
}