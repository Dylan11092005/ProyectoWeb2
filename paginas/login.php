<?php
include '../conexion.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $conexion->real_escape_string($_POST['usuario']);
    $contrasenaUsuario = $_POST['contrasena'];
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
    $stmt->bind_param('s', $nombreUsuario);
    $stmt->execute();
    $resultadoConsulta = $stmt->get_result();
    if ($resultadoConsulta && $resultadoConsulta->num_rows === 1) {
        $usuarioDatos = $resultadoConsulta->fetch_assoc();
        if (password_verify($contrasenaUsuario, $usuarioDatos['contrasena'])) {
            session_start();
            $_SESSION['usuario'] = $usuarioDatos['usuario'];
            $_SESSION['privilegio'] = $usuarioDatos['privilegio'];
            if ($usuarioDatos['nuevo'] == 1) {
                header('Location: cambiarContrasena.php');
            } else {
                header('Location: administrar.php');
            }
            exit();
        } else {
            $mensaje = 'Usuario o contraseña incorrectos';
        }
    } else {
        $mensaje = 'Usuario o contraseña incorrectos';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../estilos/estiloLogin.css">
</head>
<body>
    <?php 
    require_once '../componentes/botonRegresar.php';
    mostrarBotonRegresar('../index.php');
    ?>
    <div class="contenedorLogin">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <button type="submit">Ingresar</button>
        </form>
        <?php 
        require_once '../componentes/botonRegresar.php';
        mostrarBotonRegresar('../index.php');
        ?>
    </div>
</body>
</html>
