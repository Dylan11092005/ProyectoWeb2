<?php
include '../conexion.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$errores = [];
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasenaAnterior = $_POST['contrasenaAnterior'] ?? '';
    $nuevaContrasena = $_POST['nuevaContrasena'] ?? '';

    if (empty($usuario)) $errores[] = 'El usuario es obligatorio.';
    if (empty($contrasenaAnterior)) $errores[] = 'La contraseña anterior es obligatoria.';
    if (empty($nuevaContrasena)) $errores[] = 'La nueva contraseña es obligatoria.';
    if (!empty($nuevaContrasena) && strlen($nuevaContrasena) < 6) $errores[] = 'La nueva contraseña debe tener al menos 6 caracteres.';

    if (empty($errores)) {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows === 1) {
            $usuarioDatos = $resultado->fetch_assoc();
            if (password_verify($contrasenaAnterior, $usuarioDatos['contrasena'])) {
                $hashNueva = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                $stmtUpdate = $conexion->prepare("UPDATE usuarios SET contrasena = ?, nuevo = 0 WHERE usuario = ?");
                $stmtUpdate->bind_param("ss", $hashNueva, $usuario);
                if ($stmtUpdate->execute()) {
                   header('Location: login.php');
                } else {
                    $errores[] = 'Error al actualizar la contraseña.';
                }
                $stmtUpdate->close();
            } else {
                $errores[] = 'Usuario o contraseña anterior incorrectos.';
            }
        } else {
            $errores[] = 'Usuario o contraseña anterior incorrectos.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>

    <link rel="stylesheet" href="../estilos/estiloLogin.css?202405=<?php echo (rand()); ?>">
    <link rel="stylesheet" href="../estilos/estilosUsuarios.css?202405=<?php echo (rand()); ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
</head>

<body>
    <?php
    require_once '../componentes/botonRegresar.php';
    mostrarBotonRegresar('login.php');
    ?>
    <div class="contenedorLogin">
        <h2>Cambiar Contraseña</h2>
        <?php if (!empty($errores)): ?>
            <div style="background:#ffc107;color:#18184d;padding:10px;margin-bottom:15px;border-radius:8px;font-weight:bold;">
                <?php foreach ($errores as $err): ?>
                    <div><?php echo $err; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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