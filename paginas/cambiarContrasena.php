<?php
include '../conexion.php';
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasenaAnterior = $_POST['contrasenaAnterior'];
    $nuevaContrasena = $_POST['nuevaContrasena'];
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? AND contrasena = ?");
    $stmt->bind_param("ss", $usuario, $contrasenaAnterior);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado && $resultado->num_rows === 1) {
        $stmtUpdate = $conexion->prepare("UPDATE usuarios SET contrasena = ?, nuevo = 0 WHERE usuario = ?");
        $stmtUpdate->bind_param("ss", $nuevaContrasena, $usuario);
        if ($stmtUpdate->execute()) {
           header('Location: login.php');
        } else {
            $mensaje = 'Error al actualizar la contraseña.';
        }
        $stmtUpdate->close();
    } else {
        $mensaje = 'Usuario o contraseña anterior incorrectos.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="../estilos/estiloLogin.css">
</head>
<body>
    <?php 
    require_once '../componentes/botonRegresar.php';
    mostrarBotonRegresar('login.php');
    ?>
    <div class="contenedorLogin">
        <h2>Cambiar Contraseña</h2>
        <?php if ($mensaje) { echo '<p class="mensajeError">'.$mensaje.'</p>'; } ?>
        <form method="POST" action="">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="contrasenaAnterior">Contraseña Anterior:</label>
            <input type="password" id="contrasenaAnterior" name="contrasenaAnterior" required>
            <label for="nuevaContrasena">Nueva Contraseña:</label>
            <input type="password" id="nuevaContrasena" name="nuevaContrasena" required>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>
