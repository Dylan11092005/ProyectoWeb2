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
    if (eliminarPorId($conexion, 'propiedades', 'idPropiedad', $idEliminar)) {
        header('Location: administrarPropiedades.php?msg=eliminado');
        exit();
    } else {
        echo '<div class="mensajeError">Error al eliminar la propiedad.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Propiedades</title>
    <link rel="stylesheet" href="../estilos/estiloAdministrar.css">
    <link rel="stylesheet" href="../estilos/estilosUsuarios.css">
</head>
<body>
    <?php mostrarBotonRegresar('administrar.php'); ?>
    <div class="container">
        <div class="header-usuarios">
            <h1 class="titulo">Propiedades</h1>
            <?php if ($privilegio === 'administrador' || $privilegio === 'agente'): ?>
                <form action="agregar/agregarPropiedad.php" method="POST" class="form-agregar-usuario">
                    <button type="submit" class="btn btn-agregar">Agregar propiedad</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php
            if ($privilegio === 'administrador') {
                $resultado = $conexion->query("SELECT * FROM propiedades ORDER BY idPropiedad DESC");
            } else {

                $stmtAgente = $conexion->prepare("SELECT idUsuario FROM usuarios WHERE usuario = ? LIMIT 1");
                $stmtAgente->bind_param("s", $usuarioSesion);
                $stmtAgente->execute();
                $resAgente = $stmtAgente->get_result();
                $idAgente = 0;
                if ($rowAgente = $resAgente->fetch_assoc()) {
                    $idAgente = (int)$rowAgente['idUsuario'];
                }
                $stmtAgente->close();
                $stmt = $conexion->prepare("SELECT * FROM propiedades WHERE idAgente = ?");
                $stmt->bind_param("i", $idAgente);
                $stmt->execute();
                $resultado = $stmt->get_result();
            }
            while ($row = $resultado->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-body">
                        <img src="../uploads/<?php echo htmlspecialchars($row['imagen_destacada'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="Imagen destacada" class="card-img">
                        <h5 class="card-title">Título: <?= htmlspecialchars($row['titulo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h5>
                        <p class="card-text"><strong>Tipo:</strong> <?= htmlspecialchars($row['tipo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Destacada:</strong> <?= ($row['destacada'] ?? 0) ? 'Sí' : 'No'; ?></p>
                        <p class="card-text"><strong>Descripción breve:</strong> <?= htmlspecialchars($row['descripcionBreve'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Precio:</strong> $<?= htmlspecialchars($row['precio'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php
                        $nombreAgente = '';
                        if (!empty($row['idAgente'])) {
                            $idAgente = (int)$row['idAgente'];
                            $resAgente = $conexion->query("SELECT nombre FROM usuarios WHERE idUsuario = $idAgente LIMIT 1");
                            if ($resAgente && $agente = $resAgente->fetch_assoc()) {
                                $nombreAgente = $agente['nombre'];
                            }
                        }
                        ?>
                        <p class="card-text"><strong>Agente:</strong> <?= htmlspecialchars($nombreAgente, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Ubicación:</strong> <?= htmlspecialchars($row['ubicacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Descripción larga:</strong> <?= nl2br(htmlspecialchars($row['descripcion_larga'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
                        <?php if (!empty($row['mapa'])): ?>
                        <div class="card-text"><strong>Mapa:</strong><br><?= $row['mapa']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-actions">
                        <?php
                        $agentes = [];
                        $resAgentes = $conexion->query("SELECT idUsuario, nombre FROM usuarios WHERE privilegio = 'agente'");
                        while ($agente = $resAgentes->fetch_assoc()) {
                            $agentes[] = [
                                'value' => $agente['idUsuario'],
                                'label' => $agente['nombre']
                            ];
                        }
                        ?>
                        <button
                            type="button"
                            class="action-btn update-btn"
                            onclick='abrirModal({
                                titulo: "Editar Propiedad",
                                boton: "Guardar Cambios",
                                campos: [
                                    {label: "Título", name: "titulo", type: "text", value: "<?= htmlspecialchars($row['titulo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>", required: true, maxlength: 150},
                                    {label: "Descripción breve", name: "descripcionBreve", type: "text", value: "<?= htmlspecialchars($row['descripcionBreve'] ?? '', ENT_QUOTES, 'UTF-8'); ?>", required: true, maxlength: 255},
                                    {label: "Precio", name: "precio", type: "number", value: "<?= htmlspecialchars($row['precio'] ?? '', ENT_QUOTES, 'UTF-8'); ?>", required: true, min: 0},
                                    {label: "Tipo", name: "tipo", type: "select", value: "<?= htmlspecialchars($row['tipo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>", required: true, options: [
                                        {value: "alquiler", label: "Alquiler"},
                                        {value: "venta", label: "Venta"}
                                    ]},
                                    <?php if ($privilegio !== 'agente'): ?>
                                    {label: "Agente de ventas", name: "idAgente", type: "select", value: "<?= htmlspecialchars($row['idAgente'] ?? '', ENT_QUOTES, 'UTF-8'); ?>", required: true, options: <?= htmlspecialchars(json_encode($agentes), ENT_QUOTES, 'UTF-8'); ?>},
                                    <?php endif; ?>
                                    {label: "Destacada", name: "destacada", type: "checkbox", value: "<?= ($row['destacada'] ?? 0); ?>"},
                                    {label: "Ubicación", name: "ubicacion", type: "text", value: "<?= htmlspecialchars($row['ubicacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>", maxlength: 255},
                                    {label: "Descripción larga", name: "descripcion_larga", type: "textarea", value: "<?= htmlspecialchars($row['descripcion_larga'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"},
                                    {label: "Mapa", name: "mapa", type: "textarea", value: "<?= htmlspecialchars($row['mapa'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"},
                                    {label: "Imagen destacada", name: "imagen_destacada", type: "file", value: "", required: false, accept: "image/*"}
                                ],
                                onsubmit: function(e, form) {
                                    const formData = new FormData(form);
                                    formData.append("idPropiedad", "<?= (int)($row['idPropiedad'] ?? 0); ?>");
                                    fetch("../componentes/editarPropiedad.php", {
                                        method: "POST",
                                        body: formData
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert("Propiedad actualizada correctamente.");
                                            location.reload();
                                        } else {
                                            alert("Error: " + (data.error || "No se pudo actualizar."));
                                        }
                                    })
                                    .catch(() => alert("Error al actualizar la propiedad."));
                                    cerrarModal();
                                }
                            });'
                        >Actualizar</button>
                        <?php if ($privilegio === 'administrador' || $privilegio === 'agente'): ?>
                            <a
                                href="?eliminar=<?= (int)$row['idPropiedad']; ?>"
                                class="action-btn delete-btn"
                                onclick="return confirm('¿Seguro que deseas eliminar esta propiedad?');">Eliminar</a>
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

