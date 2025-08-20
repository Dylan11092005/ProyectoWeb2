<?php
header('Content-Type: application/json');
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$idPropiedad = isset($_POST['idPropiedad']) ? intval($_POST['idPropiedad']) : 0;
$titulo = $_POST['titulo'] ?? '';
$descripcionBreve = $_POST['descripcionBreve'] ?? '';
$precio = $_POST['precio'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$destacada = isset($_POST['destacada']) ? 1 : 0;
$ubicacion = $_POST['ubicacion'] ?? '';
$descripcion_larga = $_POST['descripcion_larga'] ?? '';
$mapa = $_POST['mapa'] ?? '';

$imagen_destacada = null;
if (isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] == UPLOAD_ERR_OK) {
    $nombre_archivo = uniqid() . '_' . basename($_FILES['imagen_destacada']['name']);
    $ruta_archivo = '../uploads/' . $nombre_archivo;
    if (move_uploaded_file($_FILES['imagen_destacada']['tmp_name'], $ruta_archivo)) {
        $imagen_destacada = $nombre_archivo;
    }
}
if (!$imagen_destacada) {
    $res = $conexion->query("SELECT imagen_destacada FROM propiedades WHERE idPropiedad = $idPropiedad LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $imagen_destacada = $row['imagen_destacada'];
    }
}

$stmt = $conexion->prepare("UPDATE propiedades SET titulo=?, descripcionBreve=?, precio=?, tipo=?, destacada=?, ubicacion=?, descripcion_larga=?, mapa=?, imagen_destacada=? WHERE idPropiedad=?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Error en la consulta']);
    exit;
}
$stmt->bind_param('ssdssssssi', $titulo, $descripcionBreve, $precio, $tipo, $destacada, $ubicacion, $descripcion_larga, $mapa, $imagen_destacada, $idPropiedad);
$exito = $stmt->execute();
$stmt->close();

if ($exito) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo actualizar']);
}
