<?php
include '../../conexion.php';
require_once '../../componentes/botonRegresar.php';
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $email = $_POST['email'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = isset($_POST['contrasena']) ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) : '';
    $privilegio = $_POST['privilegio'] ?? '';
    $nuevo = 1;

    if ($nombre && $telefono && $correo && $email && $usuario && $contrasena && $privilegio) {
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, telefono, correo, email, usuario, contrasena, privilegio, nuevo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssi', $nombre, $telefono, $correo, $email, $usuario, $contrasena, $privilegio, $nuevo);
        if ($stmt->execute()) {
            $mensaje = 'Usuario agregado correctamente.';
        } else {
            $mensaje = 'Error al agregar usuario: ' . $conexion->error;
        }
        $stmt->close();
    } else {
        $mensaje = 'Todos los campos son obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../../estilos/estiloLogin.css?202405=<?php echo (rand()); ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
</head>
<body>
    <?php mostrarBotonRegresar('../administrarUsuarios.php'); ?>
    <div class="contenedorLogin" style="max-width: 420px;">
        <h2>Agregar Usuario</h2>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required maxlength="100">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required maxlength="20" pattern="[0-9\-\+ ]*">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required maxlength="100">
            <label for="email">Email alternativo:</label>
            <input type="email" id="email" name="email" required maxlength="100">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required maxlength="50">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required maxlength="255">
            <label for="privilegio">Privilegio:</label>
            <select id="privilegio" name="privilegio" required>
                <option value="agente">Agente de ventas</option>
                <option value="administrador">Administrador</option>
            </select>
            <button type="submit">Agregar</button>
        </form>
    </div>
</body>
</html>
