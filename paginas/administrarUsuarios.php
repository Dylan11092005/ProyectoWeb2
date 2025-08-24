<?php
session_start();
include '../conexion.php';
require_once '../componentes/botonRegresar.php';
require_once '../componentes/eliminar.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$privilegio = $_SESSION['privilegio'] ?? '';
$usuarioSesion = $_SESSION['usuario'] ?? '';

if ($privilegio === 'administrador' && isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    if (eliminarPorId($conexion, 'usuarios', 'idUsuario', $idEliminar)) {
        header('Location: administrarUsuarios.php?msg=eliminado');
        exit();
    } else {
        echo '<div class="mensajeError">Error al eliminar el usuario.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="../estilos/estiloAdministrar.css?202405=<?php echo (rand()); ?>">
    <link rel="stylesheet" href="../estilos/estilosUsuarios.css?202405=<?php echo (rand()); ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
</head>

<body>
    <?php mostrarBotonRegresar('administrar.php'); ?>
    <div class="container">
        <div class="header-usuarios">
            <h1 class="titulo">Usuarios</h1>
            <?php if ($privilegio === 'administrador'): ?>
                <form action="agregar/agregarUsuarios.php" method="POST" class="form-agregar-usuario">
                    <button type="submit" class="btn btn-agregar">Agregar usuario</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php
            if ($privilegio === 'administrador') {
                $resultado = $conexion->query("SELECT * FROM usuarios ORDER BY idUsuario DESC");
            } else {
                $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
                $stmt->bind_param("s", $usuarioSesion);
                $stmt->execute();
                $resultado = $stmt->get_result();
            }
            while ($row = $resultado->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-body">
                        <img src="../img/usuarios.png" alt="Usuario" class="card-img">
                        <h5 class="card-title">Nombre: <?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        <p class="card-text"><strong>Usuario:</strong> <?= htmlspecialchars($row['usuario'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Teléfono:</strong> <?= htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Correo:</strong> <?= htmlspecialchars($row['correo'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Privilegio:</strong> <?= htmlspecialchars($row['privilegio'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>

                    <div class="card-actions">
                        <button
                            type="button"
                            class="action-btn update-btn"
                            onclick="abrirModal({
                                titulo: 'Editar Usuario',
                                boton: 'Guardar Cambios',
                                campos: [
                                    {label: 'Nombre', name: 'nombre', type: 'text', value: '<?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'); ?>', required: true, maxlength: 100},
                                    {label: 'Teléfono', name: 'telefono', type: 'tel', value: '<?= htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8'); ?>', required: true, maxlength: 20, pattern: '[0-9\\-\\+ ]*'},
                                    {label: 'Correo', name: 'correo', type: 'email', value: '<?= htmlspecialchars($row['correo'], ENT_QUOTES, 'UTF-8'); ?>', required: true, maxlength: 100},
                                    {label: 'Email alternativo', name: 'email', type: 'email', value: '<?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>', required: true, maxlength: 100},
                                    {label: 'Usuario', name: 'usuario', type: 'text', value: '<?= htmlspecialchars($row['usuario'], ENT_QUOTES, 'UTF-8'); ?>', required: true, maxlength: 50},
                                    {label: 'Contraseña', name: 'contrasena', type: 'password', value: '', required: false, maxlength: 255}
                                    <?php if ($privilegio === 'administrador'): ?>
                                    ,{label: 'Privilegio', name: 'privilegio', type: 'select', value: '<?= htmlspecialchars($row['privilegio'], ENT_QUOTES, 'UTF-8'); ?>', required: true, options: [
                                        {value: 'agente', label: 'Agente de ventas'},
                                        {value: 'administrador', label: 'Administrador'}
                                    ]}
                                    <?php endif; ?>
                                ],
                                onsubmit: function(e, form) {
                                    const formData = new FormData(form);
                                    formData.append('idUsuario', '<?= (int)$row['idUsuario']; ?>');
                                    fetch('../componentes/editarUsuarios.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Usuario actualizado correctamente.');
                                            location.reload();
                                        } else {
                                            alert('Error: ' + (data.error || 'No se pudo actualizar.'));
                                        }
                                    })
                                    .catch(() => alert('Error al actualizar el usuario.'));
                                    cerrarModal();
                                }
                            })">Actualizar</button>

                        <?php if ($privilegio === 'administrador'): ?>
                            <a
                                href="?eliminar=<?= (int)$row['idUsuario']; ?>"
                                class="action-btn delete-btn"
                                onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">Eliminar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile;
            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            } ?>
        </div>
    </div>

    <?php include '../componentes/modal.php'; ?>
</body>

</html>