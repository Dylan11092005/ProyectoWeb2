<?php
session_start();
include '../conexion.php';

$usuario = $_POST['usuario'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correo = $_POST['correo'] ?? '';
$email = $_POST['email'] ?? '';
$privilegio = $_POST['privilegio'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$idUsuario = $_POST['idUsuario'] ?? 0;

if ($idUsuario > 0) {
    if ($contrasena !== '') {
        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ?, correo = ?, email = ?, privilegio = ?, contrasena = ? WHERE idUsuario = ?");
        $stmt->bind_param("sssssssi", $usuario, $nombre, $telefono, $correo, $email, $privilegio, $contrasenaHash, $idUsuario);
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ?, correo = ?, email = ?, privilegio = ? WHERE idUsuario = ?");
        $stmt->bind_param("ssssssi", $usuario, $nombre, $telefono, $correo, $email, $privilegio, $idUsuario);
    }
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
}
