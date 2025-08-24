<?php
session_start();
include '../../conexion.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$privilegio = $_SESSION['privilegio'] ?? '';
$usuarioSesion = $_SESSION['usuario'] ?? '';

$agentes = [];
$resAgentes = $conexion->query("SELECT idUsuario, nombre FROM usuarios WHERE privilegio = 'agente'");
while ($agente = $resAgentes->fetch_assoc()) {
    $agentes[] = $agente;
}

$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcionBreve = trim($_POST['descripcionBreve'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $destacada = isset($_POST['destacada']) ? 1 : 0;
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $descripcion_larga = trim($_POST['descripcion_larga'] ?? '');
    $mapa = trim($_POST['mapa'] ?? '');
    $idAgente = null;
    if ($privilegio === 'agente') {
        $stmtAgente = $conexion->prepare("SELECT idUsuario FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmtAgente->bind_param("s", $usuarioSesion);
        $stmtAgente->execute();
        $resAgente = $stmtAgente->get_result();
        if ($rowAgente = $resAgente->fetch_assoc()) {
            $idAgente = (int)$rowAgente['idUsuario'];
        }
        $stmtAgente->close();
    } else {
        $idAgente = isset($_POST['idAgente']) && $_POST['idAgente'] !== '' ? (int)$_POST['idAgente'] : null;
    }

    if (empty($titulo)) $errores[] = 'El título es obligatorio.';
    if (empty($descripcionBreve)) $errores[] = 'La descripción breve es obligatoria.';
    if ($precio === '' || !is_numeric($precio) || $precio < 0) $errores[] = 'El precio debe ser un número positivo.';
    if (empty($tipo) || !in_array($tipo, ['alquiler', 'venta'])) $errores[] = 'El tipo es obligatorio.';
    if ($privilegio !== 'agente' && !$idAgente) $errores[] = 'Debes seleccionar un agente de ventas.';


    if (empty($errores)) {
        $imagen_destacada = null;
        if (isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] == UPLOAD_ERR_OK) {
            $nombre_archivo = uniqid() . '_' . basename($_FILES['imagen_destacada']['name']);
            $ruta_archivo = '../../uploads/' . $nombre_archivo;
            if (move_uploaded_file($_FILES['imagen_destacada']['tmp_name'], $ruta_archivo)) {
                $imagen_destacada = $nombre_archivo;
            }
        }

        $stmt = $conexion->prepare("INSERT INTO propiedades (titulo, descripcionBreve, precio, tipo, destacada, ubicacion, descripcion_larga, mapa, imagen_destacada, idAgente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdssssssi', $titulo, $descripcionBreve, $precio, $tipo, $destacada, $ubicacion, $descripcion_larga, $mapa, $imagen_destacada, $idAgente);
        $exito = $stmt->execute();
        $stmt->close();

        if ($exito) {
            header('Location: ../administrarPropiedades.php?msg=agregado');
            exit;
        } else {
            $errores[] = 'No se pudo agregar la propiedad.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Propiedad</title>
    <link rel="stylesheet" href="../../estilos/estiloLogin.css?202405=<?php echo (rand()); ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" href="../../estilos/estiloAdministrar.css">
</head>

<body>
    <?php require_once '../../componentes/botonRegresar.php';
    mostrarBotonRegresar('../administrarPropiedades.php'); ?>
    <div class="contenedorLogin" style="max-width: 420px;">
        <h2>Agregar Propiedad</h2>
        <?php if (!empty($errores)): ?>
            <div style="background:#ffc107;color:#18184d;padding:10px;margin-bottom:15px;border-radius:8px;font-weight:bold;">
                <?php foreach ($errores as $err): ?>
                    <div><?php echo $err; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" maxlength="150" required>
            <label for="descripcionBreve">Descripción breve:</label>
            <input type="text" id="descripcionBreve" name="descripcionBreve" maxlength="255" required>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" min="0" step="0.01" required>
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="alquiler">Alquiler</option>
                <option value="venta">Venta</option>
            </select>
            <label for="destacada">Destacada:</label>
            <select id="destacada" name="destacada" required>
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" maxlength="255">
            <label for="descripcion_larga">Descripción larga:</label>
            <textarea id="descripcion_larga" name="descripcion_larga"></textarea>
            <label for="mapa">Mapa:</label>
            <textarea id="mapa" name="mapa"></textarea>
            <label for="imagen_destacada">Imagen destacada:</label>
            <input type="file" id="imagen_destacada" name="imagen_destacada" accept="image/*">
            <?php if ($privilegio !== 'agente'): ?>
                <label for="idAgente">Agente de ventas:</label>
                <select id="idAgente" name="idAgente" required>
                    <option value="">Seleccione un agente</option>
                    <?php foreach ($agentes as $agente): ?>
                        <option value="<?= $agente['idUsuario']; ?>"><?= htmlspecialchars($agente['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <button type="submit">Agregar Propiedad</button>
        </form>
    </div>
</body>

</html>