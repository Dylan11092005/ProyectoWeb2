<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $mensaje  = trim($_POST['mensaje']);

    if ($nombre && $email && $telefono && $mensaje && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        $destinatario = $email;
        $asunto = "Gracias por contactarnos";
        $cuerpo = "Hola $nombre,\n\nHemos recibido tu mensaje:\n\n\"$mensaje\"\n\nPronto nos pondremos en contacto contigo.\n\nSaludos,\nUTN Solutions Real Estate";
        $cabeceras = "From: contacto@tudominio.com\r\n"; 

       
        mail($destinatario, $asunto, $cuerpo, $cabeceras);

        echo "<script>alert('¡Mensaje enviado correctamente! Revisa tu correo.');window.location.href='../index.php#contacto';</script>";
        exit;
    } else {
        echo "<script>alert('Por favor, completa todos los campos correctamente.');window.location.href='../index.php#contacto';</script>";
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}